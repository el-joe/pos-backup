<?php

namespace App\Models\Tenant;

use App\Notifications\GeneralNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasRoles,Notifiable,SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'type','active','branch_id','deleted_at'
    ];

    const TYPE = [
        'super_admin', 'admin'
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    function image()
    {
        return $this->morphOne(File::class, 'model')->where('key','image');
    }


    function branch() {
        return $this->belongsTo(Branch::class);
    }


    function getImagePathAttribute() {
        return $this->image?->full_path ?? asset('adminBoard/plugins/images/defaultUser.svg');
    }

    function scopeFilter($query,$filters = []) {
        return $query->when(isset($filters['type']), function($q) use ($filters) {
            $q->where('type',$filters['type']);
        })->when(isset($filters['active']), function($q) use ($filters) {
            $q->where('active',$filters['active']);
        })
        ->when(isset($filters['search']), function($q) use ($filters) {
            $q->whereAny(['name','phone','email'], 'like', '%'.$filters['search'].'%');
        })
        ->when(isset($filters['branch_id']), function($q) use ($filters) {
            if($filters['branch_id'] === 'all') {
                return $q;
            }
            $q->where('branch_id',$filters['branch_id']);
        });
    }


    function notifyNewStockTransfer($stockTransfer) {
        $this->notify(new GeneralNotification(
            'notifications.new_stock_transfer',
            [
                'route' => route('admin.stocks.transfers.details', $stockTransfer->id),
                'from_branch' => $stockTransfer->fromBranch?->name,
                'to_branch' => $stockTransfer->toBranch?->name,
            ]
        ));
    }

    function notifyNewStockTacking($stockTaking) {
        $this->notify(new GeneralNotification(
            'notifications.new_stock_taking',
            [
                'route' => route('admin.stocks.adjustments.details', $stockTaking->id),
                'branch' => $stockTaking->branch?->name,
            ]
        ));
    }

    function notifyCashRegisterOpened($cashRegister) {
        $this->notify(new GeneralNotification(
            'notifications.new_cash_register',
            [
                'route' => route('admin.reports.cash.register.report', $cashRegister->id),
                'branch' => $cashRegister->branch?->name,
                'opening_balance' => $cashRegister->opening_balance,
                'user' => $cashRegister->admin?->name,
            ]
        ));
    }

    function notifyCashRegisterClosed($cashRegister) {
        $this->notify(new GeneralNotification(
            'notifications.cash_register_closed',
            [
                'route' => route('admin.reports.cash.register.report', $cashRegister->id),
                'branch' => $cashRegister->branch?->name,
                'closing_balance' => $cashRegister->closing_balance,
                'user' => $cashRegister->admin?->name,
            ]
        ));
    }
}
