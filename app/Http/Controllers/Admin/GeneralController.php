<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    function generateInvoice($type,$id){
        // type is one of 'sale' or 'purchase'
        if($type == 'sales'){
            // logic to generate sale invoice
            $order = Sale::find($id);
        }elseif($type == 'purchases'){
            // logic to generate purchase invoice
            $order = Purchase::find($id); // Assuming there's a Purchase model
        }else{
            return response()->json(['error' => 'Invalid type'], 400);
        }

        if(!$order){
            return abort(404, 'Order not found');
        }

        return view('admin.invoices.' . $type , get_defined_vars());
    }
}
