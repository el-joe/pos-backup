<?php

namespace App\Models\Tenant;

use App\Enums\AuditLogActionEnum;
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
        self::create([
            'admin_id' => admin()?->id,
            'action' => $action,
            'description' => [
                'key' => "audits.$action->value",
                'params' => $descriptionParams,
            ],
        ]);
    }
}
