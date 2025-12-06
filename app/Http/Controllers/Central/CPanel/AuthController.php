<?php

namespace App\Http\Controllers\Central\CPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function login()
    {
        return view('central.cpanel.auth.login');
    }

    function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->guard(CPANEL_ADMINS_GUARD)->attempt($credentials)) {
            return redirect()->route('cpanel.dashboard');
        }

        return redirect()->back()->withErrors(['Invalid credentials provided.']);
    }
}
