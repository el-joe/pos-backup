<?php

namespace App\Http\Controllers\Central\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTenantRequest;
use App\Mail\AdminRegisterRequestMail;
use App\Mail\RegisterRequestAcceptMail;
use App\Mail\RegisterRequestMail;
use App\Models\Country;
use App\Models\Plan;
use App\Models\RegisterRequest;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\PlanPricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        Mail::to(env('ADMIN_EMAIL','eljoe1717@gmail.com'))->send(new AdminRegisterRequestMail(
            registerRequest: $registerRequest
        ));

        if(RateLimiter::tooManyAttempts($request->ip(), 2)) {
            return redirect()->back()->withErrors(['Too many registration attempts. Please try again later.']);
        }

        RateLimiter::hit($request->ip(), 600);

        return redirect()->back()->with('success',__('Register request submitted successfully. We will contact you soon.'));
    }

    function acceptRegistration($id) {
        $registerRequest = RegisterRequest::findOrFail($id);

        if($registerRequest->status != 'pending') {
            return "This request is already processed.";
        }

        // request validation RegisterTenantRequest $request
        $rules = [
            'company.name'=>'required|string|max:255|unique:tenants,id|regex:/^[a-zA-Z0-9_ ]+$/',
            'company.email'=>'required|email|max:255',
            'company.phone'=>'required|string|max:50',
            'company.domain'=>'required|string|max:255|unique:domains,domain',
            'company.address'=>'nullable|string|max:500',
            'company.country_id'=>'required|exists:countries,id',
            'company.currency_id'=>'required|exists:currencies,id',
            'company.tax_number'=>'nullable|string|max:100',
            'admin.name'=>'required|string|max:255',
            'admin.email'=>'required|email|max:255',
            'admin.phone'=>'nullable|string|max:50',
            'admin.password'=>'required|string|min:6',
        ];

        $validator = Validator::make($registerRequest->data, $rules);
        if ($validator->fails()) {
            return "The registration data is invalid: " . implode(", ", $validator->errors()->all());
        }

        $data = $registerRequest->data;

        $request = (object)$data;
        // make request id is valid for database name
        $id = Str::slug($request->company['name'], '_');
        $id = preg_replace('/[^a-zA-Z0-9_]/', '_', $id);

        $plan = Plan::find($request->plan['id']);
        $period = $request->plan['period'] ?? 'month';
        $systemsAllowed = collect($request->plan['systems_allowed'] ?? ['pos'])
            ->filter(fn ($system) => in_array($system, ['pos', 'hrm', 'booking'], true))
            ->unique()
            ->values()
            ->all();
        if (count($systemsAllowed) === 0) {
            $systemsAllowed = ['pos'];
        }

        $pricing = $plan
            ? app(PlanPricingService::class)->calculate($plan, $period, count($systemsAllowed))
            : [
                'final_price' => 0,
                'free_trial_months' => 0,
            ];
        $cycleMonths = app(PlanPricingService::class)->cycleMonths($period);
        $payableNow = ((int) ($pricing['free_trial_months'] ?? 0) > 0) ? 0.0 : (float) ($pricing['final_price'] ?? 0);

        $tenant = Tenant::create([
            'id'=>$id,
            'name'=> $request->company['name'],
            'phone'=>$request->company['phone'],
            'email'=>$request->company['email'],
            'country_id'=>$request->company['country_id'],
            'currency_id'=>$request->company['currency_id'],
            'address'=>$request->company['address'] ?? null,
            'tax_number'=>$request->company['tax_number'] ?? null,
            'active'=>false,
        ]);

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan?->id,
            'plan_details' => array_merge($plan?->toArray() ?? [], [
                'pricing' => $pricing,
                'selected_systems' => $systemsAllowed,
            ]),
            'price' => $payableNow,
            'systems_allowed' => $systemsAllowed,
            'start_date' => now(),
            'end_date' => now()->addMonths($cycleMonths + (int) ($pricing['free_trial_months'] ?? 0)),
            'status' => 'paid',
            // 'payment_gateway',
            // 'payment_details',
            // 'payment_callback_details',
            'billing_cycle' => $period == 'month' ? 'monthly' : 'yearly',
        ]);

        $domain = $tenant->domains()->create([
            'domain'=> $request->company['domain']
        ]);


        Artisan::call('tenants:seed',[
            '--tenants'=>[$tenant['id']],
        ]);

        $_request = json_encode([
            'name'=>$request->admin['name'],
            'phone'=>$request->admin['phone'],
            'email'=>$request->admin['email'],
            'password'=>$request->admin['password'],
            'type'=>'super_admin',
            'country_id'=>$request->admin['country_id'] ?? null,
        ]);

        Artisan::call('tenants:run',[
            'commandname'=>'app:tenant-create-admin',
            '--tenants'=>[$tenant['id']],
            '--argument'=>["request=$_request"]
        ]);

        Mail::to($request->company['email'])->send(new RegisterRequestAcceptMail([
            'name' => $request->company['name'],
            'email' => $request->company['email'],
            'domain' => $request->company['domain']
        ]));

        $registerRequest->update([
            'status'=>'approved'
        ]);

        return "Done";
    }
}
