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

        info('PaymentController callback check called with type : ' . $type);
        info(json_encode($request->all()));
        info('------------');

        if($type == 'check'){
            $pt = PaymentTransaction::where('transaction_reference',$request->token)->first();

            $paymentProvider = 'App\\Payments\\Providers\\'.($pt->paymentMethod->provider ?? 'Paypal');

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
                $data = decodedData($data ?? $request->query('data'));
                $registerRequest = RegisterRequest::create([
                    'data'=>[
                        'company' => [
                            'name' => $data['company_name'],
                            'email' => $data['company_email'],
                            'phone' => $data['company_phone'],
                            'country_id' => $data['country_id'],
                            'tax_number' => $data['tax_number'],
                            'address' => $data['address'],
                            'domain' => $data['final_domain'],
                            'currency_id' => $data['currency_id'],
                        ],
                        'admin' => [
                            'name' => $data['admin_name'],
                            'email' => $data['admin_email'],
                            'phone' => $data['admin_phone'],
                            'password' => $data['admin_password'],
                        ],
                        'plan' => [
                            'id' => $data['plan_id'] ?? 1,
                            'period' => $data['period'] ?? 'month',
                        ]
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
                dd($e->getMessage());
            }
        }

        return redirect()->route('payment-callback',['type'=>$type]);
    }

    function paymentCallbackPage(Request $request,$type) {
        return view('central.site.payment-callback',get_defined_vars());
    }
}
