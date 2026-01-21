<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    // 1. Tampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
