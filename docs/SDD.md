# Software Design Document (SDD)

## 1. Purpose
This plugin provides reusable legal notice markup for WordPress sites. It is being refactored into a jurisdiction-agnostic module that supports multiple countries via selectable legal libraries. The first supported jurisdictions are South Africa (`ZAF`) and the United Kingdom (`GBR`).

## 2. Scope
The plugin exposes the `[d3v-legal]` shortcode and renders the following notices:
- Cookies
- Privacy policy
- PAIA manual
- Copyright (full and footer)
- Disclaimer
- Email disclaimer
- Terms and conditions
- Competition terms and conditions
- Data administration contact
- Social media release statement
- Social media netiquette
- Returns and refunds policy
- Customer support notice
- Shipping and delivery notice
- Payment and security notice
- E-commerce terms and conditions
- Accessibility notice

The supplied text is intended as a broad, company-agnostic starting point. It must be reviewed by a qualified legal professional before being relied on for regulatory compliance. Each renderer includes source comments citing the relevant South African legislation (POPIA, PAIA, ECTA, CPA, Copyright Act, etc.).

## 3. Architecture
- Repository layout:
  - Shared legal content lives in the root [`legal-libraries/`](../../legal-libraries) directory as `{ISO3}-{LANG}-legals.json` files.
  - The WordPress adapter lives in [`src/wordpress-plugin/`](../../src/wordpress-plugin).
  - The standalone ES6 adapter lives in [`src/javascript-native/`](../../src/javascript-native).
  - The EmDash CMS adapter lives in [`src/emdash-plugin/`](../../src/emdash-plugin).
  - The Laravel package adapter lives in [`src/laravel/`](../../src/laravel).
  - The Symfony bundle adapter lives in [`src/symfony/`](../../src/symfony).
  - The Drupal module adapter lives in [`src/drupal/`](../../src/drupal).
  - The Joomla content plugin adapter lives in [`src/joomla/`](../../src/joomla).
  - The Magento 2 module adapter lives in [`src/magento/`](../../src/magento).
  - The PrestaShop module adapter lives in [`src/prestashop/`](../../src/prestashop).
  - The shared PHP renderer used by the PHP adapters lives in [`src/php-shared/D3vLegalRenderer.php`](../../src/php-shared/D3vLegalRenderer.php).
  - Additional CMS/e-commerce adapters will be added as sibling folders under [`src/`](../../src) (e.g. `src/shopify/`, `src/django/`, `src/dotnet/`).
  - Shared documentation lives in [`docs/`](../../docs) and is **not** included in release archives.
  - The [`VERSION`](../../VERSION) file is the single source of truth for the version number shared by every adapter.
- Entry points:
  - WordPress: [`src/wordpress-plugin/d3v-legal.php`](../../src/wordpress-plugin/d3v-legal.php)
  - EmDash: [`src/emdash-plugin/src/index.ts`](../../src/emdash-plugin/src/index.ts) descriptor factory plus [`src/emdash-plugin/src/components/LegalNotice.astro`](../../src/emdash-plugin/src/components/LegalNotice.astro)
  - Laravel: service provider [`src/laravel/src/D3vLegalServiceProvider.php`](../../src/laravel/src/D3vLegalServiceProvider.php) and Blade component [`src/laravel/src/Blade/LegalNoticeComponent.php`](../../src/laravel/src/Blade/LegalNoticeComponent.php)
  - Symfony: bundle [`src/symfony/src/D3vLegalBundle.php`](../../src/symfony/src/D3vLegalBundle.php) and Twig extension [`src/symfony/src/Twig/LegalNoticeExtension.php`](../../src/symfony/src/Twig/LegalNoticeExtension.php)
  - Drupal: block plugin [`src/drupal/src/Plugin/Block/LegalNoticeBlock.php`](../../src/drupal/src/Plugin/Block/LegalNoticeBlock.php)
  - Joomla: content plugin [`src/joomla/d3vlegal.php`](../../src/joomla/d3vlegal.php)
  - Magento: block [`src/magento/app/code/D3vDigital/D3vLegal/Block/LegalNotice.php`](../../src/magento/app/code/D3vDigital/D3vLegal/Block/LegalNotice.php)
  - PrestaShop: module [`src/prestashop/d3vlegal.php`](../../src/prestashop/d3vlegal.php) and Smarty plugin [`src/prestashop/smarty/plugins/function.d3vlegal.php`](../../src/prestashop/smarty/plugins/function.d3vlegal.php)
- Shortcode handler: `d3v_legal_notices()`
- Settings API page: `d3v_legal_settings_page()` registered under **Settings > D3V Legal**
- Default lookup: `d3v_legal_get_backend_defaults()` reads the `d3v_legal_settings` option and returns an array of defaults
- Country library lookup: `d3v_legal_load_country_library($country, $language)` reads the `{ISO3}-{LANG}-legals.json` library for the selected country and language. During development the plugin resolves libraries from the centralized `legal-libraries/` directory; distribution packages are self-contained and include a copy of the libraries inside the platform folder.
- Versioning: one shared version in [`VERSION`](../../VERSION) is propagated to every adapter during the release workflow so all platform packages are always released together with the same version number.
- Generic renderer: `d3v_legal_render_notice()` renders a notice from its JSON library definition using pluggable section types (`paragraph`, `list`, `contact_list`, `joined_fields`, `policy_link`). The PHP adapters share [`src/php-shared/D3vLegalRenderer.php`](../../src/php-shared/D3vLegalRenderer.php), which implements the same rendering rules without framework dependencies.
- Rendering approach: each notice is built as an HTML string and returned so it can be tested outside the WordPress runtime.
- Attribute resolution: shortcode attributes take priority; missing or empty values fall back to the backend settings; hardcoded safe defaults apply only when no value is available (e.g. `returnwindow` defaults to `30`).

## 4. Shortcode attributes
| Attribute | Used in | Purpose |
|-----------|---------|---------|
| `notice` | all | Type of notice to render |
| `company` | most | Company or brand name |
| `email` | all | General contact email address; default for any notice-specific email |
| `support_email` | support, returns, shipping, ecomtscs, accessibility | Customer support email; falls back to `email` |
| `officer_email` | privacy, paia, contact, direct-marketing clauses | Information Officer / privacy / DPO email; falls back to `email` |
| `address` | privacy, tscs, comptscs, emaildisclaimer, paia, returns, support, ecomtscs | Physical/registered address |
| `tel` | privacy, comptscs, paia, support, ecomtscs | Telephone number |
| `smp` | smr, smn, comptscs | Social media platform name |
| `websiteurl` | emaildisclaimer | Website URL |
| `officer` | paia | Information Officer name |
| `regno` | paia | Company registration number |
| `vatno` | ecomtscs | VAT registration number |
| `returnwindow` | returns | Return window in days (default `30`) |
| `policyurl` | all notices | Optional URL to a full standalone policy page; renders a "Read our full ..." link when supplied |
| `country` | all | ISO3 country code selecting the legal library to use (`ZAF` or `GBR`); taken from the library filename; case-insensitive; defaults to the backend default or `ZAF` |
| `language` | all | Three-letter language code taken from the library filename (e.g. `eng`); case-insensitive; defaults to the backend setting, then to the first available language for the selected country, then `ENG` |

## 5. Design Notes
- Attributes are sanitized with `sanitize_text_field()` before being passed to renderers.
- Renderers escape dynamic values with `esc_html()` before output; URLs are passed through `esc_url()`.
- Fallback implementations of WordPress functions (`shortcode_atts()`, `add_shortcode()`, `sanitize_text_field()`, `esc_html()`, `esc_url()`, `esc_attr()`, `__()`, `is_admin()`, `get_option()`, `add_action()`, `trailingslashit()`, `plugin_dir_path()`) are provided so the plugin can be linted and unit tested outside WordPress.
- Renderer functions are wrapped in initialization guards so the plugin can be included more than once without fatal redeclaration errors.
- Each JSON library notice contains a `legal_sources` array citing the relevant legislation, making it easier to audit compliance claims. South African notices cite POPIA, PAIA, ECTA, the CPA and other local statutes; UK notices cite UK GDPR, the Data Protection Act 2018, the Consumer Rights Act 2015, PECR and other applicable laws.
- E-commerce notices align with each jurisdiction's consumer protection, supplier obligation and payment-data safeguard laws.
- The optional `policyurl` attribute is accepted by every notice and renders a context-specific link to a full standalone policy page.
- Backend defaults are stored in a single `d3v_legal_settings` option array and merged into shortcode attributes so users rarely need to repeat company details on every shortcode.
- Country-specific legal content is stored in JSON files (`{ISO3}-{LANG}-legals.json`, e.g. `ZAF-eng-legals.json`, `GBR-eng-legals.json`) in the root `legal-libraries/` directory, loaded by a generic renderer based on the `country` and `language` attributes or backend defaults. Direct HTTP access to the directory should be blocked by server rules; the plugin reads files only from the resolved library directory with strict path validation (`realpath()` checks) to prevent directory traversal.

## 6. Packaging and Release
- The GitHub Actions workflow in [`.github/workflows/release.yml`](../../.github/workflows/release.yml) reads the shared version from [`VERSION`](../../VERSION), syncs it to every adapter, lints every PHP file, runs the PHPUnit suite, builds the EmDash plugin, and builds one platform-specific ZIP file per supported platform.
- Each ZIP is assembled in a temporary `build/<platform>/` directory. The shared `legal-libraries/` directory is copied into the platform package so the distributed plugin is self-contained. The `docs/` directory is excluded from all release archives to keep packages lean and purpose-specific.
- Release archives follow the naming convention `d3v-legal-<version>-<platform>.zip`. Current platforms produce: `wordpress`, `javascript-native`, `emdash-plugin`, `laravel`, `symfony`, `drupal`, `joomla`, `magento`, and `prestashop`. All platform ZIPs are attached to the same GitHub release so users always get a matching set.
- Adding a new platform requires:
  1. Creating a platform folder under `src/` (e.g. `src/shopify/`).
  2. Copying [`src/php-shared/D3vLegalRenderer.php`](../../src/php-shared/D3vLegalRenderer.php) into the adapter for PHP-based platforms, or reusing the shared renderer path.
  3. Adding the platform’s build steps to the release workflow.
  4. Copying `legal-libraries/` and `LICENSE` into the platform’s staging directory before zipping.
  5. Updating the version-sync step to include the new adapter’s metadata files.
  6. Updating `README.md` and `docs/SDD.md` to list the new adapter.

## 7. Future Enhancements
- Add admin notice support for legal-policy links.
- Add more granular notice templates and full translation / i18n support.
- Implement a cookie-consent mechanism that can integrate with the cookie notice.
- Add further country legal libraries (e.g. `EU`, `US`, `AU`).
- Add adapters for platforms not yet covered (e.g. React component library, Angular library, Django package, ASP.NET Core middleware/component, Shopify app/theme extension, BigCommerce app, Wix Studio app, Flutter package, OpenCart extension) where a genuine plugin/add-on model exists.

## 8. Globalization Plan

### 8.1 Goal
Remove the ZA-only focus from the plugin name and code, then add a United Kingdom (`GBR`) legal library alongside the existing South Africa (`ZAF`) content. Users select a country with an ISO3 code matching the filename exactly; the plugin loads the relevant legal library. The default remains `ZAF` for backward compatibility. Content libraries are implemented as JSON files loaded and validated by the plugin. South Africa ships with both an English (`ZAF-eng-legals.json`) and an Afrikaans (`ZAF-afr-legals.json`) library.

### 8.2 Content Architecture
- Legal libraries live in the root `legal-libraries/` directory as `{ISO3}-{LANG}-legals.json` files (e.g. `ZAF-eng-legals.json`, `GBR-eng-legals.json`). They are shared across all platform adapters.
- Each file is a JSON object keyed by country code. Although JSON files are readable by design, the library directory is protected by a `.htaccess` rule (for Apache) and a `web.config` rule (for IIS) to block direct HTTP access. Additionally, [`src/wordpress-plugin/d3v-legal.php`](../../src/wordpress-plugin/d3v-legal.php) validates the file path, reads the files only via `file_get_contents()` from the resolved library directory, and JSON-decodes them safely.
- Each library contains:
  - `notices`: keyed by notice slug (`cookies`, `privacy`, `paia`, `copyright`, `copyrightfooter`, `disclaimer`, `emaildisclaimer`, `tscs`, `comptscs`, `contact`, `smr`, `smn`, `returns`, `support`, `shipping`, `payments`, `ecomtscs`, `accessibility`).
  - Each notice has `legal_sources` (array of legislation citations) and an ordered `sections` array.
  - Each section has:
    - `title` (optional): rendered as a `<strong>` heading.
    - `template`: text with `{{field}}` placeholders and optional fallback syntax `{{field||default}}`.
    - `condition` (optional): a field that must be non-empty for the section to render.
    - `type` (optional): e.g. `policy_link` to append a "Read our full ..." link using `policyurl`.
    - `legal_sources` (optional): section-level law references rendered as HTML comments.

### 7.3 Country Selection
- New shortcode attribute `country` accepts an ISO3 code exactly as it appears in the library filename (e.g. `ZAF`, `GBR`). The attribute is case-insensitive.
- New shortcode attribute `language` accepts a three-letter language code exactly as it appears in the library filename (e.g. `eng`). The attribute is case-insensitive.
- New backend settings `default_country` and `default_language` are added to **Settings > D3V Legal**; they default to `ZAF` and `ENG`.
- Resolution order for `country` is: shortcode attribute → backend `default_country` → `ZAF`.
- Resolution order for `language` is: shortcode attribute → backend `default_language` → first available language for the resolved country → `ENG`.
- The selected country and language are validated against files discovered in the resolved `legal-libraries/` directory; invalid selections return an empty string.

### 8.4 Renderer Refactor
- The per-notice PHP renderer functions have been replaced by a generic renderer:
  - `d3v_legal_get_library_path($country, $language)` resolves and validates the `{ISO3}-{LANG}-legals.json` file path within the plugin directory.
  - `d3v_legal_load_country_library($country, $language)` reads and JSON-decodes the relevant library.
  - `d3v_legal_render_notice($notice_key, $notice, $atts, $country, $language)` sanitises fields, applies section conditions and renders the notice sections from JSON.
- All dynamic values are escaped with `esc_html()` via `d3v_legal_escape()`; URLs use `esc_url()`.
- Placeholder parsing supports fallback values (e.g. `{{company||We}}`) and nested fallback chains (e.g. `{{support_email||{{email}}}}`) so a notice-specific email role can fall back to the general contact email.

### 8.5 UK Legal Content
The UK library will mirror the ZA notice types and will be researched against:
- UK GDPR (Regulation (EU) 2016/679 as retained in UK law)
- Data Protection Act 2018
- Privacy and Electronic Communications Regulations (PECR) 2003
- Consumer Rights Act 2015
- Consumer Contracts (Information, Cancellation and Additional Charges) Regulations 2013
- Electronic Commerce (EC Directive) Regulations 2002
- Companies Act 2006
- Copyright, Designs and Patents Act 1988
- Equality Act 2010

Each UK notice section will include `legal_sources` comments citing the relevant sections, matching the existing ZA approach.

### 8.6 Backward Compatibility
- Existing shortcodes without a `country` attribute continue to render South African English content.
- The plugin header name will change from `D3V Legal Notices ZA` to `D3V Legal Notices`.
- The text domain and shortcode tag remain unchanged.
- Any `{ISO3}-{LANG}-legals.json` file placed in `legal-libraries/` is automatically discovered and supported without code changes.
