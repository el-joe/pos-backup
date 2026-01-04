<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Partner extends Model
{
    use SoftDeletes;

    protected $connection = 'central';

    // boot method to generate referral code on creating
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($partner) {
            $partner->referral_code = self::generateReferralCode();
        });
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country_id',
        'address',
        'referral_code',
        'commission_rate',
    ];

    public function commissions()
    {
        return $this->hasMany(PartnerCommission::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'partner_id', 'id');
    }

    function calculateCommission($amount)
    {
        return ($this->commission_rate / 100) * $amount;
    }

    static function generateReferralCode()
    {
        $code = Str::uuid();
        $partners = self::where('referral_code', $code)->first();
        if ($partners) {
            return self::generateReferralCode();
        }
        return $code;
    }
}
