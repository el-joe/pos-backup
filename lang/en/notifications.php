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
    'cash_register_deposit' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-plus-circle text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Cash deposit recorded in :branch by :user , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
    'cash_register_withdrawal' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-dash-circle text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Cash withdrawal recorded in :branch by :user , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
    'return_purchase_order' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-counterclockwise text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Purchase order #:order_id has been returned</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
    'return_sale_order' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-counterclockwise text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Sale order #:order_id has been returned</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'expense_paid' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-receipt-cutoff text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Expense paid in :branch (:category) , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'expense_refunded' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-counterclockwise text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Expense refunded in :branch (:category) , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'sale_payment_received' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-cash-coin text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Sale payment received in :branch for invoice #:invoice_number (:customer) , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'purchase_payment_made' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-credit-card text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Purchase payment made in :branch for ref #:ref_no (:supplier) , Amount: :amount</div>
            <div class="small text-inverse text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'customer_payment_received' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-person-check text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Customer payment received from :customer , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'supplier_payment_made' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-person-workspace text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Supplier payment made to :supplier , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'sale_item_refunded' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-counterclockwise text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Sale item refunded in :branch for invoice #:invoice_number , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',

    'purchase_item_refunded' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-counterclockwise text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">Purchase item refunded in :branch for ref #:ref_no , Amount: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
];
