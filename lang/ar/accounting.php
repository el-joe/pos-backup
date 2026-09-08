<?php

return [
    'unbalanced_transaction' => 'المعاملة غير متوازنة (النوع: :type، المرجع: :reference) — المدين :debit لا يساوي الدائن :credit.',
    'empty_lines' => 'لا يمكن حفظ معاملة بدون بنود (النوع: :type، المرجع: :reference).',
    'invalid_line' => 'تم اكتشاف بند معاملة غير صالح (النوع: :type، المرجع: :reference). يجب أن يحتوي كل بند على حساب ومبلغ رقمي.',
    'invalid_payment_account' => 'لا يمكن استخدام الحساب المحدد لاستلام أو سداد المدفوعات. الرجاء اختيار حساب نقدية أو بنك أو شيكات.',
    'payment_account_required' => 'حساب الدفع مطلوب.',
];
