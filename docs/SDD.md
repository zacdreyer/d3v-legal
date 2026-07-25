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

The supplied text is intended as a broad, company-agnostic starting point. It must be reviewed by a qualified legal professional before being relied on for regulatory compliance.

## 3. Architecture
- Entry point: [d3v-legal.php](d3v-legal.php)
- Shortcode handler: `d3v_legal_notices()`
- Output functions: `cookies()`, `privacy_policy()`, `paia_manual()`, `copyright()`, `copyright_footer()`, `disclaimer()`, `email_disclaimer()`, `tscs()`, `comp_tscs()`, `contact()`, `social_media_release()`, `social_media_netiquette()`
- Rendering approach: output is buffered and returned as a string so it can be tested outside the WordPress runtime.

## 4. Shortcode attributes
| Attribute | Used in | Purpose |
|-----------|---------|---------|
| `notice` | all | Type of notice to render |
| `company` | most | Company or brand name |
| `email` | privacy, contact, comptscs, paia | Contact email |
| `address` | privacy, tscs, comptscs, emaildisclaimer, paia | Physical/registered address |
| `tel` | privacy, comptscs, paia | Telephone number |
| `smp` | smr, smn, comptscs | Social media platform name |
| `websiteurl` | emaildisclaimer | Website URL |
| `officer` | paia | Information Officer name |
| `regno` | paia | Company registration number |

## 5. Design Notes
- Attributes are sanitized with `sanitize_text_field()` before being passed to renderers.
- Renderers escape dynamic values with `esc_html()` before output.
- Fallback implementations of `shortcode_atts()`, `add_shortcode()`, `sanitize_text_field()` and `esc_html()` are provided so the plugin can be linted and unit tested outside WordPress.
- Renderer functions are wrapped in an initialization guard so the plugin can be included more than once without fatal redeclaration errors.

## 6. Future Enhancements
- Add a WordPress admin settings page for default company details.
- Add admin notice support for legal-policy links.
- Add more granular notice templates and full translation / i18n support.
