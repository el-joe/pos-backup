<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Admin;
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

        $user = Admin::where('email',$request->email)->first();

        if(!$user){
            return back()->with('error','Email not found')->withInput();
        }

        if(!$user->active){
            return back()->with('error','Your account is deactivated! Please contact the system administrator.')->withInput();
        }

        if(!auth(TENANT_ADMINS_GUARD)->attempt($request->only(['email','password']))){
            return back()->with('error','incorrect password!')->withInput();
        }

        return redirect('admin');
    }

    function switchBranch($branch = null) {
        if(!adminCan('branches.switch')){
            return redirect()->back()->with('error',__('general.messages.you_do_not_have_permission_to_access'));
        }
        admin()->update(['branch_id' => $branch]);

        return redirect()->back();
    }

    function logout() {
        auth(TENANT_ADMINS_GUARD)->logout();
        session()->flush();
        return redirect()->route('admin.login');
    }

    function markAsRead($id) {
        $notification = admin()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }
}
