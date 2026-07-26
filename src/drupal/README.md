# D3V Legal Notices — Drupal Adapter

A Drupal module adapter for the [D3V Legal Notices](https://github.com/zacdreyer/d3v-legal/) shared legal libraries.

## Requirements

- PHP `^8.1`
- Drupal `^10 || ^11`

## Installation

### Option 1: Copy into your Drupal site

1. Copy this directory (`src/drupal`) into your Drupal site as:

   ```
   web/modules/contrib/d3v_legal
   ```

2. Ensure the shared legal libraries are discoverable. In development this
   works automatically because the renderer looks two directories above the
   module root. For a distribution package, copy or symlink the
   `legal-libraries/` folder into the module root so the final structure is:

   ```
   web/modules/contrib/d3v_legal/
   ├── d3v_legal.info.yml
   ├── d3v_legal.module
   ├── legal-libraries/
   └── src/
   ```

### Option 2: Composer

Add the repository path to your project's `composer.json`, then require it:

```bash
composer config repositories.d3v-legal '{"type": "path", "url": "/path/to/d3v-legal/src/drupal"}'
composer require d3vdigital/drupal-d3v-legal
```

## Enable the module

Using Drush:

```bash
drush en d3v_legal
```

Or enable via the Drupal admin UI at **Extend** (`/admin/modules`).

## Place the block

1. Go to **Structure → Block layout** (`/admin/structure/block`).
2. Click **Place block** in the desired region.
3. Select **D3V Legal Notice**.
4. Configure the notice key, country, language, and business details.
5. Save the block.

## Configuration

The block configuration form exposes every field accepted by the shared
renderer:

- **Notice key** — e.g. `privacy`, `terms`, `returns`, `cookies`
- **Country** — ISO3 country code, e.g. `ZAF`
- **Language** — language code, e.g. `ENG`
- **Company name**, **Email address**, **Support email**, **Officer email**
- **Physical address**, **Telephone number**, **Social / messaging profile**
- **Website URL**, **Responsible officer**
- **Registration number**, **VAT number**
- **Return window (days)**
- **Policy URL**

## License

GPL-2.0-or-later
