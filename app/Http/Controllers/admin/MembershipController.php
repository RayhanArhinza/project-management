<?php

namespace App\Http\Controllers\admin;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();
        return view('admin.memberships.index', compact('memberships'));
    }

    public function create()
    {
        return view('admin.memberships.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'valid_days' => 'nullable|integer|min:0', // Allow null or 0
            'price' => 'nullable|numeric|min:0', // Allow null or 0
        ]);

        Membership::create($request->only('name', 'valid_days', 'price'));

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil ditambahkan.');
    }

    public function edit(Membership $membership)
    {
        return view('admin.memberships.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'valid_days' => 'nullable|integer|min:0', // Allow null or 0
            'price' => 'nullable|numeric|min:0', // Allow null or 0
        ]);

        $membership->update($request->only('name', 'valid_days', 'price'));

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil diperbarui.');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil dihapus.');
    }
}
