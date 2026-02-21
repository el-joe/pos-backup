<?php

return [
    'new_stock_transfer' => '<a onclick="markAsRead(event,\':id\')" data-href=":route" href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap fw-semibold">
        <div class="fs-20px">
            <i class="bi bi-arrow-left-right text-theme"></i>
        </div>
        <div class="flex-1 flex-wrap ps-3">
            <div class="mb-1 text-inverse">نقل مخزون جديد من :from_branch إلى :to_branch</div>
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
            <div class="mb-1 text-inverse">جرد مخزون جديد في :branch</div>
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
            <div class="mb-1 text-inverse">تم فتح صندوق نقدي جديد في :branch بواسطة :user ، الرصيد الافتتاحي: :opening_balance</div>
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
            <div class="mb-1 text-inverse">تم إغلاق الصندوق النقدي في :branch بواسطة :user ، الرصيد الختامي: :closing_balance</div>
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
            <div class="mb-1 text-inverse">تم تسجيل إيداع نقدي في :branch بواسطة :user ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم تسجيل سحب نقدي في :branch بواسطة :user ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم إرجاع أمر شراء رقم #:order_id</div>
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
            <div class="mb-1 text-inverse">تم إرجاع أمر بيع رقم #:order_id</div>
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
            <div class="mb-1 text-inverse">تم دفع مصروف في :branch (:category) ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم استرجاع مصروف في :branch (:category) ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم استلام دفعة بيع في :branch للفاتورة رقم #:invoice_number (:customer) ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم دفع دفعة شراء في :branch للمرجع رقم #:ref_no (:supplier) ، المبلغ: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
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
            <div class="mb-1 text-inverse">تم استلام دفعة من العميل :customer ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم دفع دفعة للمورد :supplier ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم إرجاع عنصر من البيع في :branch للفاتورة رقم #:invoice_number ، المبلغ: :amount</div>
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
            <div class="mb-1 text-inverse">تم إرجاع عنصر من الشراء في :branch للمرجع رقم #:ref_no ، المبلغ: :amount</div>
            <div class="small text-inverse text-opacity-50">:date</div>
        </div>
        <div class="ps-2 fs-16px">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>',
];
