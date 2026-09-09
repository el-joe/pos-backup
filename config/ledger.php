<?php

// Controls how LedgerBridgeService::post() behaves when it cannot map a
// transaction to a balanced journal entry (see docs/ledger-architecture-decision.md).
//
// true (default, production): a mapping failure throws LedgerBridgeException and
// rolls back the caller's transaction. Used once chart_of_accounts is confirmed seeded
// and every accounts.type in use resolves through account_coa_map.
//
// false: a mapping failure is caught, logged at critical, and post() returns null —
// the sale/purchase/payment still commits without a journal entry. This exists so a
// future unmapped account type cannot take a tenant offline; it is a seatbelt, not
// a replacement for keeping chart_of_accounts seeded and account_coa_map complete.
return [
    'bridge_strict' => env('LEDGER_BRIDGE_STRICT', true),
];
