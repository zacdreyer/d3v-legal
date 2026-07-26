<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Jurisdiction
    |--------------------------------------------------------------------------
    |
    | The default country (ISO3) and language code to use when a Blade
    | component or programmatic render call does not specify them.
    |
    */

    'country'  => 'ZAF',
    'language' => 'ENG',

    /*
    |--------------------------------------------------------------------------
    | Business Defaults
    |--------------------------------------------------------------------------
    |
    | Optional default business details that are merged into every notice
    | render. Values may be overridden per-component via Blade attributes.
    |
    */

    'business' => [
        'company'       => null,
        'email'         => null,
        'support_email' => null,
        'officer_email' => null,
        'address'       => null,
        'tel'           => null,
        'smp'           => null,
        'websiteurl'    => null,
        'officer'       => null,
        'regno'         => null,
        'vatno'         => null,
        'returnwindow'  => null,
        'policyurl'     => null,
    ],
];
