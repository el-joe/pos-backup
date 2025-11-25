<?php

return [
    'new_stock_transfer' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-left-right text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">New stock transfer from :from_branch to :to_branch</div>
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
            <div class="mb-1 text-inverse">New stock taking in :branch</div>
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
            <div class="mb-1 text-inverse">New cash register opened in :branch by :user , Opening Balance: :opening_balance</div>
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
            <div class="mb-1 text-inverse">Cash register closed in :branch by :user , Closing Balance: :closing_balance</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
];
