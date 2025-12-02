<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use App\Mail\AdminRegisterRequestMail;
use App\Mail\RegisterRequestMail;
use App\Models\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    function callback(Request $request,$type) {
        $data = $request->query('data');
        $data = decodedSlug($data);

        if($type == 'success'){
            try{
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
                        ],
                        'admin' => [
                            'name' => $data['admin_name'],
                            'email' => $data['admin_email'],
                            'phone' => $data['admin_phone'],
                            'password' => $data['admin_password'],
                        ]
                    ],
                    'status'=>'pending'
                ]);

                Mail::to($data['company_email'])->send(new RegisterRequestMail([
                    'name' => $data['company_name'],
                ]));

                Mail::to(env('ADMIN_EMAIL'))->send(new AdminRegisterRequestMail(
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
