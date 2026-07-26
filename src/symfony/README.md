# D3V Legal Notices — Symfony Bundle

A Symfony bundle adapter for the [D3V Legal Notices](https://github.com/zacdreyer/d3v-legal/) shared legal libraries.

## Requirements

- PHP `^8.1`
- Symfony Framework Bundle `^6.0|^7.0`
- Symfony Twig Bundle `^6.0|^7.0`
- Twig `^3.0`

## Installation

```bash
composer require d3vdigital/symfony-d3v-legal
```

## Enable the bundle

If your project uses Symfony Flex, the bundle is enabled automatically. Otherwise, register it in `config/bundles.php`:

```php
return [
    // ...
    D3vDigital\D3vLegal\Symfony\D3vLegalBundle::class => ['all' => true],
];
```

## Usage

### Twig function

Render a legal notice in any Twig template:

```twig
{{ d3v_legal_notice('privacy', {
    country: 'ZAF',
    language: 'ENG',
    company: 'Acme Inc.',
    email: 'legal@example.com',
    support_email: 'support@example.com',
    officer_email: 'dpo@example.com',
    address: '1 Example Street, Cape Town, 8001',
    tel: '+27 21 123 4567',
    smp: 'WhatsApp: +27 60 000 0000',
    websiteurl: 'https://example.com',
    officer: 'Jane Doe',
    regno: '2010/123456/07',
    vatno: '4123456789',
    returnwindow: '30',
    policyurl: 'https://example.com/privacy-policy'
}) }}
```

Only the `notice` key is required. All other options fall back to sensible defaults: the renderer defaults to country `ZAF` and language `ENG`, and derives `support_email` and `officer_email` from `email` when omitted.

### Programmatic rendering

Inject or retrieve the shared renderer service:

```php
use D3vDigital\D3vLegal\D3vLegalRenderer;

class LegalController
{
    public function __construct(
        private D3vLegalRenderer $renderer,
    ) {
    }

    public function privacy(): Response
    {
        $html = $this->renderer->render('privacy', [
            'country'  => 'ZAF',
            'language' => 'ENG',
            'company'  => 'Acme Inc.',
            'email'    => 'legal@example.com',
        ]);

        return new Response($html);
    }
}
```

## License

GPL-2.0-or-later
