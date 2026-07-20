# d3v-legal
WordPress plugin that outputs legal notices such as privacy policy, copyright, disclaimer, cookie notices, terms and conditions, and social media statements for South African websites. This plugin is informational and should be reviewed by a legal professional before being used in production compliance workflows.

> Note: This plugin is provided for informational purposes only and does not constitute legal advice. Please consult a qualified attorney or compliance professional before relying on it for legal or regulatory compliance.

## Installation
1. Copy the plugin folder to your WordPress plugins directory.
2. Activate the plugin from the WordPress admin area.
3. Add shortcode snippets to your content or templates.

### Local development
- Install PHP 8.1+.
- Install Composer.
- Run `composer install`.
- Run `php -l d3v-legal.php` to lint the plugin.
- Run `composer test` to execute the PHPUnit regression suite.

## Shortcode Usage
```text
[d3v-legal notice='' company='' email='' address='' tel='' smp='' websiteurl='']
```

- `notice`: Type of notice to render.
- `company`: Company or brand name shown in the output.
- `email`: Contact email used in notices.
- `address`: Physical address or registered office.
- `tel`: Contact number.
- `smp`: Social media platform name.
- `websiteurl`: Website URL for email disclaimer content.

## Shortcode Examples

### Cookie Notice
```text
[d3v-legal notice='cookies' company='ABC Holdings']
```

### Privacy Policy
```text
[d3v-legal notice='privacy' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa' email='info@abc.com' tel='+27 82 000 0000']
```

### Copyright Notice
```text
[d3v-legal notice='copyright' company='ABC Holdings']
```

### Footer Copyright Notice
```text
[d3v-legal notice='copyrightfooter' company='ABC Holdings']
```

### Disclaimer
```text
[d3v-legal notice='disclaimer' company='ABC Holdings']
```

### Email Disclaimer
```text
[d3v-legal notice='emaildisclaimer' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa' websiteurl='www.abcexample.com']
```

### Terms and Conditions
```text
[d3v-legal notice='tscs' company='ABC Holdings' address='21 Random Street, Somewhere, South Africa']
```

### Competition Terms and Conditions
```text
[d3v-legal notice='comptscs' company='ABC Holdings' email='info@abc.com' address='21 Random Street, Somewhere, South Africa' tel='+27 82 000 0000' smp='Facebook']
```

### Contact
```text
[d3v-legal notice='contact' company='ABC Holdings' email='info@abc.com']
```

### Social Media Release Statement
```text
[d3v-legal notice='smr' company='ABC Holdings' smp='Facebook']
```

### Social Media Netiquette
```text
[d3v-legal notice='smn' company='ABC Holdings' smp='Facebook']
```

## Documentation
- [docs/SDD.md](docs/SDD.md)
- [docs/TDD.md](docs/TDD.md)
- [docs/AGENT_MEMORY.md](docs/AGENT_MEMORY.md)

## Release Process
The repository includes a GitHub Actions workflow in [.github/workflows/release.yml](.github/workflows/release.yml) that lints the plugin, runs the PHP test suite, and packages a release archive whenever a version tag is pushed.
