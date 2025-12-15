<?php

return [
    'new_stock_transfer' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-left-right text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">تم إنشاء تحويل مخزون جديد من الفرع :from_branch إلى الفرع :to_branch</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
    'new_stock_taking' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-box-seam text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">جرد مخزون جديد في الفرع :branch</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
    'new_cash_register' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-cash-stack text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">تم فتح سجل نقدي جديد في الفرع :branch بواسطة :user ، الرصيد الافتتاحي: :opening_balance</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
    'cash_register_closed' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-cash-stack text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">تم إغلاق السجل النقدي في الفرع :branch بواسطة :user ، الرصيد الختامي: :closing_balance</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

];
