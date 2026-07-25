# Agent Memory

- The plugin entry point is [d3v-legal.php](d3v-legal.php).
- Shortcode `[d3v-legal]` renders legal notices and returns a string so it is testable outside WordPress.
- Attributes: `notice`, `company`, `email`, `address`, `tel`, `smp`, `websiteurl`, `officer`, `regno`, `vatno`, `returnwindow`, `policyurl`.
- Backend settings page is available under **Settings > D3V Legal** and stores defaults in the `d3v_legal_settings` option.
- Attribute resolution order: shortcode attribute → backend setting → safe hardcoded fallback (e.g. `returnwindow` = 30).
- `policyurl` is implemented for every notice type and renders a context-specific "Read our full ..." link to a standalone policy page.
- PHP linting: `php -l d3v-legal.php`.
- Test suite: `php composer.phar test` or `vendor/bin/phpunit --configuration phpunit.xml.dist`.
- Targets South African legal notice content aligned with POPIA, PAIA, ECTA, CPA, the Copyright Act, PEPUDA and the Constitution.
- Each renderer function has a docblock citing the relevant legislation sections.
- Renderer functions are wrapped in an include guard so the plugin can be loaded repeatedly.
- E-commerce notices added: `returns`, `support`, `shipping`, `payments`, `ecomtscs`, plus an `accessibility` notice.
- WordPress function fallbacks (`shortcode_atts`, `add_shortcode`, `sanitize_text_field`, `esc_html`, `esc_url`, `esc_attr`, `__`, `is_admin`, `get_option`, `add_action`) allow the plugin to be linted and unit-tested outside WordPress.
- Future work: admin notice support for legal-policy links, translation / i18n support, and a cookie-consent mechanism.

## Session history
- 2026-07-25 (continued): Implemented the `policyurl` shortcode attribute across all notice renderers; added tests and updated README/SDD/TDD.
- 2026-07-25: Added backend **Settings > D3V Legal** page with default company/contact/legal values; made all shortcode attributes optional with shortcode priority over backend defaults; added PHPUnit coverage for backend fallback logic; updated README, SDD, TDD and this memory file.
