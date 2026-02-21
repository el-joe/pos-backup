<?php

$en = require __DIR__.'/../en/website.php';

return array_replace_recursive($en, [
    'blogs' => [
        'title' => 'المدونة',
        'subtitle' => 'آخر الأخبار والمقالات والنصائح.',
        'empty' => 'لا توجد مقالات حالياً.',
        'back_to_all' => 'العودة لكل المقالات',
    ],

    'nav' => [
        'blogs' => 'المدونة',
        'english' => 'English',
        'arabic' => 'العربية',
    ],
]);
