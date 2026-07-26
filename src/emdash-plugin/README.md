# D3V Legal Notices — EmDash Plugin

Native plugin for EmDash CMS that renders jurisdiction-aware legal notices from the shared D3V Legal libraries.

## Features

- Renders notices at build time via the `<LegalNotice>` Astro component.
- Injects a lightweight CSS3 stylesheet into public pages via the `page:fragments` hook.
- Shares the same `{ISO3}-{LANG}-legals.json` libraries used by the WordPress and JavaScript adapters.

## Installation

Install the plugin from npm (or from the release ZIP):

```bash
npm install @d3vdigital/emdash-legal-notices
```

Register it in `astro.config.mjs`:

```ts
import { defineConfig } from "astro/config";
import emdash from "emdash/astro";
import { d3vLegalNoticesPlugin } from "@d3vdigital/emdash-legal-notices";

export default defineConfig({
  integrations: [
    emdash({
      plugins: [d3vLegalNoticesPlugin()],
    }),
  ],
});
```

## Usage in templates

```astro
---
import LegalNotice from "@d3vdigital/emdash-legal-notices/components/LegalNotice.astro";
---

<LegalNotice
  notice="privacy"
  company="Example Co"
  email="info@example.co.za"
  address="21 Random Street, Somewhere, South Africa"
  tel="+27 82 000 0000"
/>
```

## Supported props

| Prop | Purpose |
|------|---------|
| `notice` | Required. Notice type to render. |
| `country` | ISO3 country code (e.g. `ZAF`, `GBR`). |
| `language` | Three-letter language code (e.g. `eng`, `afr`). |
| `company` | Company or brand name. |
| `email` | General contact email. |
| `support_email` | Support email; falls back to `email`. |
| `officer_email` | Privacy/DPO email; falls back to `email`. |
| `address` | Physical or registered address. |
| `tel` | Telephone number. |
| `smp` | Social media platform name. |
| `websiteurl` | Website URL. |
| `officer` | Information Officer name. |
| `regno` | Company registration number. |
| `vatno` | VAT registration number. |
| `returnwindow` | Return window in days. |
| `policyurl` | URL to a full standalone policy page. |

## Styling

The plugin injects a default stylesheet automatically. You can override any CSS custom property or class in your own stylesheets.
