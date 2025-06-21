<?php

namespace App\Models\Tenant;

use App\Models\Tenant\AccountType;
use App\Models\Tenant\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_type_id','code','mode_type','model_id','extra_details','deleted_at'
    ];

    const RELATED_TO = [
        'customers' => Contact::class,
        'suppliers' => Contact::class
    ];

    function model(){
        return $this->morphTo();
    }

    function type() {
        return $this->belongsTo(AccountType::class,'account_type_id');
    }

    function getRelatedToAttribute() {
        switch ($this->model_type) {
            case Contact::class:
                return $this->model?->type;
            default:
                return "None";
        }
    }

    static function modelList($related_to_key) {
        switch ($related_to_key) {
            case 'customers':
                return self::RELATED_TO[$related_to_key]::whereType('customer')->get();
            case 'suppliers':
                return self::RELATED_TO[$related_to_key]::whereType('supplier')->get();
            default:
                return [];
        }
    }
}
