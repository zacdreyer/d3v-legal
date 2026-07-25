# Test-Driven Development Notes

## 1. Goal
Provide a lightweight regression suite for the shortcode rendering behavior.

## 2. Current Test Coverage
The repository includes a PHPUnit test file at [tests/TestPluginTest.php](tests/TestPluginTest.php).

### Covered Cases
- Unknown notices return an empty string.
- Cookie notices render content that includes the expected copy.
- Dynamic values are escaped in the output.
- Including the plugin twice does not produce a fatal error.

## 3. How to Run Tests
1. Install dependencies with `composer install`.
2. Run:
   `vendor/bin/phpunit --configuration phpunit.xml.dist`
   or, if Composer is on PATH:
   `composer test`

## 4. Suggested Next Tests
- Confirm privacy notice output contains the company name.
- Confirm copyright notice includes the current year.
- Confirm social media notices render the platform name.
- Confirm the PAIA manual renders the supplied Information Officer name and registration number.
- Confirm the privacy notice includes POPIA and PAIA references.
