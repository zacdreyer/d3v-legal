# Test-Driven Development Notes

## 1. Goal
Provide a lightweight regression suite for the shortcode rendering behavior.

## 2. Current Test Coverage
The repository includes a PHPUnit test file at [src/wordpress-plugin/tests/TestPluginTest.php](src/wordpress-plugin/tests/TestPluginTest.php).

### Covered Cases
- Unknown notices return an empty string.
- Cookie notices render content that includes the expected copy and company name.
- Dynamic values are escaped in the output.
- Including the plugin twice does not produce a fatal error.
- Privacy notice renders POPIA-related text, the Information Regulator reference and the supplied email.
- PAIA manual renders the supplied Information Officer name and registration number.
- Returns/refunds notice renders the configured return window and CPA reference.
- E-commerce terms render the supplied VAT number and email.
- Shipping notice references the ECTA 30-day default.
- Payment notice references PCI-DSS.
- Support notice renders the supplied email and telephone number.
- Support notice uses `support_email` when supplied, otherwise falls back to `email`.
- Privacy notice uses `officer_email` when supplied, otherwise falls back to `email`.
- Accessibility notice renders equality/non-discrimination text.
- `policyurl` attribute renders a context-specific "Read our full ..." link in the cookie and privacy notices.
- A `policyurl` without a protocol is normalised to HTTPS in the rendered link.
- Query-string ampersands in `policyurl` are encoded for safe use in HTML attributes.
- Shortcode attributes take priority over backend defaults.
- Backend defaults are used when a shortcode attribute is missing or empty.
- The backend default return window is applied when it is not supplied in the shortcode.
- Default country is `ZAF` and default language is `ENG` when no `country`/`language` shortcode attributes and no backend defaults are set.
- Backend `default_country` and `default_language` settings are applied when the shortcode omits `country`/`language`.
- Shortcode `country` attribute overrides the backend `default_country`.
- `country='GBR'` renders United Kingdom library content referencing UK GDPR and the ICO.
- UK cookie notice references PECR and a cookie banner.
- `country` and `language` attributes are case-insensitive and match the exact ISO3/language codes from the `legal-libraries/` filenames.

## 3. How to Run Tests
1. Install dependencies with `composer install` (or `php composer.phar install` if Composer is not on PATH).
2. Run:
   `vendor/bin/phpunit --configuration phpunit.xml.dist`
   or, if Composer is on PATH:
   `composer test`
   Otherwise use the bundled phar:
   `php composer.phar test`

## 4. Suggested Next Tests
- Confirm copyright notice includes the current year.
- Confirm social media notices render the platform name.
- Confirm direct marketing opt-out text appears in the privacy notice when an email is supplied.
- Confirm each ecommerce notice contains the relevant legislation references.
- Confirm every notice type renders a policy link when `policyurl` is supplied.
- Confirm `policyurl` values are escaped consistently across all notices.
- Confirm backend values are sanitised before being saved (numeric return window, escaped strings).
- Confirm the settings page renders without fatal errors in a WordPress environment.

## 5. Suggested Additional Tests

- Confirm unsupported/invalid `country` values return an empty string.
- Confirm `ZAF-eng-legals.json` and `GBR-eng-legals.json` contain every supported notice slug.
- Confirm `d3v_legal_get_library_path()` rejects directory traversal attempts.
- Confirm placeholder fallback syntax (e.g. `{{company||We}}`) and nested fallback chains (e.g. `{{support_email||{{email}}}}`) render correctly when a field is empty.
- Confirm policy-link sections render in all notices when `policyurl` is supplied.
- Confirm backend `default_country` values outside the supported list are rejected on save.
