<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Sale;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    function show($token){
        $data = decodedData($token);
        $orderId = $data['order_id'] ?? null;
        $type = $data['type'] ?? 'a4'; // a4 , 80mm
        $action = $data['action'] ?? 'pdf';
        $lang = lang() == 'ar' ? '-ar' : '';

        $order = Sale::where('id', $orderId)->firstOrFail();

        return view("invoices.invoice-{$type}{$lang}", get_defined_vars());
    }
}
