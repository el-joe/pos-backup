<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\AccountType;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    function accountTypeGroups() {
        $livewireView = 'tenant.account-type-groups-list';
        return view('admin.general',get_defined_vars());
    }

    function accountTypes() {
        $livewireView = 'tenant.account-type.account-types-list';
        return view('admin.general',get_defined_vars());
    }

    function accounts() {
        $livewireView = 'tenant.account.accounts-list';
        return view('admin.general',get_defined_vars());
    }

    function contacts($id) {
        $livewireView = 'tenant.contact.contacts-list';
        return view('admin.general',get_defined_vars());
    }

    function branches() {
        $livewireView = 'tenant.administration.branches-list';
        return view('admin.general',get_defined_vars());
    }

    function admins() {
        $livewireView = 'tenant.administration.admins-list';
        return view('admin.general',get_defined_vars());
    }


    function categories() {
        $livewireView = 'tenant.inventory.categories-list';
        return view('admin.general',get_defined_vars());
    }

    function brands() {
        $livewireView = 'tenant.inventory.brands-list';
        return view('admin.general',get_defined_vars());
    }

    function units() {
        $livewireView = 'tenant.inventory.units-list';
        return view('admin.general',get_defined_vars());
    }

    function products() {
        $livewireView = 'tenant.product.products-list';
        return view('admin.general',get_defined_vars());
    }

    function addEditProduct($id) {
        $livewireView = 'tenant.product.product-actions';
        return view('admin.general',get_defined_vars());
    }

    function purchases() {
        $livewireView = 'tenant.purchase.purchases-list';
        return view('admin.general',get_defined_vars());
    }

    function addEditPurchase($id) {
        $livewireView = 'tenant.purchase.purchase-actions';
        return view('admin.general',get_defined_vars());
    }

    function sales() {
        $livewireView = 'tenant.sale.sales-list';
        return view('admin.general',get_defined_vars());
    }

    function addEditSale($id) {
        $livewireView = 'tenant.sale.sale-actions';
        return view('admin.general',get_defined_vars());
    }

}
