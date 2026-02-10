<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
        return redirect()->route('members.index')->with('success', 'Login berhasil!');
    }

    return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
}


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login')->with('success', 'Logout berhasil!');
    }
}
