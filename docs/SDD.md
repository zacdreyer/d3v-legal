# Software Design Document (SDD)

## 1. Purpose
This plugin provides reusable legal notice markup for WordPress sites. It is being refactored into a jurisdiction-agnostic module that supports multiple countries via selectable legal libraries. The first supported jurisdictions are South Africa (`ZA`) and the United Kingdom (`UK`).

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
- Entry point: [d3v-legal.php](d3v-legal.php)
- Shortcode handler: `d3v_legal_notices()`
- Settings API page: `d3v_legal_settings_page()` registered under **Settings > D3V Legal**
- Default lookup: `d3v_legal_get_backend_defaults()` reads the `d3v_legal_settings` option and returns an array of defaults
- Country library lookup: `d3v_legal_load_country_library($country)` reads the JSON library for the selected country, restricted to files inside the plugin directory
- Generic renderer: `d3v_legal_render_notice()` renders a notice from its JSON library definition using pluggable section types (`paragraph`, `list`, `contact_list`, `joined_fields`, `policy_link`)
- Rendering approach: each notice is built as an HTML string and returned so it can be tested outside the WordPress runtime.
- Attribute resolution: shortcode attributes take priority; missing or empty values fall back to the backend settings; hardcoded safe defaults apply only when no value is available (e.g. `returnwindow` defaults to `30`).

## 4. Shortcode attributes
| Attribute | Used in | Purpose |
|-----------|---------|---------|
| `notice` | all | Type of notice to render |
| `company` | most | Company or brand name |
| `email` | privacy, contact, comptscs, paia, returns, support, shipping, ecomtscs, accessibility | Contact email |
| `address` | privacy, tscs, comptscs, emaildisclaimer, paia, returns, support, ecomtscs | Physical/registered address |
| `tel` | privacy, comptscs, paia, support, ecomtscs | Telephone number |
| `smp` | smr, smn, comptscs | Social media platform name |
| `websiteurl` | emaildisclaimer | Website URL |
| `officer` | paia | Information Officer name |
| `regno` | paia | Company registration number |
| `vatno` | ecomtscs | VAT registration number |
| `returnwindow` | returns | Return window in days (default `30`) |
| `policyurl` | all notices | Optional URL to a full standalone policy page; renders a "Read our full ..." link when supplied |
| `country` | all | ISO2 country code selecting the legal library to use (`ZA` or `UK`); defaults to the backend default or `ZA` |

## 5. Design Notes
- Attributes are sanitized with `sanitize_text_field()` before being passed to renderers.
- Renderers escape dynamic values with `esc_html()` before output; URLs are passed through `esc_url()`.
- Fallback implementations of WordPress functions (`shortcode_atts()`, `add_shortcode()`, `sanitize_text_field()`, `esc_html()`, `esc_url()`, `esc_attr()`, `__()`, `is_admin()`, `get_option()`, `add_action()`, `trailingslashit()`, `plugin_dir_path()`) are provided so the plugin can be linted and unit tested outside WordPress.
- Renderer functions are wrapped in initialization guards so the plugin can be included more than once without fatal redeclaration errors.
- Each JSON library notice contains a `legal_sources` array citing the relevant legislation, making it easier to audit compliance claims. South African notices cite POPIA, PAIA, ECTA, the CPA and other local statutes; UK notices cite UK GDPR, the Data Protection Act 2018, the Consumer Rights Act 2015, PECR and other applicable laws.
- E-commerce notices align with each jurisdiction's consumer protection, supplier obligation and payment-data safeguard laws.
- The optional `policyurl` attribute is accepted by every notice and renders a context-specific link to a full standalone policy page.
- Backend defaults are stored in a single `d3v_legal_settings` option array and merged into shortcode attributes so users rarely need to repeat company details on every shortcode.
- Country-specific legal content is stored in JSON files (`ZA-legals.json`, `UK-legals.json`) loaded by a generic renderer based on the `country` attribute or backend default. Direct HTTP access to the directory should be blocked by server rules; the plugin reads files only from its own directory with strict path validation (`realpath()` checks) to prevent directory traversal.

## 6. Future Enhancements
- Add admin notice support for legal-policy links.
- Add more granular notice templates and full translation / i18n support.
- Implement a cookie-consent mechanism that can integrate with the cookie notice.
- Add further country legal libraries (e.g. `EU`, `US`, `AU`).

## 7. Globalization Plan

### 7.1 Goal
Remove the ZA-only focus from the plugin name and code, then add a United Kingdom (`UK`) legal library alongside the existing South Africa (`ZA`) content. Users select a country with an ISO2 code; the plugin loads the relevant legal library. The default remains `ZA` for backward compatibility. Content libraries are implemented as JSON files loaded and validated by the plugin.

### 7.2 Content Architecture
- Legal libraries live at the repository root as `ZA-legals.json` and `UK-legals.json`.
- Each file is a JSON object keyed by country code. Although JSON files are readable by design, the library directory is protected by a `.htaccess` rule (for Apache) and a `web.config` rule (for IIS) to block direct HTTP access. Additionally, [d3v-legal.php](d3v-legal.php) validates the file path, reads the files only via `file_get_contents()` from the plugin directory, and JSON-decodes them safely.
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
- New shortcode attribute `country` accepts an ISO2 code (`ZA`, `UK`).
- New backend setting `default_country` is added to **Settings > D3V Legal**; it defaults to `ZA`.
- Resolution order for `country` is: shortcode attribute → backend `default_country` → `ZA`.
- The selected country is validated against available JSON library keys; invalid selections return an empty string.

### 7.4 Renderer Refactor
- The per-notice PHP renderer functions have been replaced by a generic renderer:
  - `d3v_legal_get_library_path($country)` resolves and validates the JSON file path within the plugin directory.
  - `d3v_legal_load_country_library($country)` reads and JSON-decodes the relevant library.
  - `d3v_legal_render_notice($notice_key, $notice, $atts, $country)` sanitises fields, applies section conditions and renders the notice sections from JSON.
- All dynamic values are escaped with `esc_html()` via `d3v_legal_escape()`; URLs use `esc_url()`.
- Placeholder parsing supports fallback values so generic text (e.g. "We") is used when a company name is not supplied.

### 7.5 UK Legal Content
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

### 7.6 Backward Compatibility
- Existing shortcodes without a `country` attribute continue to render South African content.
- The plugin header name will change from `D3V Legal Notices ZA` to `D3V Legal Notices`.
- The text domain and shortcode tag remain unchanged.
