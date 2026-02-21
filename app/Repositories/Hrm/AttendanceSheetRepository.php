<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\AttendanceSheet;
use App\Repositories\BaseRepository;

class AttendanceSheetRepository extends BaseRepository
{
    public function __construct(AttendanceSheet $sheet)
    {
        $this->setInstance($sheet);
    }
}
