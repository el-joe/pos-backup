<?php

namespace App\Http\Controllers\Central\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function callback(Request $request) {
        $data = $request->query('data');
        $data = decodedSlug($data);

        dd($data);
    }
}
