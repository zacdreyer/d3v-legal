# Agent Memory

- The plugin entry point is [d3v-legal.php](d3v-legal.php).
- Shortcode `[d3v-legal]` renders legal notices and returns a string so it is testable outside WordPress.
- Attributes: `notice`, `country`, `company`, `email`, `address`, `tel`, `smp`, `websiteurl`, `officer`, `regno`, `vatno`, `returnwindow`, `policyurl`.
- Backend settings page is available under **Settings > D3V Legal** and stores defaults in the `d3v_legal_settings` option.
- Attribute resolution order: shortcode attribute → backend setting → safe hardcoded fallback (e.g. `returnwindow` = 30, default country = `ZA`).
- `policyurl` is implemented for every notice type and renders a context-specific "Read our full ..." link to a standalone policy page.
- PHP linting: `php -l d3v-legal.php`.
- Test suite: `php composer.phar test` or `vendor/bin/phpunit --configuration phpunit.xml.dist`.
- Supports South Africa (`ZA`) and the United Kingdom (`UK`) legal libraries loaded from JSON files at the plugin root.
- Country selection: shortcode `country` attribute → backend `default_country` → `ZA`.
- Legal sources for each notice are stored in the JSON library under `legal_sources`.
- Generic renderer replaces the previous per-notice PHP functions; sections support `paragraph`, `list`, `contact_list`, `joined_fields` and `policy_link` types with `{{field}}` placeholders and `{{field||fallback}}` fallback syntax.
- JSON files are read only from the plugin directory with strict path validation (`realpath()` checks) to prevent directory traversal.
- WordPress function fallbacks (`shortcode_atts`, `add_shortcode`, `sanitize_text_field`, `esc_html`, `esc_url`, `esc_attr`, `__`, `is_admin`, `get_option`, `add_action`, `trailingslashit`, `plugin_dir_path`) allow the plugin to be linted and unit-tested outside WordPress.
- Future work: admin notice support for legal-policy links, translation / i18n support, and a cookie-consent mechanism.

## Session history
- 2026-07-25 (continued): Implemented the `policyurl` shortcode attribute across all notice renderers; added tests and updated README/SDD/TDD.
- 2026-07-25: Added backend **Settings > D3V Legal** page with default company/contact/legal values; made all shortcode attributes optional with shortcode priority over backend defaults; added PHPUnit coverage for backend fallback logic; updated README, SDD, TDD and this memory file.
- 2026-07-26: Completed globalization refactor. Extracted South African content into `ZA-legals.json`, added `UK-legals.json`, replaced per-notice PHP renderers with a generic JSON renderer, added `country` shortcode attribute and backend `default_country` selector, renamed plugin header to "D3V Legal Notices", updated tests and documentation.
