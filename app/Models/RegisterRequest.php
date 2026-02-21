<?php

namespace App\Models;

use App\Mail\RegisterRequestAcceptMail;
use App\Services\PlanPricingService;
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
            if ($model->status == 'approved' && $model->wasChanged('status')) {

                $data = $model->data;
                $request = (object) $data;
                // make request id is valid for database name
                $id = Str::slug($request->company['name'], '_');
                $id = preg_replace('/[^a-zA-Z0-9_]/', '_', $id);

                if (Tenant::find($id)) {
                    return;
                }

                $period = ($request->plan['period'] ?? 'month') === 'year' ? 'year' : 'month';

                $modules = ['pos', 'hrm', 'booking'];
                $selectedPlanIds = collect($request->plan['selected_plans'] ?? [])
                    ->filter(fn ($id) => !empty($id))
                    ->map(fn ($id) => (int) $id)
                    ->values();

                $plansById = $selectedPlanIds->isNotEmpty()
                    ? Plan::query()->whereIn('id', $selectedPlanIds)->where('active', true)->get()->keyBy('id')
                    : collect();

                $selectedSystemPlans = [];
                foreach ($modules as $module) {
                    $planId = (int) ($request->plan['selected_plans'][$module] ?? 0);
                    if ($planId <= 0) {
                        continue;
                    }

                    $plan = $plansById->get($planId);
                    if (!$plan) {
                        continue;
                    }

                    $planModule = is_object($plan->module_name) ? $plan->module_name->value : (string) $plan->module_name;
                    if ($planModule !== $module) {
                        continue;
                    }

                    $selectedSystemPlans[$module] = $plan;
                }

                // Backward compatibility: legacy requests store a single plan id.
                if (count($selectedSystemPlans) === 0) {
                    $legacyPlan = Plan::find($request->plan['id'] ?? null);
                    if ($legacyPlan) {
                        $legacyModule = is_object($legacyPlan->module_name) ? $legacyPlan->module_name->value : (string) $legacyPlan->module_name;
                        if (in_array($legacyModule, $modules, true)) {
                            $selectedSystemPlans[$legacyModule] = $legacyPlan;
                        } else {
                            $selectedSystemPlans['pos'] = $legacyPlan;
                        }
                    }
                }

                if (count($selectedSystemPlans) === 0) {
                    return;
                }

                $systemsAllowed = array_values(array_keys($selectedSystemPlans));
                $systemsCount = max(1, count($systemsAllowed));

                $pricingBySystem = [];
                $totalPrice = 0.0;
                $payableNow = 0.0;
                $maxTrialMonths = 0;

                foreach ($selectedSystemPlans as $module => $plan) {
                    $modulePricing = app(PlanPricingService::class)->calculate($plan, $period, $systemsCount);
                    $modulePrice = (float) ($modulePricing['final_price'] ?? 0);
                    $moduleTrialMonths = (int) ($modulePricing['free_trial_months'] ?? 0);

                    $pricingBySystem[$module] = [
                        'plan_id' => $plan->id,
                        'plan_name' => $plan->name,
                        'pricing' => $modulePricing,
                        'payable_now' => $moduleTrialMonths > 0 ? 0.0 : $modulePrice,
                    ];

                    $totalPrice += $modulePrice;
                    if ($moduleTrialMonths === 0) {
                        $payableNow += $modulePrice;
                    }
                    $maxTrialMonths = max($maxTrialMonths, $moduleTrialMonths);
                }

                $pricing = [
                    'period' => $period,
                    'systems_count' => $systemsCount,
                    'per_system' => $pricingBySystem,
                    'total_price' => round($totalPrice, 2),
                    'due_now' => round($payableNow, 2),
                    'total_discount_amount' => 0,
                    'free_trial_months' => $maxTrialMonths,
                    'is_free_trial' => $maxTrialMonths > 0,
                ];

                $primaryPlan = collect($selectedSystemPlans)->first();

                $tenant = Tenant::create([
                    'id' => $id,
                    'name' => $request->company['name'],
                    'phone' => $request->company['phone'],
                    'email' => $request->company['email'],
                    'country_id' => $request->company['country_id'],
                    'currency_id' => $request->company['currency_id'],
                    'address' => $request->company['address'] ?? null,
                    'tax_number' => $request->company['tax_number'] ?? null,
                    'active' => false,
                ]);

                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $primaryPlan?->id,
                    'plan_details' => array_merge($primaryPlan?->toArray() ?? [], [
                        'pricing' => $pricing,
                        'selected_systems' => $systemsAllowed,
                        'selected_system_plans' => collect($selectedSystemPlans)->map(function ($plan, $module) {
                            return [
                                'module' => $module,
                                'id' => $plan->id,
                                'name' => $plan->name,
                                'slug' => $plan->slug,
                            ];
                        })->values()->all(),
                    ]),
                    'price' => (float) ($pricing['due_now'] ?? 0),
                    'systems_allowed' => $systemsAllowed,
                    'start_date' => now(),
                    'end_date' => now()->addMonths(app(PlanPricingService::class)->cycleMonths($period) + (int) ($pricing['free_trial_months'] ?? 0)),
                    'status' => 'paid',
                    // 'payment_gateway',
                    // 'payment_details',
                    // 'payment_callback_details',
                    'billing_cycle' => $period == 'month' ? 'monthly' : 'yearly',
                ]);

                $domain = $tenant->domains()->create([
                    'domain' => $request->company['domain']
                ]);


                Artisan::call('tenants:seed', [
                    '--tenants' => [$tenant['id']],
                ]);

                $_request = json_encode([
                    'name' => $request->admin['name'],
                    'phone' => $request->admin['phone'],
                    'email' => $request->admin['email'],
                    'password' => $request->admin['password'],
                    'type' => 'super_admin',
                    'country_id' => $request->admin['country_id'] ?? null,
                ]);

                Artisan::call('tenants:run', [
                    'commandname' => 'app:tenant-create-admin',
                    '--tenants' => [$tenant['id']],
                    '--argument' => ["request=$_request"]
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
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
