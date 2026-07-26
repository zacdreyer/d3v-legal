# D3V Legal Notices — Magento 2 Adapter

A Magento 2 module adapter for the [D3V Legal Notices](https://github.com/zacdreyer/d3v-legal/) shared legal libraries.

## Requirements

- PHP `^8.1`
- Magento Framework `^103.0`

## Installation

### Option 1: Composer

```bash
composer require d3vdigital/magento-d3v-legal
```

The package uses the Magento `extra.map` directive to place the module at `app/code/D3vDigital/D3vLegal`.

### Option 2: Manual copy

Copy the module folder to your Magento installation:

```text
app/code/D3vDigital/D3vLegal
```

Then enable the module:

```bash
bin/magento module:enable D3vDigital_D3vLegal
bin/magento setup:upgrade
bin/magento cache:flush
```

## Usage

Insert the block into a CMS page or block content:

```text
{{block class="D3vDigital\D3vLegal\Block\LegalNotice" notice="privacy" company="Acme Inc."}}
```

All supported attributes can be supplied directly on the block:

```text
{{block
    class="D3vDigital\D3vLegal\Block\LegalNotice"
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
}}
```

## Available notices

The available notice keys depend on the selected country and language. Common notices include `privacy`, `terms`, `returns`, `shipping`, and `copyright`.

## Legal libraries

The module expects the shared `legal-libraries/` JSON files to be available at the package root (for composer installs) or at the repository root (for development). If you distribute the module independently, bundle the `legal-libraries/` folder alongside the module.
