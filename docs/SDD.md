# Software Design Document (SDD)

## 1. Purpose
This plugin provides reusable legal notice markup for WordPress sites, with a focus on South African compliance language and POPIA- and PAIA-related notices.

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
- Output functions: `cookies()`, `privacy_policy()`, `paia_manual()`, `copyright()`, `copyright_footer()`, `disclaimer()`, `email_disclaimer()`, `tscs()`, `comp_tscs()`, `contact()`, `social_media_release()`, `social_media_netiquette()`, `returns_refunds()`, `customer_support()`, `shipping_delivery()`, `payment_security()`, `ecommerce_tscs()`, `accessibility()`
- Rendering approach: output is buffered and returned as a string so it can be tested outside the WordPress runtime.
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

## 5. Design Notes
- Attributes are sanitized with `sanitize_text_field()` before being passed to renderers.
- Renderers escape dynamic values with `esc_html()` before output.
- Fallback implementations of WordPress functions (`shortcode_atts()`, `add_shortcode()`, `sanitize_text_field()`, `esc_html()`, `esc_url()`, `esc_attr()`, `__()`, `is_admin()`, `get_option()`, `add_action()`) are provided so the plugin can be linted and unit tested outside WordPress.
- Renderer functions are wrapped in an initialization guard so the plugin can be included more than once without fatal redeclaration errors.
- Each renderer function is preceded by a PHP docblock that cites the relevant sections of South African legislation, making it easier to audit compliance claims.
- E-commerce notices align with ECTA Chapter VII (consumer protection), CPA supplier obligations, and POPIA payment-data safeguards.
- The optional `policyurl` attribute is accepted by every notice renderer and renders a context-specific link to a full standalone policy page.
- Backend defaults are stored in a single `d3v_legal_settings` option array and merged into shortcode attributes so users rarely need to repeat company details on every shortcode.

## 6. Future Enhancements
- Add admin notice support for legal-policy links.
- Add more granular notice templates and full translation / i18n support.
- Implement a cookie-consent mechanism that can integrate with the cookie notice.
