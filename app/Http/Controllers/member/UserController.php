<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // Pastikan model Role ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $memberId = Auth::guard('member')->id();
        $users = User::with('role')->where('member_id', $memberId)->get();
        $roles = Role::all(); // Ambil semua role untuk dropdown

        // Hitung sisa kuota user
        $member = Auth::guard('member')->user();
        $currentUserCount = $users->count();
        $maxUsers = $this->getMaxUsersForMembership($member->membership_id);
        $remainingQuota = $maxUsers - $currentUserCount;

        return view('member.users.index', compact('users', 'roles', 'currentUserCount', 'maxUsers', 'remainingQuota'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('member.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $member = Auth::guard('member')->user();

        // Cek batasan membership
        $currentUserCount = User::where('member_id', $member->id)->count();
        $maxUsers = $this->getMaxUsersForMembership($member->membership_id);

        if ($currentUserCount >= $maxUsers) {
            return redirect()->route('member.users.index')
                ->with('error', "You have reached your membership limit of {$maxUsers} users. Please upgrade your membership to create more users.");
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id', // Validasi role_id harus ada di tabel roles
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'member_id' => Auth::guard('member')->id(),
            'role_id'   => $request->role_id, // Gunakan role_id dari request
        ]);

        $remainingQuota = $maxUsers - ($currentUserCount + 1);
        $message = "User created successfully. You have {$remainingQuota} user slots remaining.";

        return redirect()->route('member.users.index')->with('success', $message);
    }

    public function edit(User $user)
    {
        // Pastikan hanya user milik member yang bisa diedit
        $this->authorizeUser($user);
        $roles = Role::all();
        return view('member.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id', // Validasi role_id
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id, // Update role_id
        ]);

        return redirect()->route('member.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizeUser($user);
        $user->delete();

        return redirect()->route('member.users.index')->with('success', 'User deleted successfully.');
    }

    protected function authorizeUser(User $user)
    {
        if ($user->member_id !== Auth::guard('member')->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Get maximum users allowed based on membership_id
     */
    private function getMaxUsersForMembership($membershipId)
    {
        switch ($membershipId) {
            case 1:
                return 10;
            case 2:
                return 1000;
            case 3:
                return 5000;
            default:
                return 0; // Atau bisa throw exception jika membership tidak valid
        }
    }

    /**
     * Get membership name for display
     */
    private function getMembershipName($membershipId)
    {
        switch ($membershipId) {
            case 1:
                return 'Basic';
            case 2:
                return 'Premium';
            case 3:
                return 'Enterprise';
            default:
                return 'Unknown';
        }
    }
}
