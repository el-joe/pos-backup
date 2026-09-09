<?php

// Controls how large a closing-balance discrepancy CashRegisterPage::closeRegister() is
// allowed to post to the ledger unattended. A variance above this share of the register's
// opening_balance is very unlikely to be a genuine counting error at the drawer and is
// blocked from the UI — the operator is told to contact an administrator instead of
// silently booking it as a Cash Over/Short expense. See prompt 21.
return [
    'max_variance_pct' => (float) env('CASH_REGISTER_MAX_VARIANCE_PCT', 10),
];
