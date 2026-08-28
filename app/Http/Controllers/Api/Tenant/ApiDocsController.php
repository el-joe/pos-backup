<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;

class ApiDocsController extends Controller
{
    public function index()
    {
        return view('api.docs');
    }
}
