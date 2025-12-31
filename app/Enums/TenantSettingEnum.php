<?php

namespace App\Enums;

enum TenantSettingEnum : string
{
    case STRING = 'string';
    case TEXT = 'text';
    case NUMBER = 'number';
    case BOOLEAN = 'boolean';
    case JSON = 'json';
    case EMAIL = 'email';
    case PASSWORD = 'password';
    case SELECT = 'select';
    case FILE = 'file';
    case DATE = 'date';
    case URL = 'url';
    case ARRAY = 'array';
}
