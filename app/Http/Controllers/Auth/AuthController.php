<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();

        // Cek apakah user memiliki relasi member dan membership
        if ($user->member) {
            // ❌ Cek jika membership_id = 1, tolak login
            // if ($user->member->membership_id == 1) {
            //     Auth::logout();
            //     return back()->withErrors([
            //         'membership' => 'Akun Anda belum memiliki membership aktif.'
            //     ])->withInput();
            // }

            // ✅ Cek apakah membership expired
            if ($user->member->end_date && Carbon::parse($user->member->end_date)->isPast()) {
                Auth::logout();
                return back()->withErrors([
                    'membership' => 'Maaf, membership Anda telah habis. Silahkan perbarui membership Anda.'
                ])->withInput();
            }
        }

        return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
    }

    return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
}


    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
