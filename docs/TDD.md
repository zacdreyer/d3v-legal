# Test-Driven Development Notes

## 1. Goal
Provide a lightweight regression suite for the shortcode rendering behavior.

## 2. Current Test Coverage
The repository includes a small PHPUnit test file at tests/test_plugin.php.

### Covered Cases
- Unknown notices return an empty string.
- Cookie notices render content that includes the expected copy.

## 3. How to Run Tests
1. Install PHPUnit.
2. Run:
   phpunit --configuration phpunit.xml.dist

## 4. Suggested Next Tests
- Confirm privacy notice output contains the company name.
- Confirm copyright notice includes the current year.
- Confirm social media notices render the platform name.
