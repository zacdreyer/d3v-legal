# Software Design Document (SDD)

## 1. Purpose
This plugin provides reusable legal notice markup for WordPress sites, with a focus on South African compliance language and POPIA-related notices.

## 2. Scope
The plugin exposes a shortcode, [d3v-legal], and renders a set of legal notice sections such as cookies, privacy policy, copyright, disclaimer, terms and conditions, and social media notices.

## 3. Architecture
- Entry point: d3v-legal.php
- Shortcode handler: d3v_legal_notices()
- Output functions: cookies(), privacy_policy(), copyright(), disclaimer(), email_disclaimer(), tscs(), comp_tscs(), contact(), social_media_release(), social_media_netiquette()
- Rendering approach: output is buffered and returned as a string so it can be tested outside the WordPress runtime.

## 4. Design Notes
- The plugin uses a simple attribute map and sanitizes values before rendering.
- It avoids direct echo output in the shortcode handler so it is easier to test and reuse.
- Default fallback functions are provided when WordPress helpers are unavailable so the plugin can be linted and unit tested in a basic PHP environment.

## 5. Future Enhancements
- Add a settings page for defaults and configurable company details.
- Add admin notice support for legal-policy links.
- Add more granular notice templates and translation support.
