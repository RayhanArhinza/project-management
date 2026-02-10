<?php

namespace App\Http\Controllers\admin;

use App\Models\Member;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $memberships = Membership::all();

        // Update expired memberships
        $this->updateExpiredMemberships();

        // Mulai query untuk model Member
        $query = Member::query();

        // Filter berdasarkan membership jika parameter membership_id tersedia
        if ($request->filled('membership_id')) {
            $query->where('membership_id', $request->membership_id);
        }

        // Filter pencarian untuk nama dan email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->get();

        return view('admin.members.index', compact('members', 'memberships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $memberships = Membership::all();
        return view('admin.members.create', compact('memberships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:members',
            'password'      => 'required|string|min:8',
            'membership_id' => 'required|exists:memberships,id',
        ]);

        // Get membership to calculate dates
        $membership = Membership::find($request->membership_id);
        $startDate = Carbon::now();
        $endDate = null;

        // Calculate end date if membership has valid_days
        if ($membership->valid_days > 0) {
            $endDate = $startDate->copy()->addDays($membership->valid_days);
        }

        Member::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'membership_id' => $request->membership_id,
            'start_date'    => $startDate->toDateString(),
            'end_date'      => $endDate ? $endDate->toDateString() : null,
        ]);

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return view('admin.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        $memberships = Membership::all();
        return view('admin.members.edit', compact('member', 'memberships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:members,email,' . $member->id,
            'password'      => 'nullable|string|min:8',
            'membership_id' => 'required|exists:memberships,id',
        ]);

        $data = $request->only(['name', 'email', 'membership_id']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Check if membership changed
        if ($request->membership_id != $member->membership_id) {
            $membership = Membership::find($request->membership_id);
            $startDate = Carbon::now();
            $endDate = null;

            // Calculate new end date if membership has valid_days
            if ($membership->valid_days > 0) {
                $endDate = $startDate->copy()->addDays($membership->valid_days);
            }

            $data['start_date'] = $startDate->toDateString();
            $data['end_date'] = $endDate ? $endDate->toDateString() : null;
        }

        $member->update($data);

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully');
    }

    /**
     * Update expired memberships to membership_id = 1
     */
    private function updateExpiredMemberships()
    {
        Member::where('end_date', '<', Carbon::now()->toDateString())
              ->where('membership_id', '!=', 1)
              ->update([
                  'membership_id' => 1,
                  'start_date' => null,
                  'end_date' => null
              ]);
    }
}
