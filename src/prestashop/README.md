# D3V Legal Notices – PrestaShop Module

PrestaShop adapter for the shared D3V Legal Notices libraries.

## Requirements

- PrestaShop 1.7.0.0 or newer (tested up to 9.99.99)
- PHP 8.1 or newer

## Installation

1. Copy or upload the `prestashop` folder into your PrestaShop `modules/` directory
   and rename it to `d3vlegal`.

   Expected path: `modules/d3vlegal/d3vlegal.php`

2. In the PrestaShop Back Office, go to **Modules > Module Manager**.

3. Search for **D3V Legal Notices** and click **Install**.

   The module registers the `displayFooter` hook automatically.

## Usage

### Smarty function

The module ships a custom Smarty plugin so you can render a notice anywhere in a
PrestaShop template:

```smarty
{d3vlegal
  notice='privacy'
  company='Acme Inc.'
  email='legal@example.com'
  country='ZAF'
  language='ENG'
}
```

Available parameters match the fields accepted by `D3vLegalRenderer`:

- `notice` (required) – the notice key, e.g. `privacy`, `terms`, `returns`
- `country` – ISO 3166-1 alpha-3 country code, e.g. `ZAF`, `GBR`
- `language` – language code, e.g. `ENG`, `AFR`
- `company`, `email`, `support_email`, `officer_email`
- `address`, `tel`, `smp`, `websiteurl`
- `officer`, `regno`, `vatno`
- `returnwindow`, `policyurl`

### `displayFooter` hook

The module hooks into `displayFooter`. Because the hook payload does not include a
notice key by default, the hook method returns an empty string unless another module
or override passes a `notice` value into `$params`.

For direct rendering, use the `{d3vlegal ...}` Smarty function.

## Files

- `d3vlegal.php` – main module class
- `D3vLegalRenderer.php` – shared renderer (copied from `php-shared/`)
- `smarty/plugins/function.d3vlegal.php` – custom Smarty plugin
- `config.xml` – PrestaShop module metadata

## License

GPL-2.0-or-later
