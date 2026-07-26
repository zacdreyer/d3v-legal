# D3V Legal Notices — WordPress Plugin

WordPress adapter for D3V Legal Notices. Renders jurisdiction-aware legal notices from the shared JSON libraries using a simple shortcode and backend settings page.

## Installation

1. Download the latest `d3v-legal-<version>-wordpress.zip` release asset.
2. Extract the folder to your WordPress `wp-content/plugins/` directory.
3. Activate the plugin from the WordPress admin area.
4. Go to **Settings > D3V Legal** and fill in your default business and contact details.
5. Add shortcode snippets to your content or templates.

## Local development

- Install PHP 8.1+.
- Install Composer.
- Run `composer install`.
- Run `php -l src/wordpress-plugin/d3v-legal.php` to lint the plugin.
- Run `composer test` (or `vendor/bin/phpunit --configuration phpunit.xml.dist`) to execute the PHPUnit regression suite.

## Backend settings

The plugin adds a **Settings > D3V Legal** page where you can store defaults for country, company name, contact details, VAT number, Information Officer, default return window and policy URL. Once saved, shortcodes only need to supply the `notice` attribute (and any values you want to override per page).

## Shortcode usage

```text
[d3v-legal notice='' country='' language='' company='' email='' support_email='' officer_email='' address='' tel='' smp='' websiteurl='' officer='' regno='' vatno='' returnwindow='' policyurl='']
```

All attributes are optional except `notice`. Values supplied in the shortcode take priority; missing or empty values fall back to the backend defaults.

| Attribute | Purpose |
|-----------|---------|
| `notice` | Required. Notice type to render. |
| `country` | ISO3 country code (e.g. `ZAF`, `GBR`). Case-insensitive. |
| `language` | Three-letter language code (e.g. `eng`, `afr`). Case-insensitive. |
| `company` | Company or brand name. |
| `email` | General contact email. |
| `support_email` | Support email; falls back to `email`. |
| `officer_email` | Privacy/DPO email; falls back to `email`. |
| `address` | Physical or registered address. |
| `tel` | Telephone number. |
| `smp` | Social media platform name. |
| `websiteurl` | Website URL. |
| `officer` | Information Officer name. |
| `regno` | Company registration number. |
| `vatno` | VAT registration number. |
| `returnwindow` | Return window in days (default `30`). |
| `policyurl` | URL to a full standalone policy page. |

## Examples

Minimal usage with backend defaults:
```text
[d3v-legal notice='cookies']
[d3v-legal notice='privacy']
```

Explicit country and language:
```text
[d3v-legal notice='cookies' country='GBR' company='ABC Holdings']
[d3v-legal notice='cookies' country='ZAF' language='afr' company='ABC Holdings']
```
