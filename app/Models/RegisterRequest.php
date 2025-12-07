<?php

namespace App\Models;

use App\Mail\RegisterRequestAcceptMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterRequest extends Model
{
    // boot method to set default values
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if($model->status == 'approved'){
                $data = $model->data;

                $request = (object)$data;
                // make request id is valid for database name
                $id = Str::slug($request->company['name'], '_');
                $id = preg_replace('/[^a-zA-Z0-9_]/', '_', $id);

                $plan = Plan::find($request->plan['id']);
                $period = $request->plan['period'] ?? 'month';

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
                    'plan_details' => $plan?->toArray() ?? [],
                    'price' => $plan->{'price_' . $period} ?? 0,
                    'systems_allowed' => ['pos'],
                    'start_date' => now(),
                    'end_date' => now()->addMonths($period == 'month' ? 1 : 12),
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
            }
        });
    }

    protected $fillable = [
        'data',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
