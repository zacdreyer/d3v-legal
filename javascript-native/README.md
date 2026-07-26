# D3V Legal Notices — Native ES6

A framework-agnostic, standalone JavaScript adapter for D3V Legal Notices. It renders legal notices into HTML5 using shared JSON libraries from the repository root and styles them with CSS3.

## Files

- `d3v-legal.js` — Main ES6 module. Fetches shared legal libraries and renders notices into `[data-d3v-legal]` elements.
- `d3v-legal.css` — Optional CSS3 stylesheet.
- `config.js` — Default configuration values (country, language, business/contact details).
- `index.html` — Minimal demo page.

## Usage

Include the stylesheet and the module on any static page or framework template:

```html
<link rel="stylesheet" href="path/to/d3v-legal.css">
<script type="module" src="path/to/d3v-legal.js"></script>

<article
  data-d3v-legal
  data-notice="privacy"
  data-company="Example Co"
  data-email="info@example.co.za"
></article>
```

## Configuration

Set defaults in `config.js`:

```js
export const config = {
  country: 'ZAF',
  language: 'ENG',
  company: '',
  email: '',
  // ... other fields
};
```

Any `data-*` attribute on a notice element overrides the corresponding default.

## Supported attributes

| Attribute | Purpose |
|-----------|---------|
| `data-notice` | Required. Notice type to render. |
| `data-country` | ISO3 country code (e.g. `ZAF`, `GBR`). |
| `data-language` | Three-letter language code (e.g. `eng`, `afr`). |
| `data-company` | Company or brand name. |
| `data-email` | General contact email. |
| `data-support_email` | Support email; falls back to `data-email`. |
| `data-officer_email` | Privacy/DPO email; falls back to `data-email`. |
| `data-address` | Physical or registered address. |
| `data-tel` | Telephone number. |
| `data-smp` | Social media platform name. |
| `data-websiteurl` | Website URL. |
| `data-officer` | Information Officer name. |
| `data-regno` | Company registration number. |
| `data-vatno` | VAT registration number. |
| `data-returnwindow` | Return window in days. |
| `data-policyurl` | URL to a full standalone policy page. |

## Programmatic API

```js
import { renderInto } from './d3v-legal.js';

const element = document.getElementById('notice-target');
await renderInto(element);
```

## Packaging

When this folder is packaged by the release workflow, the shared `legal-libraries/` directory is copied into the distribution so the adapter remains self-contained.
