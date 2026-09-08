<?php

return [
    'unbalanced_transaction' => 'Transaction is unbalanced (type: :type, reference: :reference) — debit :debit does not equal credit :credit.',
    'empty_lines' => 'Cannot save a transaction with no lines (type: :type, reference: :reference).',
    'invalid_line' => 'Invalid transaction line detected (type: :type, reference: :reference). Each line must have an account and a numeric amount.',
    'invalid_payment_account' => 'The selected account cannot be used to receive or make payments. Please choose a cash, bank, or check account.',
    'payment_account_required' => 'A payment account is required.',
];
