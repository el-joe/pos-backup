<?php

namespace App\Models\Tenant;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'type','active'
    ];

    const TYPE = [
        'super_admin', 'admin'
    ];

    const PERMISSIONS = [
        "article-categories" => [
            'list', 'create', 'update', 'delete'
        ],
        "articles" => [
            'list', 'create', 'update', 'delete'
        ],
        "categories" => [
            'list', 'create', 'update', 'delete'
        ],
        "instructors" => [
            'list', 'create', 'update', 'delete'
        ],
        "courses" => [
            'list', 'create', 'update', 'delete'
        ],
        "course-reviews" => [
            'list', 'publish', 'delete', 'reply'
        ],
        "plans" => [
            'list', 'create', 'update', 'delete'
        ],
        "faqs" => [
            'list', 'create', 'update', 'delete'
        ],
        "orders" => [
            'list', 'print', 'pdf', 'refund'
        ],
        "offers" => [
            'list', 'create', 'update', 'delete'
        ],
        "articles-reviews" => [
            'list', 'publish', 'delete'
        ],
        "sales-report" => [
            "list"
        ],
        "organizations" => [
            "list", "accept", 'active'
        ],
        'organization-admins' => [
            'list','update', 'reset-password', 'active'
        ],
        "organization-subscriptions" => [
            "list", "print", 'pdf', 'refund'
        ],
        'customers' => [
            'list','update', 'reset-password', 'active'
        ],
        'admins' => [
            'list','update', 'create', 'delete','grant-permissions'
        ]
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
