# Test-Driven Development Notes

## 1. Goal
Provide a lightweight regression suite for the shortcode rendering behavior.

## 2. Current Test Coverage
The repository includes a PHPUnit test file at [tests/TestPluginTest.php](tests/TestPluginTest.php).

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
- Accessibility notice renders equality/non-discrimination text.
- `policyurl` attribute renders a context-specific "Read our full ..." link in the cookie and privacy notices.
- A `policyurl` without a protocol is normalised to HTTPS in the rendered link.
- Query-string ampersands in `policyurl` are encoded for safe use in HTML attributes.
- Shortcode attributes take priority over backend defaults.
- Backend defaults are used when a shortcode attribute is missing or empty.
- The backend default return window is applied when it is not supplied in the shortcode.

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
