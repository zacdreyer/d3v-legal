# D3V Legal Notices — Joomla Content Plugin

Content plugin for Joomla that renders jurisdiction-aware legal notices from the shared D3V Legal libraries using `{d3v-legal}` tags.

## Requirements

- Joomla 3.10+ / 4.x / 5.x
- PHP 8.1+

## Installation

1. Create a zip archive of this folder. The archive must contain the plugin files at the root:

   ```
   d3vlegal.xml
   d3vlegal.php
   D3vLegalRenderer.php
   ```

2. In the Joomla administrator, go to **System → Install → Extensions** (Joomla 4/5) or **Extensions → Manage → Install** (Joomla 3).

3. Upload and install the zip file.

4. Enable the plugin at **System → Manage → Plugins**, search for **Content - D3V Legal Notices**, and enable it.

## Usage

Insert a tag anywhere Joomla content is prepared (articles, custom HTML modules, etc.):

```text
{d3v-legal notice="privacy" company="Example Co" email="privacy@example.co.za"}
```

### Available attributes

| Attribute     | Description                                              |
|---------------|----------------------------------------------------------|
| `notice`      | Required. Notice key, e.g. `privacy`, `cookies`, `tscs`. |
| `country`     | ISO3 country code, e.g. `ZAF`, `GBR`.                    |
| `language`    | Language code, e.g. `ENG`, `AFR`.                        |
| `company`     | Company / responsible party name.                        |
| `email`       | Primary contact email.                                   |
| `support_email` | Support email address.                                 |
| `officer_email` | Information officer email address.                     |
| `address`     | Physical address.                                        |
| `tel`         | Telephone number.                                        |
| `smp`         | Social media profile.                                    |
| `websiteurl`  | Website URL.                                             |
| `officer`     | Information officer name.                                |
| `regno`       | Company registration number.                             |
| `vatno`       | VAT number.                                              |
| `returnwindow`| Return window in days (default `30`).                    |
| `policyurl`   | URL to the full policy document.                         |

### Example tags

```text
{d3v-legal notice="cookies" company="Example Co"}
{d3v-legal notice="paia" company="Example Co" officer="Jane Doe" regno="1999/123456/07"}
{d3v-legal notice="returns" company="Example Co" returnwindow="14"}
{d3v-legal notice="privacy" country="GBR" company="Example Ltd"}
{d3v-legal notice="cookies" country="ZAF" language="AFR" company="Voorbeeld BK"}
```

## Available notices

- `cookies`
- `privacy`
- `copyright`
- `copyrightfooter`
- `disclaimer`
- `emaildisclaimer`
- `tscs`
- `comptscs`
- `contact`
- `smr`
- `smn`
- `paia`
- `returns`
- `support`
- `shipping`
- `payments`
- `ecomtscs`
- `accessibility`

## License

GPL-2.0-or-later
