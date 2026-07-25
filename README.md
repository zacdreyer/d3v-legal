# d3v-legal
WordPress plugin that outputs legal notices such as privacy policy, copyright, disclaimer, cookie notices, terms and conditions, and social media statements for South African and United Kingdom websites. Additional countries can be supported by adding a `{ISO3}-{LANG}-legals.json` file to the `legal-libraries/` directory.

> Note: This plugin is provided for informational purposes only and does not constitute legal advice. Please consult a qualified attorney or compliance professional before relying on it for legal or regulatory compliance.

## Installation
1. Copy the plugin folder to your WordPress plugins directory.
2. Activate the plugin from the WordPress admin area.
3. Go to **Settings > D3V Legal** in the WordPress admin and fill in your default business and contact details.
4. Add shortcode snippets to your content or templates. Shortcode attributes are optional; any value omitted from the shortcode will fall back to the value saved in Settings > D3V Legal.

### Local development
- Install PHP 8.1+.
- Install Composer.
- Run `composer install`.
- Run `php -l d3v-legal.php` to lint the plugin.
- Run `composer test` to execute the PHPUnit regression suite.

## Backend Settings
The plugin adds a **Settings > D3V Legal** page where you can store default values for country, company name, contact details, VAT number, Information Officer, default return window and policy URL. Once saved, shortcodes only need to supply the `notice` attribute (and any values you want to override per page).

## Shortcode Usage
```text
[d3v-legal notice='' country='' language='' company='' email='' support_email='' officer_email='' address='' tel='' smp='' websiteurl='' officer='' regno='' vatno='' returnwindow='' policyurl='']
```

All attributes are optional except `notice`. Values supplied in the shortcode take priority; missing or empty values fall back to the backend defaults configured under **Settings > D3V Legal**.

- `notice`: Type of notice to render.
- `country`: ISO3 country code taken from the library filename (e.g. `ZAF` or `GBR`). Case-insensitive. Defaults to the backend setting, or `ZAF` if none is configured.
- `language`: Three-letter language code taken from the library filename (e.g. `eng`). Case-insensitive. Defaults to the backend setting, or the first available language for the selected country, otherwise `ENG`.
- `company`: Company or brand name shown in the output.
- `email`: General contact email address used as the default for all notices.
- `support_email`: Customer support email address. Used in support, returns, shipping, e-commerce and accessibility notices. Falls back to `email` if not provided.
- `officer_email`: Information Officer / privacy / data protection email address. Used in privacy, PAIA and data-rights notices. Falls back to `email` if not provided.
- `address`: Physical address or registered office.
- `tel`: Contact number.
- `smp`: Social media platform name.
- `websiteurl`: Website URL for email disclaimer content.
- `officer`: Information Officer name, used in the PAIA manual.
- `regno`: Company registration number, used in the PAIA manual.
- `vatno`: VAT registration number, used in e-commerce terms.
- `returnwindow`: Number of days within which returns are accepted (default `30`), used in the returns/refunds policy.
- `policyurl`: Optional URL to a full standalone policy page. When supplied, a "Read our full ..." link is appended to the notice.

## Shortcode Examples

### Minimal usage (backend defaults)
If you have saved your details under **Settings > D3V Legal**:
```text
[d3v-legal notice='cookies']
[d3v-legal notice='privacy']
```

### Selecting a country and language
Render the UK cookie notice explicitly:
```text
[d3v-legal notice='cookies' country='GBR' company='ABC Holdings']
```

The language is case-insensitive and read directly from the filename:
```text
[d3v-legal notice='cookies' country='gbr' language='eng' company='ABC Holdings']
```

### Cookie Notice
```text
[d3v-legal notice='cookies' company='ABC Holdings']
```

### Privacy Policy
```text
[d3v-legal notice='privacy' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa' email='info@abc.com' tel='+27 82 000 0000']
```

### PAIA Manual
```text
[d3v-legal notice='paia' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa' email='info@abc.com' tel='+27 82 000 0000' officer='A. B. Officer' regno='2020/123456/07']
```

### Copyright Notice
```text
[d3v-legal notice='copyright' company='ABC Holdings']
```

### Footer Copyright Notice
```text
[d3v-legal notice='copyrightfooter' company='ABC Holdings']
```

### Disclaimer
```text
[d3v-legal notice='disclaimer' company='ABC Holdings']
```

### Email Disclaimer
```text
[d3v-legal notice='emaildisclaimer' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa' websiteurl='www.abcexample.com']
```

### Terms and Conditions
```text
[d3v-legal notice='tscs' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa']
```

### Competition Terms and Conditions
```text
[d3v-legal notice='comptscs' company='ABC Holdings' email='info@abc.com' address='21 Random Street, Somewhere, South Africa' tel='+27 82 000 0000' smp='Facebook']
```

### Contact
```text
[d3v-legal notice='contact' company='ABC Holdings' email='info@abc.com']
```

### Social Media Release Statement
```text
[d3v-legal notice='smr' company='ABC Holdings' smp='Facebook']
```

### Social Media Netiquette
```text
[d3v-legal notice='smn' company='ABC Holdings' smp='Facebook']
```

### Returns and Refunds Policy
```text
[d3v-legal notice='returns' company='ABC Holdings' email='info@abc.com' address='21 Random Street, Somewhere, South Africa' returnwindow='30']
```

### Customer Support Notice
```text
[d3v-legal notice='support' company='ABC Holdings' email='info@abc.com' tel='+27 82 000 0000' address='21 Random Street, Somewhere, South Africa']
```

### Shipping and Delivery Notice
```text
[d3v-legal notice='shipping' company='ABC Holdings' email='info@abc.com']
```

### Payment and Security Notice
```text
[d3v-legal notice='payments' company='ABC Holdings']
```

### E-commerce Terms and Conditions
```text
[d3v-legal notice='ecomtscs' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa' email='info@abc.com' tel='+27 82 000 0000' vatno='4567890123']
```

### Accessibility Notice
```text
[d3v-legal notice='accessibility' company='ABC Holdings' email='info@abc.com']
```

## Documentation
- [docs/SDD.md](docs/SDD.md)
- [docs/TDD.md](docs/TDD.md)
- [docs/agent-memory.md](docs/agent-memory.md)

## Release Process
The repository includes a GitHub Actions workflow in [.github/workflows/release.yml](.github/workflows/release.yml) that lints the plugin, runs the PHP test suite, and packages a release archive whenever a version tag is pushed.
