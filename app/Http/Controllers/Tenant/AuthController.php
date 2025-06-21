<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function login() {
        return view('admin.login');
    }

    function postLogin(Request $request) {
        $request->validate([
            'email'=>'required|exists:admins,email',
            'password'=>'required'
        ]);

        if(!auth(TENANT_ADMINS_GUARD)->attempt($request->only(['email','password']))){
            return back()->with('error','Invalid Credentials!')->withInput();
        }

        return redirect('admin');
    }
}
