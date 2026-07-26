# Agent Memory

- The repository is a multi-platform project. Shared legal content lives in the root `legal-libraries/` directory.
- Platform adapters live under `src/`:
  - WordPress adapter entry point: [`src/wordpress-plugin/d3v-legal.php`](src/wordpress-plugin/d3v-legal.php).
  - Standalone ES6 adapter: [`src/javascript-native/`](src/javascript-native).
  - EmDash CMS native plugin: [`src/emdash-plugin/`](src/emdash-plugin); it exposes a descriptor factory (`d3vLegalNoticesPlugin`) and a `<LegalNotice>` Astro component.
- The [`VERSION`](VERSION) file is the single source of truth. The release workflow syncs this version to every adapter and rebuilds all platform packages together.
- Shortcode `[d3v-legal]` renders legal notices and returns a string so it is testable outside WordPress.
- Attributes: `notice`, `country`, `language`, `company`, `email`, `support_email`, `officer_email`, `address`, `tel`, `smp`, `websiteurl`, `officer`, `regno`, `vatno`, `returnwindow`, `policyurl`.
- Backend settings page is available under **Settings > D3V Legal** and stores defaults in the `d3v_legal_settings` option.
- Attribute resolution order: shortcode attribute → backend setting → safe hardcoded fallback (e.g. `returnwindow` = 30).
- `policyurl` is implemented for every notice type and renders a context-specific "Read our full ..." link to a standalone policy page.
- PHP linting: `php -l src/wordpress-plugin/d3v-legal.php`.
- Test suite: `php composer.phar test` or `vendor/bin/phpunit --configuration phpunit.xml.dist`.
- Release archives are platform-specific ZIPs built by CI (e.g. `d3v-legal-<version>-wordpress.zip`, `d3v-legal-<version>-javascript-native.zip`, `d3v-legal-<version>-emdash-plugin.zip`). The `docs/` directory is excluded from release packages. Every ZIP includes a self-contained copy of `legal-libraries/`.
- Targets South African legal notice content aligned with POPIA, PAIA, ECTA, CPA, the Copyright Act, PEPUDA and the Constitution; United Kingdom content aligned with UK GDPR, the Data Protection Act 2018, PECR, the Consumer Rights Act 2015 and related statutes.
- Each JSON library notice includes a `legal_sources` array citing the relevant legislation sections.
- Renderer functions are wrapped in an include guard so the plugin can be loaded repeatedly.
- E-commerce notices added: `returns`, `support`, `shipping`, `payments`, `ecomtscs`, plus an `accessibility` notice.
- WordPress function fallbacks (`shortcode_atts`, `add_shortcode`, `sanitize_text_field`, `esc_html`, `esc_url`, `esc_attr`, `__`, `is_admin`, `get_option`, `add_action`, `trailingslashit`, `plugin_dir_path`) allow the plugin to be linted and unit-tested outside WordPress.
- Legal libraries are discovered dynamically from the root `legal-libraries/` directory using the `{ISO3}-{LANG}-legals.json` naming convention. The exact ISO3 and language codes from the filenames are used in the shortcode and backend settings, case-insensitively.
- Future work: admin notice support for legal-policy links, translation / i18n support, and a cookie-consent mechanism.

## Session history
- 2026-07-26 (continued): Separated contact email into `email`, `support_email` and `officer_email`; added backend fields and shortcode attributes; made support/privacy notices fall back to `email` when the role-specific email is empty; updated ZA and UK JSON libraries; added nested placeholder support to the renderer; added PHPUnit coverage; updated README, SDD, TDD and this memory file.
- 2026-07-27: Implemented ISO3 country codes and three-letter language codes in the library filename convention (`{ISO3}-{LANG}-legals.json`); made countries and languages dynamically discoverable from `legal-libraries/`; updated shortcode/backend/settings rendering to use the exact filename codes case-insensitively; defaulted to `ZAF`/`ENG`; renamed libraries to `ZAF-eng-legals.json` and `GBR-eng-legals.json`; updated tests, README, SDD, TDD and this memory file.
- 2026-07-28: Restructured all platform adapters under `src/` (`src/wordpress-plugin/`, `src/javascript-native/`, `src/emdash-plugin/`); created per-platform READMEs; added a root `VERSION` file as the single source of truth; updated the release workflow to sync the shared version to every adapter, commit the synced versions, build all platforms, and attach every platform ZIP to the same release; ensured each ZIP contains a self-contained `legal-libraries/` copy; updated README, SDD, TDD and this memory file.
- 2026-07-25 (continued): Implemented the `policyurl` shortcode attribute across all notice renderers; added tests and updated README/SDD/TDD.
- 2026-07-25: Added backend **Settings > D3V Legal** page with default company/contact/legal values; made all shortcode attributes optional with shortcode priority over backend defaults; added PHPUnit coverage for backend fallback logic; updated README, SDD, TDD and this memory file.
