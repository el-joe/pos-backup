<?php

return [
    'SA' => [
        'standard' => 'ZATCA',
        'vat_required' => true,
        'qr_required' => true,
        'xml_required' => false,
        'label' => 'Saudi Arabia - ZATCA Phase 2',
    ],
    'EG' => [
        'standard' => 'ETA',
        'vat_required' => true,
        'qr_required' => false,
        'xml_required' => true,
        'label' => 'Egypt - ETA E-Invoice',
    ],
    'AE' => [
        'standard' => 'UAE_VAT',
        'vat_required' => true,
        'qr_required' => false,
        'xml_required' => false,
        'label' => 'UAE - Federal Tax Authority',
    ],
    'KW' => [
        'standard' => 'KWT',
        'vat_required' => false,
        'qr_required' => false,
        'xml_required' => false,
        'label' => 'Kuwait',
    ],
    'BH' => [
        'standard' => 'BHR',
        'vat_required' => true,
        'qr_required' => false,
        'xml_required' => false,
        'label' => 'Bahrain',
    ],
    'OM' => [
        'standard' => 'OMN',
        'vat_required' => true,
        'qr_required' => false,
        'xml_required' => false,
        'label' => 'Oman',
    ],
    'JO' => [
        'standard' => 'JOR',
        'vat_required' => false,
        'qr_required' => false,
        'xml_required' => false,
        'label' => 'Jordan',
    ],

    'default' => [
        'standard' => 'generic',
        'vat_required' => false,
        'qr_required' => false,
        'xml_required' => false,
    ],
];
