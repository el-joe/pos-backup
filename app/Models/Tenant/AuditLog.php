<?php

namespace App\Models\Tenant;

use App\Enums\AuditLogActionEnum;
use App\Notifications\GeneralNotification;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description',
    ];

    protected $casts = [
        'action' => AuditLogActionEnum::class,
        'description' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    static function log(AuditLogActionEnum $action, $descriptionParams = [])
    {
        $log = self::create([
            'admin_id' => admin()?->id,
            'action' => $action,
            'description' => [
                'key' => "audits.$action->value",
                'params' => $descriptionParams,
            ],
        ]);

        self::dispatchFallbackNotification($log);

        return $log;
    }

    protected static function dispatchFallbackNotification(self $log): void
    {
        if (self::usesDedicatedNotification($log->action)) {
            return;
        }

        $route = $log->description['params']['route']
            ?? $log->description['params']['url']
            ?? route('admin.notifications.list');

        $message = __($log->description['key'] ?? 'audits.' . $log->action->value, $log->description['params'] ?? []);

        superAdmins()->each(function (Admin $admin) use ($route, $message) {
            $admin->notify(new GeneralNotification('notifications.audit_event', [
                'route' => $route,
                'message' => $message,
            ]));
        });
    }

    protected static function usesDedicatedNotification(AuditLogActionEnum $action): bool
    {
        return in_array($action, [
            AuditLogActionEnum::CASH_REGISTER_OPENED,
            AuditLogActionEnum::CASH_REGISTER_CLOSED,
            AuditLogActionEnum::CASH_REGISTER_DEPOSIT,
            AuditLogActionEnum::CASH_REGISTER_WITHDRAWAL,
            AuditLogActionEnum::CREATE_SALE_ORDER_PAYMENT,
            AuditLogActionEnum::CREATE_PURCHASE_PAYMENT,
            AuditLogActionEnum::RETURN_SALE_ITEM,
            AuditLogActionEnum::RETURN_SALE_ORDER,
            AuditLogActionEnum::RETURN_PURCHASE_ORDER,
            AuditLogActionEnum::REFUND_PURCHASE_ITEM,
            AuditLogActionEnum::PAY_EXPENSE,
            AuditLogActionEnum::CREATE_STOCK_TRANSFER,
            AuditLogActionEnum::CREATE_STOCK_TAKING,
        ], true);
    }
}
