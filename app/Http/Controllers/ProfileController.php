<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profile.
     */
    public function index()
    {
        $user = auth()->user();
        // Pastikan view 'profile.index' telah disiapkan
        return view('profile.index', compact('user'));
    }

    /**
     * Update data profile user.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi input dari form profile
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'      => 'nullable|min:8|confirmed',
            'alamat'        => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // hanya file jpg, jpeg, png
        ]);

        // Update data user
        $user->name  = $validatedData['name'];
        $user->email = $validatedData['email'];
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        // Update data profile (buat jika belum ada)
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $profile->alamat        = $validatedData['alamat'] ?? $profile->alamat;
        $profile->nomor_telepon = $validatedData['nomor_telepon'] ?? $profile->nomor_telepon;

        // Jika ada file avatar yang diupload
        if ($request->hasFile('avatar')) {
            $avatar     = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('avatars'), $avatarName);
            $profile->avatar = $avatarName;
        }

        $profile->save();

        return redirect()->back()->with('success', 'Profile berhasil diupdate.');
    }
}
