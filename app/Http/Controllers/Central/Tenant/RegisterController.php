<?php

namespace App\Http\Controllers\Central\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTenantRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class RegisterController extends Controller
{
    function register() {
        return view('central.tenant.register');
    }

    function postRegister(RegisterTenantRequest $request) {
        $tenant = Tenant::create([
            'id'=>$request->id,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'active'=>false,
        ]);

        $domain = $tenant->domains()->create([
            'domain'=>$request->domain
        ]);

        Artisan::call('tenants:seed',[
            '--tenants'=>[$tenant['id']],
        ]);

        $_request = json_encode([
            'name'=>$request->id,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=>$request->password,
            'type'=>'super_admin'
        ]);

        Artisan::call('tenants:run',[
            'commandname'=>'app:tenant-create-admin',
            '--tenants'=>[$tenant['id']],
            '--argument'=>["request=$_request"]
        ]);

        return back()->with('success','Domain Created Successfully, please wait till Contact you.');
    }
}
