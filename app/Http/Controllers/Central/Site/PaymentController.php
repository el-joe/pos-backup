<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use App\Mail\AdminRegisterRequestMail;
use App\Mail\RegisterRequestMail;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\RegisterRequest;
use App\Payments\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\info;

class PaymentController extends Controller
{
    function callback(Request $request,$type) {
        if($type == 'check'){
            $pt = PaymentTransaction::where('transaction_reference',$request->token)->first();

            if (!$pt) {
                return redirect()->route('payment-callback', ['type' => 'failed']);
            }

            $paymentProvider = 'App\\Payments\\Providers\\' . ($pt->paymentMethod?->provider ?? 'Paypal');

            $paymentService = new PaymentService(new $paymentProvider());
            $captureResult = $paymentService->capture($request->token);
            if($captureResult['status'] == 'COMPLETED'){
                $type = 'success';
                $data = $pt->request_payload['metadata'] ?? null;
                $pt->update([
                    'status'=>'success',
                    'response_payload'=>$captureResult,
                ]);
            }else{
                $type = 'failed';
                $pt->update([
                    'status'=>'failed',
                    'response_payload'=>$captureResult,
                ]);
            }
        }

        if($type == 'success'){
            try{
                if(!isset($data)){
                    $data = $request->query('data') ?? null;
                }

                // $data may be:
                // - an encoded string (our token)
                // - an already-decoded array
                if (is_string($data)) {
                    $data = decodedData($data);
                }

                if (!is_array($data)) {
                    return redirect()->route('payment-callback', ['type' => 'failed']);
                }

                $period = ($data['period'] ?? 'month') === 'year' ? 'year' : 'month';
                $systemsAllowed = collect($data['systems_allowed'] ?? [])
                    ->filter(fn ($system) => in_array($system, ['pos', 'hrm', 'booking'], true))
                    ->unique()
                    ->values()
                    ->all();
                if (count($systemsAllowed) === 0) {
                    $systemsAllowed = ['pos'];
                }

                $planPayload = [
                    'id' => $data['plan_id'] ?? 1,
                    'period' => $period,
                    'systems_allowed' => $systemsAllowed,
                ];

                if (isset($data['selected_plans']) && is_array($data['selected_plans'])) {
                    $planPayload['selected_plans'] = $data['selected_plans'];
                }
                if (isset($data['selected_system_plans']) && is_array($data['selected_system_plans'])) {
                    $planPayload['selected_system_plans'] = $data['selected_system_plans'];
                }
                if (isset($data['pricing']) && is_array($data['pricing'])) {
                    $planPayload['pricing'] = $data['pricing'];
                }

                $registerRequest = RegisterRequest::create([
                    'data'=>[
                        'company' => [
                            'name' => $data['company_name'],
                            'email' => $data['company_email'],
                            'phone' => $data['company_phone'],
                            'country_id' => $data['country_id'],
                            'tax_number' => $data['tax_number'] ?? null,
                            'address' => $data['address'] ?? null,
                            'domain' => $data['final_domain'],
                            'currency_id' => $data['currency_id'],
                        ],
                        'admin' => [
                            'name' => $data['admin_name'],
                            'email' => $data['admin_email'],
                            'phone' => $data['admin_phone'] ?? null,
                            'password' => $data['admin_password'],
                        ],
                        'plan' => $planPayload,
                        'partner_id' => $data['partner_id'] ?? null,
                    ],
                    'status'=>'pending'
                ]);

                Mail::to($data['company_email'])->send(new RegisterRequestMail([
                    'name' => $data['company_name'],
                ]));

                Mail::to(env('ADMIN_EMAIL','eljoe1717@gmail.com'))->send(new AdminRegisterRequestMail(
                    registerRequest: $registerRequest
                ));
            }catch(\Exception $e){
                report($e);
                return redirect()->route('payment-callback', ['type' => 'failed']);
            }
        }

        return redirect()->route('payment-callback',['type'=>$type]);
    }

    function paymentCallbackPage(Request $request,$type) {
        return view('central.site.payment-callback',get_defined_vars());
    }
}
