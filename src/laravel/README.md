# D3V Legal Notices — Laravel Adapter

A Laravel package adapter for the [D3V Legal Notices](https://github.com/zacdreyer/d3v-legal/) shared legal libraries.

## Requirements

- PHP `^8.1`
- Laravel / Illuminate `^10.0|^11.0`

## Installation

```bash
composer require d3vdigital/laravel-d3v-legal
```

The package uses Laravel auto-discovery. If you have disabled auto-discovery, register the service provider manually in `config/app.php`:

```php
'providers' => [
    // ...
    D3vDigital\D3vLegal\Laravel\D3vLegalServiceProvider::class,
],
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=d3v-legal-config
```

Edit `config/d3v-legal.php` to set your default country, language, and business details.

```php
return [
    'country'  => 'ZAF',
    'language' => 'ENG',
    'business' => [
        'company'       => 'Acme Inc.',
        'email'         => 'legal@example.com',
        'support_email' => 'support@example.com',
        'officer_email' => 'dpo@example.com',
        'address'       => '1 Example Street, Cape Town, 8001',
        'tel'           => '+27 21 123 4567',
        'smp'           => 'WhatsApp: +27 60 000 0000',
        'websiteurl'    => 'https://example.com',
        'officer'       => 'Jane Doe',
        'regno'         => '2010/123456/07',
        'vatno'         => '4123456789',
        'returnwindow'  => '30',
        'policyurl'     => 'https://example.com/privacy-policy',
    ],
];
```

## Usage

### Blade component

```blade
<x-d3v-legal-notice notice="privacy" />
```

All supported attributes can be supplied directly on the component to override the config defaults:

```blade
<x-d3v-legal-notice
    notice="privacy"
    country="ZAF"
    language="ENG"
    company="Acme Inc."
    email="legal@example.com"
    support_email="support@example.com"
    officer_email="dpo@example.com"
    address="1 Example Street, Cape Town, 8001"
    tel="+27 21 123 4567"
    smp="WhatsApp: +27 60 000 0000"
    websiteurl="https://example.com"
    officer="Jane Doe"
    regno="2010/123456/07"
    vatno="4123456789"
    returnwindow="30"
    policyurl="https://example.com/privacy-policy"
/>
```

### Programmatic rendering

Resolve the shared renderer from the service container:

```php
use D3vDigital\D3vLegal\D3vLegalRenderer;

$renderer = app(D3vLegalRenderer::class);

$html = $renderer->render('privacy', [
    'country'  => 'ZAF',
    'language' => 'ENG',
    'company'  => 'Acme Inc.',
    'email'    => 'legal@example.com',
]);
```

## License

GPL-2.0-or-later
