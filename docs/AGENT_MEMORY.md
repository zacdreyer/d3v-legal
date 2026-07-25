# Agent Memory

- The plugin entry point is [d3v-legal.php](d3v-legal.php).
- Shortcode `[d3v-legal]` renders legal notices and returns a string so it is testable outside WordPress.
- Attributes: `notice`, `company`, `email`, `address`, `tel`, `smp`, `websiteurl`, `officer`, `regno`.
- PHP linting: `php -l d3v-legal.php`.
- Test suite: `composer test` or `vendor/bin/phpunit --configuration phpunit.xml.dist`.
- Targets South African legal notice content, including POPIA and PAIA.
- Renderer functions are wrapped in an include guard so the plugin can be loaded repeatedly.
- Future work: admin settings page, translation / i18n support, and a cookie-consent mechanism.
