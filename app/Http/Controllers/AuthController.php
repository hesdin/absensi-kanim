<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginCheck(Request $request)
    {

        // Validate
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:3',
        ],
        [
            'email.exists' => 'This email is not exists'
        ]);

        $creds = $request->only('email', 'password');

        if (Auth::attempt($creds)) {
            return redirect()->route('dashboard');
        } else {
            return back()->with('fail', 'Gagal Login, Email atau Password salah !');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // ADMIN

    public function loginAdmin()
    {
        return view('auth.login-admin');
    }

    public function loginAdminCheck(Request $request)
    {
        // Validate
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:3',
        ],
        [
            'email.exists' => 'This email is not exists'
        ]);

        $creds = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($creds)) {
            return redirect()->route('admin.dashboard');
        } else {
            return back()->with('fail', 'Gagal Login, Email atau Password salah !');
        }
    }

    public function logoutAdmin()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
