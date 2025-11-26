<?php

namespace App\Http\Controllers\Central\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTenantRequest;
use App\Mail\AdminRegisterRequestMail;
use App\Mail\RegisterRequestAcceptMail;
use App\Mail\RegisterRequestMail;
use App\Models\Country;
use App\Models\RegisterRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    function register() {
        $countries = Country::select('id','name')->get();
        return view('central.tenant.register', get_defined_vars());
    }

    function postRegister(RegisterTenantRequest $request) {

        $data = $request->validated();

        $registerRequest = RegisterRequest::create([
            'data'=>$data,
            'status'=>'pending'
        ]);

        Mail::to($data['email'])->send(new RegisterRequestMail([
            'name' => $data['id'],
        ]));

        Mail::to(env('ADMIN_EMAIL'))->send(new AdminRegisterRequestMail(
            registerRequest: $registerRequest
        ));

        return redirect()->back()->with('success',__('Register request submitted successfully. We will contact you soon.'));
    }

    function acceptRegistration($id) {
        $registerRequest = RegisterRequest::findOrFail($id);

        if($registerRequest->status != 'pending') {
            return "This request is already processed.";
        }

        // request validation RegisterTenantRequest $request
        $rules = (new RegisterTenantRequest())->rules();

        $validator = Validator::make($registerRequest->data + [
            'password_confirmation'=>$registerRequest->data['password'] ?? null,
        ], $rules);
        if ($validator->fails()) {
            return "The registration data is invalid: " . implode(", ", $validator->errors()->all());
        }

        $data = $registerRequest->data;

        $request = (object)$data;
        // make request id is valid for database name
        $id = preg_replace('/[^a-zA-Z0-9_]/', '_', $request->id);
        $tenant = Tenant::create([
            'id'=>$id,
            'name'=> $request->id,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'active'=>false,
        ]);

        $domain = $tenant->domains()->create([
            'domain'=>$request->domain_mode == 'domain' ? $request->domain : $request->subdomain . '.' . str_replace(['http://','https://'],'',url('/')),
        ]);


        Artisan::call('tenants:seed',[
            '--tenants'=>[$tenant['id']],
        ]);

        $_request = json_encode([
            'name'=>$request->id,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=>$request->password,
            'type'=>'super_admin',
            'country_id'=>$request->country_id,
        ]);

        Artisan::call('tenants:run',[
            'commandname'=>'app:tenant-create-admin',
            '--tenants'=>[$tenant['id']],
            '--argument'=>["request=$_request"]
        ]);

        Mail::to($request->email)->send(new RegisterRequestAcceptMail([
            'name' => $request->id,
            'email' => $request->email,
            'domain' => $request->domain,
        ]));

        $registerRequest->update([
            'status'=>'approved'
        ]);

        return "Done";
    }
}
