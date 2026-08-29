<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Tenant\Sale;
use App\Services\EInvoice\EInvoiceService;
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

        $countryCode = strtoupper(Country::find(tenant('country_id'))?->code ?? 'default');

        return view("invoices.invoice-{$type}{$lang}", compact('order', 'countryCode'));
    }
}
