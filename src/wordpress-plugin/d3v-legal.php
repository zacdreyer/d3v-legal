<?php
/*
 Plugin Name: D3V Legal Notices
 Plugin URI: https://github.com/zacdreyer/d3v-legal/
 Description: Output relevant legal notices by country, including South Africa and the United Kingdom. Backend defaults are configured under Settings > D3V Legal.
 Version: 2026.07.28
 Author: Zac Dreyer
 Author URI: https://github.com/zacdreyer/
 Text Domain: legalnotices
 Requires at least: 6.4
 Tested up to: 6.6
 Requires PHP: 8.1
 License: GPL-2.0-or-later
 License URI: https://www.gnu.org/licenses/gpl-2.0.html

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// ------------------------------------------------------------------------------
// WordPress function fallbacks for testing outside WordPress.
// ------------------------------------------------------------------------------
if (! function_exists('shortcode_atts')) {
    /**
     * Fallback implementation of shortcode_atts for CLI/PHPUnit contexts.
     */
    function shortcode_atts($pairs, $atts) {
        $atts = is_array($atts) ? $atts : array();
        $normalized = array();

        foreach ($pairs as $name => $default) {
            $normalized[$name] = isset($atts[$name]) ? $atts[$name] : $default;
        }

        return $normalized;
    }
}

if (! function_exists('add_shortcode')) {
    /**
     * Fallback implementation of add_shortcode for CLI/PHPUnit contexts.
     */
    function add_shortcode($tag, $function_to_add) {
        return true;
    }
}

if (! function_exists('sanitize_text_field')) {
    /**
     * Fallback implementation of sanitize_text_field for CLI/PHPUnit contexts.
     */
    function sanitize_text_field($value) {
        if (is_array($value)) {
            return '';
        }

        return trim(strip_tags((string) $value));
    }
}

if (! function_exists('esc_html')) {
    /**
     * Fallback implementation of esc_html for CLI/PHPUnit contexts.
     */
    function esc_html($text) {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('esc_url')) {
    /**
     * Fallback implementation of esc_url for CLI/PHPUnit contexts.
     *
     * Mirrors WordPress behaviour of normalising query-string ampersands for
     * safe use in HTML attributes and adding a default scheme.
     */
    function esc_url($url) {
        $url = (string) $url;
        if ('' === $url) {
            return '';
        }
        if (! preg_match('#^(https?|mailto|tel|ftp)://#i', $url) && '/' !== substr($url, 0, 1) && '#' !== substr($url, 0, 1)) {
            $url = 'https://' . $url;
        }
        $url = str_replace('&amp;', '&', $url);
        $url = str_replace('&', '&amp;', $url);
        return $url;
    }
}

if (! function_exists('esc_attr')) {
    /**
     * Fallback implementation of esc_attr for CLI/PHPUnit contexts.
     */
    function esc_attr($text) {
        return esc_html($text);
    }
}

if (! function_exists('__')) {
    /**
     * Fallback implementation of __ for CLI/PHPUnit contexts.
     */
    function __($text, $domain = 'default') {
        return $text;
    }
}

if (! function_exists('is_admin')) {
    /**
     * Fallback implementation of is_admin for CLI/PHPUnit contexts.
     */
    function is_admin() {
        return false;
    }
}

if (! function_exists('get_option')) {
    /**
     * Fallback implementation of get_option for CLI/PHPUnit contexts.
     */
    function get_option($option, $default = false) {
        return $default;
    }
}

if (! function_exists('add_action')) {
    /**
     * Fallback implementation of add_action for CLI/PHPUnit contexts.
     */
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (! function_exists('trailingslashit')) {
    /**
     * Fallback implementation of trailingslashit for CLI/PHPUnit contexts.
     */
    function trailingslashit($string) {
        return rtrim($string, '/\\') . '/';
    }
}

if (! function_exists('plugin_dir_path')) {
    /**
     * Fallback implementation of plugin_dir_path for CLI/PHPUnit contexts.
     */
    function plugin_dir_path($file) {
        return trailingslashit(dirname($file));
    }
}

if (! function_exists('d3v_legal_escape')) {
    /**
     * Central escaping helper used by all renderers.
     */
    function d3v_legal_escape($value) {
        return esc_html($value);
    }
}

if (! function_exists('d3v_legal_policy_link')) {
    /**
     * Render a link to the full standalone policy.
     *
     * @param string $url   URL to the full policy.
     * @param string $label Link label.
     * @return string HTML link.
     */
    function d3v_legal_policy_link($url, $label) {
        $url   = esc_url($url);
        $label = esc_html($label);
        return '<p><a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $label . '</a></p>';
    }
}

if (! function_exists('d3v_legal_get_backend_defaults')) {
    /**
     * Retrieve default values stored in the WordPress admin settings page.
     *
     * The global override $d3v_legal_test_settings is used by the PHPUnit
     * regression suite to simulate saved backend values outside WordPress.
     *
     * @return array Default values for shortcode attributes.
     */
    function d3v_legal_get_backend_defaults() {
        if (isset($GLOBALS['d3v_legal_test_settings'])) {
            return $GLOBALS['d3v_legal_test_settings'];
        }

        $defaults = array(
            'default_country'  => 'ZAF',
            'default_language' => 'ENG',
            'company'          => '',
            'email'            => '',
            'support_email'    => '',
            'officer_email'    => '',
            'address'          => '',
            'tel'              => '',
            'smp'              => '',
            'websiteurl'       => '',
            'officer'          => '',
            'regno'            => '',
            'vatno'            => '',
            'returnwindow'     => '',
            'policyurl'        => '',
        );

        if (! function_exists('get_option')) {
            return $defaults;
        }

        $settings = get_option('d3v_legal_settings', array());
        if (! is_array($settings)) {
            return $defaults;
        }

        foreach (array_keys($defaults) as $key) {
            if (isset($settings[$key]) && '' !== $settings[$key]) {
                $defaults[$key] = $settings[$key];
            }
        }

        return $defaults;
    }
}

// ------------------------------------------------------------------------------
// Shortcode registration.
// ------------------------------------------------------------------------------
if (! function_exists('d3v_legal_notices')) {
    /**
     * Main shortcode handler for [d3v-legal].
     *
     * Shortcode attributes take priority. If an attribute is omitted or empty,
     * the value saved in Settings > D3V Legal is used. If no backend value
     * exists, sensible fallbacks (for example a 30-day return window) apply.
     *
     * Available notices:
     *  cookies, privacy, copyright, copyrightfooter, disclaimer, emaildisclaimer,
     *  tscs, comptscs, contact, smr, smn, paia, returns, support, shipping,
     *  payments, ecomtscs, accessibility.
     *
     * @param array|string $atts Shortcode attributes.
     * @return string Rendered HTML.
     */
    function d3v_legal_notices($atts) {
        $backend_defaults = d3v_legal_get_backend_defaults();

        $empty_defaults = array(
            'notice'           => '',
            'country'          => '',
            'language'         => '',
            'company'          => '',
            'email'            => '',
            'support_email'    => '',
            'officer_email'    => '',
            'address'          => '',
            'tel'              => '',
            'smp'              => '',
            'websiteurl'       => '',
            'officer'          => '',
            'regno'            => '',
            'vatno'            => '',
            'returnwindow'     => '',
            'policyurl'        => '',
            'default_country'  => '',
            'default_language' => '',
        );

        $parsed = shortcode_atts($empty_defaults, is_array($atts) ? $atts : array());

        foreach ($backend_defaults as $key => $value) {
            if ('' === $parsed[$key] && '' !== $value) {
                $parsed[$key] = $value;
            }
        }

        if ('' === $parsed['support_email'] && '' !== $parsed['email']) {
            $parsed['support_email'] = $parsed['email'];
        }

        if ('' === $parsed['officer_email'] && '' !== $parsed['email']) {
            $parsed['officer_email'] = $parsed['email'];
        }

        if ('' === $parsed['returnwindow']) {
            $parsed['returnwindow'] = '30';
        }

        $normalized = array_map('sanitize_text_field', $parsed);

        if ('' === $normalized['notice']) {
            return '';
        }

        $country  = strtoupper(sanitize_text_field($normalized['country']));
        $language = strtoupper(sanitize_text_field($normalized['language']));

        $supported_countries = d3v_legal_supported_countries();
        if (! in_array($country, $supported_countries, true)) {
            $country = d3v_legal_get_default_country();
        }

        $supported_languages = d3v_legal_supported_languages($country);
        if (! in_array($language, $supported_languages, true)) {
            $language = d3v_legal_get_default_language($country);
        }

        $library = d3v_legal_load_country_library($country, $language);
        if (empty($library) || ! isset($library[$country]['notices'][$normalized['notice']])) {
            return '';
        }

        if (! is_array($library[$country]['notices'][$normalized['notice']])) {
            return '';
        }

        $notice = $library[$country]['notices'][$normalized['notice']];

        return d3v_legal_render_notice($normalized['notice'], $notice, $normalized, $country);
    }
}

if (! function_exists('d3v_legal_get_library_directory')) {
    /**
     * Resolve the absolute path to the legal libraries subdirectory.
     *
     * The plugin supports two layouts:
     *  - Development: the plugin lives in a platform folder (e.g. wordpress-plugin)
     *    and the shared legal-libraries directory sits at the repository root.
     *  - Distribution: the plugin is self-contained and legal-libraries is inside
     *    the plugin folder.
     *
     * @return string Path to the libraries directory with trailing slash.
     */
    function d3v_legal_get_library_directory() {
        if (defined('D3V_LEGAL_LIBRARY_PATH')) {
            return trailingslashit(D3V_LEGAL_LIBRARY_PATH);
        }

        $plugin_dir = plugin_dir_path(__FILE__);
        if ('' === $plugin_dir) {
            $plugin_dir = trailingslashit(__DIR__);
        }

        // Development layout: plugin is inside src/<platform>/ and legal-libraries
        // lives at the repository root (two levels above the plugin file).
        $centralized = realpath(
            $plugin_dir
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'legal-libraries'
        );
        if (false !== $centralized && is_dir($centralized)) {
            return $centralized . DIRECTORY_SEPARATOR;
        }

        // Distribution layout: legal-libraries is bundled inside the plugin folder.
        return $plugin_dir . 'legal-libraries' . DIRECTORY_SEPARATOR;
    }
}

if (! function_exists('d3v_legal_discover_libraries')) {
    /**
     * Discover all legal library files and parse their country/language codes.
     *
     * Files must match the pattern {ISO3}-{LANG}-legals.json, where ISO3 is a
     * three-letter country code and LANG is a language code.
     *
     * @return array Map of ISO3 country codes to arrays of uppercase language codes.
     */
    function d3v_legal_discover_libraries() {
        $library_dir = d3v_legal_get_library_directory();
        $allowed_base = realpath($library_dir);
        if (false === $allowed_base) {
            return array();
        }

        $files = glob($library_dir . '*-legals.json');
        if (! is_array($files)) {
            return array();
        }

        $libraries = array();

        foreach ($files as $file) {
            $basename = basename((string) $file);
            if (! preg_match('/^([A-Za-z]{3})-([A-Za-z]+)-legals\.json$/', $basename, $matches)) {
                continue;
            }

            $real_file = realpath($file);
            if (false === $real_file) {
                continue;
            }

            if (0 !== strpos($real_file, $allowed_base . DIRECTORY_SEPARATOR)) {
                continue;
            }

            $country  = strtoupper($matches[1]);
            $language = strtoupper($matches[2]);

            if (! isset($libraries[$country])) {
                $libraries[$country] = array();
            }

            if (! in_array($language, $libraries[$country], true)) {
                $libraries[$country][] = $language;
            }
        }

        return $libraries;
    }
}

if (! function_exists('d3v_legal_supported_countries')) {
    /**
     * Return the list of supported country ISO3 codes.
     *
     * Countries are discovered dynamically by scanning the plugin's
     * legal-libraries directory for files matching the pattern
     * {ISO3}-{LANG}-legals.json. This allows users to add new jurisdictions
     * simply by dropping a correctly-named JSON library into the folder.
     *
     * @return array Supported uppercase ISO3 country codes.
     */
    function d3v_legal_supported_countries() {
        $libraries = d3v_legal_discover_libraries();
        $countries = array_keys($libraries);

        if (empty($countries)) {
            return array('ZAF');
        }

        sort($countries);
        return $countries;
    }
}

if (! function_exists('d3v_legal_supported_languages')) {
    /**
     * Return the list of supported language codes for a given country.
     *
     * @param string $country ISO3 country code.
     * @return array Supported uppercase language codes.
     */
    function d3v_legal_supported_languages($country) {
        $libraries = d3v_legal_discover_libraries();
        $country   = strtoupper(sanitize_text_field($country));

        if (! isset($libraries[$country]) || empty($libraries[$country])) {
            return array('ENG');
        }

        $languages = $libraries[$country];
        sort($languages);
        return $languages;
    }
}

if (! function_exists('d3v_legal_get_default_country')) {
    /**
     * Determine the default country for rendering notices.
     *
     * Priority: backend setting > discovered fallback (ZAF if present, else the
     * first supported country).
     *
     * @return string ISO3 country code.
     */
    function d3v_legal_get_default_country() {
        $supported = d3v_legal_supported_countries();
        $backend   = d3v_legal_get_backend_defaults();
        $country   = isset($backend['default_country']) ? strtoupper(sanitize_text_field($backend['default_country'])) : '';

        if (in_array($country, $supported, true)) {
            return $country;
        }

        if (in_array('ZAF', $supported, true)) {
            return 'ZAF';
        }

        return isset($supported[0]) ? $supported[0] : 'ZAF';
    }
}

if (! function_exists('d3v_legal_get_default_language')) {
    /**
     * Determine the default language for a given country.
     *
     * Priority: backend setting > discovered fallback (ENG if present, else the
     * first supported language for the country).
     *
     * @param string $country ISO3 country code.
     * @return string Uppercase language code.
     */
    function d3v_legal_get_default_language($country) {
        $supported = d3v_legal_supported_languages($country);
        $backend   = d3v_legal_get_backend_defaults();
        $language  = isset($backend['default_language']) ? strtoupper(sanitize_text_field($backend['default_language'])) : '';

        if (in_array($language, $supported, true)) {
            return $language;
        }

        if (in_array('ENG', $supported, true)) {
            return 'ENG';
        }

        return isset($supported[0]) ? $supported[0] : 'ENG';
    }
}

if (! function_exists('d3v_legal_get_library_path')) {
    /**
     * Resolve the absolute path to a country/language legal library JSON file.
     *
     * Only files within the plugin's legal-libraries directory are accepted to
     * prevent path traversal and to ensure external JSON cannot be loaded.
     *
     * @param string $country  ISO3 country code.
     * @param string $language Language code.
     * @return string Normalised file path, or empty string if invalid.
     */
    function d3v_legal_get_library_path($country, $language) {
        $country  = strtoupper(sanitize_text_field($country));
        $language = strtoupper(sanitize_text_field($language));

        if (! preg_match('/^[A-Z]{3}$/', $country)) {
            return '';
        }

        if (! preg_match('/^[A-Z]+$/', $language)) {
            return '';
        }

        $library_dir = d3v_legal_get_library_directory();
        $allowed_base = realpath($library_dir);
        if (false === $allowed_base) {
            return '';
        }

        $expected_file = $country . '-' . $language . '-legals.json';
        $files = glob($library_dir . '*-legals.json');
        if (! is_array($files)) {
            return '';
        }

        foreach ($files as $file) {
            $basename = basename((string) $file);
            if (strtoupper($basename) !== strtoupper($expected_file)) {
                continue;
            }

            $real_file = realpath($file);
            if (false === $real_file) {
                continue;
            }

            if (0 !== strpos($real_file, $allowed_base . DIRECTORY_SEPARATOR)) {
                continue;
            }

            return $real_file;
        }

        return '';
    }
}

if (! function_exists('d3v_legal_load_country_library')) {
    /**
     * Load the JSON legal library for a given country and language.
     *
     * @param string $country  ISO3 country code.
     * @param string $language Language code.
     * @return array Decoded library data, or empty array if unavailable.
     */
    function d3v_legal_load_country_library($country, $language) {
        $file = d3v_legal_get_library_path($country, $language);
        if ('' === $file) {
            return array();
        }

        $json = file_get_contents($file);
        if (false === $json) {
            return array();
        }

        $data = json_decode($json, true);
        if (! is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            return array();
        }

        return $data;
    }
}

if (! function_exists('d3v_legal_render_notice')) {
    /**
     * Render a notice from its JSON library definition.
     *
     * @param string $notice_key Shortcode notice identifier.
     * @param array  $notice     Notice definition from JSON.
     * @param array  $atts       Normalised shortcode attributes.
     * @param string $country    ISO3 country code.
     * @return string Rendered HTML.
     */
    function d3v_legal_render_notice($notice_key, $notice, $atts, $country) {
        $id = 'd3v-legal-' . preg_replace('/[^a-z0-9-]+/', '-', strtolower($notice_key));
        $html = '<div id="' . esc_attr($id) . '" class="d3v-legal d3v-legal-' . esc_attr(strtolower($notice_key)) . '">';

        if (isset($notice['sections']) && is_array($notice['sections'])) {
            foreach ($notice['sections'] as $section) {
                if (! is_array($section)) {
                    continue;
                }

                if (! d3v_legal_section_is_visible($section, $atts)) {
                    continue;
                }

                $type = isset($section['type']) ? sanitize_text_field($section['type']) : 'paragraph';

                switch ($type) {
                    case 'list':
                        $html .= d3v_legal_render_list_section($section, $atts);
                        break;
                    case 'contact_list':
                        $html .= d3v_legal_render_contact_list_section($section, $atts);
                        break;
                    case 'joined_fields':
                        $html .= d3v_legal_render_joined_fields_section($section, $atts);
                        break;
                    case 'policy_link':
                        $html .= d3v_legal_render_policy_link_section($section, $atts);
                        break;
                    default:
                        $html .= d3v_legal_render_paragraph_section($section, $atts, $country);
                        break;
                }
            }
        }

        $html .= '</div>';
        return $html;
    }
}

if (! function_exists('d3v_legal_section_is_visible')) {
    /**
     * Determine whether a section should be rendered based on its condition.
     *
     * @param array $section Section definition.
     * @param array $atts    Normalised shortcode attributes.
     * @return bool True if the section should be rendered.
     */
    function d3v_legal_section_is_visible($section, $atts) {
        if (! isset($section['condition'])) {
            return true;
        }

        $condition = sanitize_text_field($section['condition']);

        if ('any' === $condition) {
            if (! isset($section['fields'])) {
                return true;
            }
            foreach ($section['fields'] as $field) {
                if (! is_array($field)) {
                    continue;
                }
                $placeholder = isset($field['value']) ? $field['value'] : '';
                if (d3v_legal_has_replacement_value($placeholder, $atts)) {
                    return true;
                }
            }
            return false;
        }

        if (isset($atts[$condition]) && '' !== $atts[$condition]) {
            return true;
        }

        return false;
    }
}

if (! function_exists('d3v_legal_has_replacement_value')) {
    /**
     * Check whether a template placeholder would receive a non-empty value.
     *
     * @param string $template Template string containing placeholders.
     * @param array  $atts     Normalised shortcode attributes.
     * @return bool True if at least one placeholder maps to a non-empty value.
     */
    function d3v_legal_has_replacement_value($template, $atts) {
        $tokens = d3v_legal_extract_placeholders($template);

        foreach ($tokens as $token) {
            $value = d3v_legal_get_placeholder_value($token, $atts);
            if ('' !== $value) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('d3v_legal_extract_placeholders')) {
    /**
     * Extract placeholder names from a template string.
     *
     * Placeholders use the syntax {{key}} or {{key||fallback}}. Nested
     * placeholders inside a fallback are also discovered so that visibility
     * checks and joined-field rendering can consider the full fallback chain.
     *
     * @param string $template Template string.
     * @return array List of placeholder names.
     */
    function d3v_legal_extract_placeholders($template) {
        if (! preg_match_all('/\{\{([a-z0-9_]+)/i', $template, $matches)) {
            return array();
        }
        return array_unique($matches[1]);
    }
}

if (! function_exists('d3v_legal_get_placeholder_value')) {
    /**
     * Resolve a single placeholder against the shortcode attributes.
     *
     * @param string $key  Placeholder key.
     * @param array  $atts Normalised shortcode attributes.
     * @return string Resolved value.
     */
    function d3v_legal_get_placeholder_value($key, $atts) {
        if ('year' === $key) {
            return (string) date('Y');
        }

        $value = isset($atts[$key]) ? sanitize_text_field($atts[$key]) : '';
        return $value;
    }
}

if (! function_exists('d3v_legal_replace_placeholders')) {
    /**
     * Replace all placeholders in a template string with their values.
     *
     * Supports nested fallback placeholders (e.g. {{support_email||{{email}}}})
     * by applying replacements iteratively until the output stabilises.
     *
     * @param string $template Template string.
     * @param array  $atts     Normalised shortcode attributes.
     * @return string Rendered text.
     */
    function d3v_legal_replace_placeholders($template, $atts) {
        $max_iterations = 5;
        $rendered = $template;

        for ($i = 0; $i < $max_iterations; $i++) {
            $previous = $rendered;
            $rendered = preg_replace_callback(
                '/\{\{([a-z0-9_]+)(?:\|\|([^}]*))?\}\}/i',
                function ($matches) use ($atts) {
                    $key = $matches[1];
                    $fallback = isset($matches[2]) ? $matches[2] : '';
                    $value = d3v_legal_get_placeholder_value($key, $atts);
                    return '' !== $value ? d3v_legal_escape($value) : esc_html($fallback);
                },
                $rendered
            );

            if ($rendered === $previous) {
                break;
            }
        }

        return $rendered;
    }
}

if (! function_exists('d3v_legal_render_paragraph_section')) {
    /**
     * Render a simple paragraph section.
     *
     * @param array  $section Section definition.
     * @param array  $atts    Normalised shortcode attributes.
     * @param string $country ISO3 country code.
     * @return string HTML.
     */
    function d3v_legal_render_paragraph_section($section, $atts, $country) {
        $html = '';

        if (isset($section['title']) && '' !== trim((string) $section['title'])) {
            $html .= '<p><strong>' . esc_html($section['title']) . '</strong></p>';
        }

        if (isset($section['template'])) {
            $template = (string) $section['template'];
            $html .= '<p>' . d3v_legal_replace_placeholders($template, $atts) . '</p>';
        }

        return $html;
    }
}

if (! function_exists('d3v_legal_render_list_section')) {
    /**
     * Render a section as a list.
     *
     * @param array $section Section definition.
     * @param array $atts    Normalised shortcode attributes.
     * @return string HTML.
     */
    function d3v_legal_render_list_section($section, $atts) {
        $html = '';

        if (isset($section['title']) && '' !== trim((string) $section['title'])) {
            $html .= '<p><strong>' . esc_html($section['title']) . '</strong></p>';
        }

        if (isset($section['items']) && is_array($section['items'])) {
            $html .= '<ul>';
            foreach ($section['items'] as $item) {
                $html .= '<li>' . d3v_legal_replace_placeholders((string) $item, $atts) . '</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }
}

if (! function_exists('d3v_legal_render_contact_list_section')) {
    /**
     * Render a contact details list, omitting empty entries.
     *
     * @param array $section Section definition.
     * @param array $atts    Normalised shortcode attributes.
     * @return string HTML.
     */
    function d3v_legal_render_contact_list_section($section, $atts) {
        $items = array();

        if (isset($section['fields']) && is_array($section['fields'])) {
            foreach ($section['fields'] as $field) {
                if (! is_array($field) || ! isset($field['value'])) {
                    continue;
                }

                if (isset($field['condition'])) {
                    if ('any' === $field['condition'] && ! d3v_legal_has_replacement_value($field['value'], $atts)) {
                        continue;
                    }
                    if ('any' !== $field['condition'] && (! isset($atts[$field['condition']]) || '' === $atts[$field['condition']])) {
                        continue;
                    }
                }

                $rendered = d3v_legal_replace_placeholders($field['value'], $atts);
                if ('' !== trim(strip_tags($rendered))) {
                    $items[] = $rendered;
                }
            }
        }

        if (empty($items)) {
            return '';
        }

        $html = '';
        if (isset($section['title']) && '' !== trim((string) $section['title'])) {
            $html .= '<p><strong>' . esc_html($section['title']) . '</strong></p>';
        }

        $html .= '<ul>';
        foreach ($items as $item) {
            $html .= '<li>' . $item . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}

if (! function_exists('d3v_legal_render_joined_fields_section')) {
    /**
     * Render fields joined by a separator, omitting empties.
     *
     * @param array $section Section definition.
     * @param array $atts    Normalised shortcode attributes.
     * @return string HTML.
     */
    function d3v_legal_render_joined_fields_section($section, $atts) {
        $template = isset($section['template']) ? (string) $section['template'] : '';
        $separator = isset($section['separator']) ? (string) $section['separator'] : ', ';

        $parts = array();
        $tokens = d3v_legal_extract_placeholders($template);
        foreach ($tokens as $token) {
            $value = d3v_legal_get_placeholder_value($token, $atts);
            if ('' !== $value) {
                $parts[] = d3v_legal_escape($value);
            }
        }

        if (empty($parts)) {
            return '';
        }

        $html = '';
        if (isset($section['title']) && '' !== trim((string) $section['title'])) {
            $html .= '<p><strong>' . esc_html($section['title']) . '</strong></p>';
        }

        $html .= '<p>' . implode(esc_html($separator), $parts) . '</p>';
        return $html;
    }
}

if (! function_exists('d3v_legal_render_policy_link_section')) {
    /**
     * Render a policy link section when a policy URL is available.
     *
     * @param array $section Section definition.
     * @param array $atts    Normalised shortcode attributes.
     * @return string HTML.
     */
    function d3v_legal_render_policy_link_section($section, $atts) {
        $policyurl = isset($atts['policyurl']) ? esc_url($atts['policyurl']) : '';
        if ('' === $policyurl) {
            return '';
        }

        $label = isset($section['label']) ? (string) $section['label'] : __('Read full policy', 'legalnotices');
        return d3v_legal_policy_link($policyurl, $label);
    }
}

if (! function_exists('d3v_legal_register_shortcode')) {
    /**
     * Register the [d3v-legal] shortcode with WordPress.
     */
    function d3v_legal_register_shortcode() {
        if (function_exists('add_shortcode')) {
            add_shortcode('d3v-legal', 'd3v_legal_notices');
        }
    }
}

d3v_legal_register_shortcode();

// ------------------------------------------------------------------------------
// Admin settings page.
// ------------------------------------------------------------------------------
if (! function_exists('d3v_legal_admin_menu')) {
    /**
     * Add the D3V Legal settings page under Settings.
     */
    function d3v_legal_admin_menu() {
        add_options_page(
            __('D3V Legal Settings', 'legalnotices'),
            __('D3V Legal', 'legalnotices'),
            'manage_options',
            'd3v-legal',
            'd3v_legal_settings_page'
        );
    }
}

if (! function_exists('d3v_legal_register_settings')) {
    /**
     * Register settings, sections and fields for the admin page.
     */
    function d3v_legal_register_settings() {
        register_setting(
            'd3v_legal_settings_group',
            'd3v_legal_settings',
            array(
                'type'              => 'array',
                'sanitize_callback' => 'd3v_legal_sanitize_settings',
                'default'           => array(),
            )
        );

        add_settings_section(
            'd3v_legal_section_country',
            __('Country Defaults', 'legalnotices'),
            'd3v_legal_section_country_callback',
            'd3v-legal'
        );

        add_settings_section(
            'd3v_legal_section_business',
            __('Business Details', 'legalnotices'),
            'd3v_legal_section_business_callback',
            'd3v-legal'
        );

        add_settings_section(
            'd3v_legal_section_contact',
            __('Contact Details', 'legalnotices'),
            'd3v_legal_section_contact_callback',
            'd3v-legal'
        );

        add_settings_section(
            'd3v_legal_section_legal',
            __('Legal Defaults', 'legalnotices'),
            'd3v_legal_section_legal_callback',
            'd3v-legal'
        );

        $fields = array(
            array('section' => 'd3v_legal_section_country',  'id' => 'default_country',  'label' => __('Default country (ISO3)', 'legalnotices'),             'type' => 'select_country'),
            array('section' => 'd3v_legal_section_country',  'id' => 'default_language', 'label' => __('Default language', 'legalnotices'),                   'type' => 'select_language'),
            array('section' => 'd3v_legal_section_business', 'id' => 'company',          'label' => __('Company / brand name', 'legalnotices'),               'type' => 'text'),
            array('section' => 'd3v_legal_section_business', 'id' => 'regno',            'label' => __('Company registration number', 'legalnotices'),        'type' => 'text'),
            array('section' => 'd3v_legal_section_business', 'id' => 'vatno',            'label' => __('VAT registration number', 'legalnotices'),            'type' => 'text'),
            array('section' => 'd3v_legal_section_contact',  'id' => 'email',            'label' => __('General contact email address', 'legalnotices'),        'type' => 'text'),
            array('section' => 'd3v_legal_section_contact',  'id' => 'support_email',    'label' => __('Support email address (optional)', 'legalnotices'),     'type' => 'text'),
            array('section' => 'd3v_legal_section_contact',  'id' => 'officer_email',    'label' => __('Information Officer / privacy email (optional)', 'legalnotices'), 'type' => 'text'),
            array('section' => 'd3v_legal_section_contact',  'id' => 'tel',              'label' => __('Telephone number', 'legalnotices'),                   'type' => 'text'),
            array('section' => 'd3v_legal_section_contact',  'id' => 'address',          'label' => __('Physical / registered address', 'legalnotices'),        'type' => 'text'),
            array('section' => 'd3v_legal_section_contact',  'id' => 'websiteurl',       'label' => __('Website URL', 'legalnotices'),                        'type' => 'text'),
            array('section' => 'd3v_legal_section_legal',    'id' => 'officer',          'label' => __('Information Officer name', 'legalnotices'),           'type' => 'text'),
            array('section' => 'd3v_legal_section_legal',    'id' => 'smp',              'label' => __('Default social media platform', 'legalnotices'),      'type' => 'text'),
            array('section' => 'd3v_legal_section_legal',    'id' => 'returnwindow',     'label' => __('Default return window (days)', 'legalnotices'),       'type' => 'number'),
            array('section' => 'd3v_legal_section_legal',    'id' => 'policyurl',        'label' => __('Default policy page URL', 'legalnotices'),          'type' => 'text'),
        );

        foreach ($fields as $field) {
            add_settings_field(
                'd3v_legal_' . $field['id'],
                $field['label'],
                'd3v_legal_render_field',
                'd3v-legal',
                $field['section'],
                array(
                    'label_for' => 'd3v_legal_' . $field['id'],
                    'name'      => $field['id'],
                    'type'      => $field['type'],
                )
            );
        }
    }
}

if (! function_exists('d3v_legal_sanitize_settings')) {
    /**
     * Sanitize settings before saving.
     *
     * @param array $input Raw settings array.
     * @return array Sanitized settings array.
     */
    function d3v_legal_sanitize_settings($input) {
        $sanitized = array();
        $keys      = array('default_country', 'default_language', 'company', 'email', 'support_email', 'officer_email', 'address', 'tel', 'smp', 'websiteurl', 'officer', 'regno', 'vatno', 'returnwindow', 'policyurl');

        foreach ($keys as $key) {
            if (! isset($input[$key])) {
                $sanitized[$key] = '';
                continue;
            }

            if ('returnwindow' === $key) {
                $sanitized[$key] = absint($input[$key]);
                if ($sanitized[$key] < 1) {
                    $sanitized[$key] = '';
                }
            } elseif ('default_country' === $key) {
                $sanitized[$key] = strtoupper(sanitize_text_field($input[$key]));
                if (! in_array($sanitized[$key], d3v_legal_supported_countries(), true)) {
                    $sanitized[$key] = '';
                }
            } elseif ('default_language' === $key) {
                $sanitized[$key] = strtoupper(sanitize_text_field($input[$key]));
                $country = isset($sanitized['default_country']) && '' !== $sanitized['default_country']
                    ? $sanitized['default_country']
                    : d3v_legal_get_default_country();
                if (! in_array($sanitized[$key], d3v_legal_supported_languages($country), true)) {
                    $sanitized[$key] = '';
                }
            } else {
                $sanitized[$key] = sanitize_text_field($input[$key]);
            }
        }

        return $sanitized;
    }
}

if (! function_exists('d3v_legal_section_country_callback')) {
    function d3v_legal_section_country_callback() {
        echo '<p>' . esc_html__('Select the default country and language used when no country/language attributes are supplied in the shortcode.', 'legalnotices') . '</p>';
    }
}

if (! function_exists('d3v_legal_section_business_callback')) {
    function d3v_legal_section_business_callback() {
        echo '<p>' . esc_html__('Enter the default business details used by the legal notices.', 'legalnotices') . '</p>';
    }
}

if (! function_exists('d3v_legal_section_contact_callback')) {
    function d3v_legal_section_contact_callback() {
        echo '<p>' . esc_html__('These contact details will be used in any notice that requires them.', 'legalnotices') . '</p>';
    }
}

if (! function_exists('d3v_legal_section_legal_callback')) {
    function d3v_legal_section_legal_callback() {
        echo '<p>' . esc_html__('Default values for legal-specific fields.', 'legalnotices') . '</p>';
    }
}

if (! function_exists('d3v_legal_render_field')) {
    /**
     * Render a single settings field.
     *
     * @param array $args Field arguments.
     */
    function d3v_legal_render_field($args) {
        $settings = get_option('d3v_legal_settings', array());
        $name     = $args['name'];
        $id       = $args['label_for'];
        $type     = isset($args['type']) ? $args['type'] : 'text';
        $value    = isset($settings[$name]) ? $settings[$name] : '';

        if ('select_country' === $type) {
            printf(
                '<select id="%s" name="d3v_legal_settings[%s]">',
                esc_attr($id),
                esc_attr($name)
            );
            foreach (d3v_legal_supported_countries() as $code) {
                printf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($code),
                    selected($value, $code, false),
                    esc_html($code)
                );
            }
            echo '</select>';
        } elseif ('select_language' === $type) {
            $country = isset($settings['default_country']) && '' !== $settings['default_country']
                ? $settings['default_country']
                : d3v_legal_get_default_country();

            printf(
                '<select id="%s" name="d3v_legal_settings[%s]">',
                esc_attr($id),
                esc_attr($name)
            );
            foreach (d3v_legal_supported_languages($country) as $code) {
                printf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($code),
                    selected($value, $code, false),
                    esc_html($code)
                );
            }
            echo '</select>';
        } else {
            printf(
                '<input type="%s" id="%s" name="d3v_legal_settings[%s]" value="%s" class="regular-text" />',
                esc_attr($type),
                esc_attr($id),
                esc_attr($name),
                esc_attr($value)
            );
        }
    }
}

if (! function_exists('d3v_legal_settings_page')) {
    /**
     * Output the D3V Legal settings page.
     */
    function d3v_legal_settings_page() {
        if (! current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('d3v_legal_settings_group');
                do_settings_sections('d3v-legal');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

if (function_exists('add_action') && function_exists('is_admin') && is_admin()) {
    add_action('admin_menu', 'd3v_legal_admin_menu');
    add_action('admin_init', 'd3v_legal_register_settings');
}

// ------------------------------------------------------------------------------
// Legacy renderers stub.
//
// IMPORTANT LEGAL DISCLAIMER:
// The notices are now loaded from country-specific JSON legal libraries. They
// are provided for convenience only and do not constitute legal advice. A
// qualified legal practitioner should review and adapt each notice for your
// specific business, industry and data-processing activities before publication.
// ------------------------------------------------------------------------------
if (! function_exists('d3v_legal_renderers_initialized')) {
    function d3v_legal_renderers_initialized() {}

    /**
     * Cookie / tracking notice.
     *
     * Legal sources: POPIA s1 (definition of personal information), s11 (lawfulness
     * of processing); ECTA s43 (information to be provided for electronic
     * transactions).
     */
    function cookies($company, $policyurl = '') {
        $company   = esc_html($company);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-cookies" class="d3v-legal d3v-legal-cookies">
            <p><?php echo $company ?: 'We'; ?> use cookies and similar tracking technologies on this website. Cookies are small text files placed on your device to help the site function, remember your preferences, analyse traffic and improve your experience.</p>

            <p><strong>Types of cookies we use</strong></p>
            <ul>
                <li><strong>Essential cookies:</strong> required for the website to operate and to maintain security. These cookies do not collect personal information.</li>
                <li><strong>Preference cookies:</strong> remember your settings and choices to improve your experience.</li>
                <li><strong>Analytics cookies:</strong> help us understand how visitors interact with the website so that we can improve it. Where analytics data may identify you, we rely on an appropriate lawful basis under POPIA.</li>
                <li><strong>Third-party cookies:</strong> set by approved service providers (for example, analytics or advertising partners) and governed by their own privacy notices.</li>
            </ul>

            <p>You can manage or disable cookies through your browser settings. Please note that restricting essential cookies may affect the functionality of the website. By continuing to use this website, you consent to the use of cookies in accordance with this notice.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full cookie policy'); } ?>
        </div>
        <?php
    }

    /**
     * Comprehensive privacy policy.
     *
     * Legal sources: POPIA ss 1 (definitions), 11 (lawfulness), 13 (consent),
     * 14 (justification), 18 (collection directly from data subject), 19 (collection
     * from other source), 21 (purpose specification), 23 (further processing
     * limitation), 24 (information quality), 58 (security safeguards), 69
     * (participation of data subject), 71 (access to personal information), 73
     * (correction of personal information); PAIA s51 (private-body manual).
     */
    function privacy_policy($company, $address, $email, $tel, $policyurl = '') {
        $company   = esc_html($company);
        $address   = esc_html($address);
        $email     = esc_html($email);
        $tel       = esc_html($tel);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-privacy-policy" class="d3v-legal d3v-legal-privacy-policy">
            <p>This privacy policy explains how <?php echo $company ?: 'we'; ?> collects, uses, protects and shares personal information. By using our website or providing your personal information to us, you acknowledge that you have read and understood this policy.</p>

            <p><strong>Identity of the responsible party</strong></p>
            <p><?php echo $company ?: 'We'; ?> are the responsible party for the processing of personal information collected through this website and our related services. Our contact details are:</p>
            <ul>
                <li><?php echo $company ?: 'Responsible party'; ?></li>
                <?php if ($address) : ?><li><?php echo $address; ?></li><?php endif; ?>
                <?php if ($email) : ?><li>Email: <?php echo $email; ?></li><?php endif; ?>
                <?php if ($tel) : ?><li>Telephone: <?php echo $tel; ?></li><?php endif; ?>
            </ul>

            <p><strong>What personal information we collect</strong></p>
            <p>“Personal information” has the meaning ascribed to it in POPIA. We may collect:</p>
            <ul>
                <li><strong>Directly from you:</strong> name, contact details, identity or company registration number, billing and payment information, and any other information you provide when you register, request a quote, place an order, subscribe to newsletters or contact us.</li>
                <li><strong>Automatically:</strong> IP address, browser type and version, device information, operating system, pages viewed, time spent on the site, referring URLs and other analytics data collected via cookies and similar technologies.</li>
            </ul>

            <p><strong>Purpose of processing</strong></p>
            <p>We process personal information only for lawful and specified purposes, including to:</p>
            <ul>
                <li>provide our products and services;</li>
                <li>process orders, payments and deliveries;</li>
                <li>communicate with you, including service-related messages;</li>
                <li>carry out direct marketing where permitted and with an opportunity to opt out;</li>
                <li>improve our website, services and customer experience;</li>
                <li>comply with legal and regulatory obligations; and</li>
                <li>protect our rights, property and legitimate interests.</li>
            </ul>

            <p><strong>Legal basis for processing</strong></p>
            <p>We process personal information only where a lawful basis exists, such as:</p>
            <ul>
                <li><strong>Consent:</strong> where you have given express consent, for example to receive marketing communications.</li>
                <li><strong>Contract:</strong> where processing is necessary to perform a contract with you or to take steps at your request before entering into a contract.</li>
                <li><strong>Legal obligation:</strong> where we are required to process information by law.</li>
                <li><strong>Legitimate interest:</strong> where processing is necessary for our legitimate business interests, provided your rights and interests do not override those interests.</li>
            </ul>

            <p><strong>Direct marketing and opt-out</strong></p>
            <p>We may use your personal information to send you promotional communications by email, SMS or other channels, but only where permitted by POPIA. You may opt out of direct marketing at any time<?php if ($email) : ?> by contacting us at <?php echo $email; ?> or using the unsubscribe link in any marketing message<?php endif; ?>.</p>

            <p><strong>Cookies and device data</strong></p>
            <p>We use cookies and similar technologies to collect device and usage data. For more information, please see our cookie notice.</p>

            <p><strong>Sharing with third parties</strong></p>
            <p>We do not sell personal information. We may share personal information with trusted third parties who assist us in operating our business, such as payment processors, delivery partners, IT service providers, marketing service providers, legal advisers and auditors. These third parties are required to maintain appropriate confidentiality and security safeguards.</p>

            <p><strong>Transborder flows</strong></p>
            <p>Personal information may be transferred to and processed in countries outside South Africa. Where this occurs, we will take reasonable steps to ensure that the recipient provides an adequate level of protection for the personal information, or that the transfer is otherwise lawful under POPIA.</p>

            <p><strong>Data subject rights</strong></p>
            <p>Under POPIA and PAIA, you have the right to:</p>
            <ul>
                <li>request access to your personal information;</li>
                <li>request correction or deletion of inaccurate or irrelevant personal information;</li>
                <li>object to the processing of your personal information;</li>
                <li>lodge a complaint with the Information Regulator; and</li>
                <li>withdraw consent where processing is based on consent.</li>
            </ul>
            <?php if ($email) : ?><p>To exercise your rights, please contact us at <?php echo $email; ?>. We may require proof of identity before processing your request.</p><?php endif; ?>

            <p><strong>Retention</strong></p>
            <p>We retain personal information only for as long as necessary to fulfil the purposes for which it was collected, to comply with legal obligations, to resolve disputes and to enforce our agreements.</p>

            <p><strong>Security</strong></p>
            <p>We implement appropriate technical and organisational security measures to safeguard personal information against loss, unauthorised access, disclosure, alteration or destruction. No internet-based system can be guaranteed to be completely secure.</p>

            <p><strong>Information Officer</strong></p>
            <p><?php echo $company ?: 'We'; ?> have appointed an Information Officer who is responsible for POPIA compliance and for handling access-to-information requests. Contact details are available on request<?php if ($email) : ?> at <?php echo $email; endif; ?>.</p>

            <p><strong>Complaints to the Information Regulator</strong></p>
            <p>If you believe we have infringed your privacy rights, you may contact the Information Regulator of South Africa. Contact details are available at <a href="https://www.justice.gov.za/inforeg/contact.html" target="_blank" rel="noopener noreferrer">www.justice.gov.za/inforeg/contact.html</a>.</p>

            <p><strong>Children’s privacy</strong></p>
            <p>Our services are not directed to children under the age of 18, and we do not knowingly collect personal information from children without appropriate consent.</p>

            <p><strong>Changes to this privacy policy</strong></p>
            <p>We may update this privacy policy from time to time. The current version will be published on our website with the date of the latest revision. Continued use of the website after changes constitutes acceptance of the revised policy.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full privacy policy'); } ?>
        </div>
        <?php
    }

    /**
     * PAIA manual notice for a private body.
     *
     * Legal sources: PAIA ss 9 (objects), 10 (guide), 50 (right of access for
     * private bodies), 51 (manual), 52 (voluntary disclosure), 53 (form of
     * request), 54 (fees), 56 (decision period), 63-70 (grounds for refusal),
     * 77A (complaints to Regulator), 83 (additional functions of Regulator).
     */
    function paia_manual($company, $address, $email, $tel, $officer, $regno, $policyurl = '') {
        $company   = esc_html($company);
        $address   = esc_html($address);
        $email     = esc_html($email);
        $tel       = esc_html($tel);
        $officer   = esc_html($officer);
        $regno     = esc_html($regno);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-paia" class="d3v-legal d3v-legal-paia">
            <p>This manual is published in terms of section 51 of the Promotion of Access to Information Act 2 of 2000 (PAIA), read with section 32 of the Constitution of the Republic of South Africa, 1996, and the Protection of Personal Information Act 4 of 2013 (POPIA).</p>

            <p><strong>Right of access</strong></p>
            <p>PAIA gives every person a right of access to records held by a public or private body, subject to the grounds for refusal set out in PAIA.</p>

            <p><strong>Information Officer</strong></p>
            <p>The Information Officer is responsible for dealing with access-to-information requests:</p>
            <ul>
                <li>Name: <?php echo $officer ?: ($company ?: 'Information Officer'); ?></li>
                <?php if ($address) : ?><li>Address: <?php echo $address; ?></li><?php endif; ?>
                <?php if ($email) : ?><li>Email: <?php echo $email; ?></li><?php endif; ?>
                <?php if ($tel) : ?><li>Telephone: <?php echo $tel; ?></li><?php endif; ?>
            </ul>

            <p><strong>Records held</strong></p>
            <p><?php echo $company ?: 'We'; ?> hold records relating to, but not necessarily limited to:</p>
            <ul>
                <li>company records and statutory filings;</li>
                <li>customer, supplier and client information;</li>
                <li>financial, accounting and tax records;</li>
                <li>personnel and human-resources records;</li>
                <li>contracts and agreements;</li>
                <li>information technology and system records; and</li>
                <li>marketing and communication records.</li>
            </ul>

            <p><strong>Request procedure and form</strong></p>
            <p>Requests for access to records must be made on the prescribed PAIA application form and submitted to the Information Officer. The request must provide sufficient detail to identify the record requested and the requester’s preferred form of access.</p>

            <p><strong>Fees</strong></p>
            <p>A request fee and further access fees may be payable as prescribed by law. The Information Officer will inform you of any applicable fees before processing your request.</p>

            <p><strong>Decision period</strong></p>
            <p>The Information Officer will notify you of the decision on your request within the period prescribed by PAIA, normally 30 days, unless an extension applies.</p>

            <p><strong>Grounds for refusal</strong></p>
            <p>Access to certain records may be refused in terms of PAIA, including records that are protected by legal privilege, contain confidential commercial information, include third-party personal information, or whose disclosure could reasonably be expected to endanger the life or physical safety of an individual or prejudice law enforcement or national security.</p>

            <p><strong>Complaint to the Information Regulator</strong></p>
            <p>If you are dissatisfied with the decision on your request or the manner in which it was handled, you may lodge a complaint with the Information Regulator in terms of PAIA.</p>

            <?php if ($regno) : ?>
            <p><strong>Registration number</strong></p>
            <p><?php echo $regno; ?></p>
            <?php endif; ?>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full PAIA manual'); } ?>
        </div>
        <?php
    }

    /**
     * Standard copyright notice.
     *
     * Legal source: Copyright Act 98 of 1978.
     */
    function copyright($company, $policyurl = '') {
        $company   = esc_html($company);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-copyright" class="d3v-legal d3v-legal-copyright">
            <p>This website and its content are copyright of <?php echo $company ?: 'the owner'; ?> &copy; <?php echo date('Y'); ?>. All rights reserved.</p>
            <p>Unless otherwise indicated, all text, images, designs, graphics, logos, icons, audio, video, software and other material on this website are protected by copyright and other intellectual property laws.</p>
            <p>You may view, print or download content for your personal and non-commercial use only. Any redistribution, reproduction, transmission, storage or commercial exploitation of the content, in whole or in part, without the prior written permission of <?php echo $company ?: 'the owner'; ?> is prohibited.</p>
            <p>All third-party trademarks, logos and brands displayed on this website are the property of their respective owners and are used for identification purposes only.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full copyright notice'); } ?>
        </div>
        <?php
    }

    /**
     * Standard copyright footer notice.
     *
     * Legal source: Copyright Act 98 of 1978.
     */
    function copyright_footer($company, $policyurl = '') {
        $company   = esc_html($company);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-copyright-footer" class="d3v-legal d3v-legal-copyright-footer">
            <p>Copyright &copy; <?php echo date('Y'); ?> <?php echo $company ?: 'All rights reserved'; ?>. All rights reserved.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full copyright notice'); } ?>
        </div>
        <?php
    }

    /**
     * Website disclaimer.
     *
     * Legal sources: ECTA s93 (limitation of liability); common law principles
     * regarding misrepresentation, negligence and professional advice.
     */
    function disclaimer($company, $policyurl = '') {
        $company   = esc_html($company);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-disclaimer" class="d3v-legal d3v-legal-disclaimer">
            <p>The information contained on this website is provided for general information purposes only and does not constitute professional, legal, financial or other advice. <?php echo $company ?: 'We'; ?> make no representations or warranties of any kind, express or implied, about the accuracy, reliability, completeness, suitability or availability of the information, products, services or related graphics contained on this website.</p>

            <p>Any reliance you place on the information on this website is strictly at your own risk. To the fullest extent permitted by law, <?php echo $company ?: 'we'; ?> will not be liable for any loss or damage, including indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</p>

            <p>This website may contain links to third-party websites. <?php echo $company ?: 'We'; ?> have no control over the content of those websites and are not responsible for their content, privacy practices or availability. The inclusion of any link does not necessarily imply endorsement.</p>

            <p><?php echo $company ?: 'We'; ?> make every effort to keep this website available and functioning correctly. However, we take no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our reasonable control.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full disclaimer'); } ?>
        </div>
        <?php
    }

    /**
     * Email disclaimer.
     *
     * Legal sources: ECTA ss 11-16 (legal recognition of data messages, writing,
     * signature, retention); common law regarding confidentiality, privilege and
     * misdelivery.
     */
    function email_disclaimer($company, $address, $websiteurl, $policyurl = '') {
        $company    = esc_html($company);
        $address    = esc_html($address);
        $websiteurl = esc_html($websiteurl);
        $policyurl  = esc_url($policyurl);
        ?>
        <div id="d3v-legal-email-disclaimer" class="d3v-legal d3v-legal-email-disclaimer">
            <p><strong>Confidentiality and privilege</strong></p>
            <p>This email and any files transmitted with it are confidential and intended solely for the use of the individual or entity to whom they are addressed. This communication may be legally privileged or protected by other legal rules. If you have received this email in error, please notify the sender immediately and delete it from your system.</p>

            <p><strong>Misdelivery</strong></p>
            <p>Unauthorised use, disclosure, copying or distribution of this email or its contents is prohibited. The views expressed in this email are those of the sender and do not necessarily reflect the views of <?php echo $company ?: 'us'; ?>.</p>

            <p><strong>Virus check</strong></p>
            <p><?php echo $company ?: 'We'; ?> use reasonable efforts to ensure that this email and any attachments are free from viruses or other harmful content. However, we cannot accept responsibility for any loss or damage arising from the use of this email or its attachments. The recipient is responsible for checking this email and any attachments for viruses before opening them.</p>

            <p><strong>No binding agreement</strong></p>
            <p>No employee or agent of <?php echo $company ?: 'us'; ?> is authorised to conclude a binding agreement on behalf of the company by email without express written confirmation by an authorised representative.</p>

            <p><strong>Liability</strong></p>
            <p><?php echo $company ?: 'We'; ?> accept no liability for any loss or damage arising from the interception, corruption, delay or non-delivery of this email, or from any action taken in reliance on its contents.</p>

            <?php if ($company || $address || $websiteurl) : ?>
            <p><?php echo implode(', ', array_filter(array($company, $address, $websiteurl))); ?></p>
            <?php endif; ?>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full email disclaimer'); } ?>
        </div>
        <?php
    }

    /**
     * General website terms and conditions.
     *
     * Legal sources: ECTA ss 22 (formation and validity of agreements), 23 (time
     * and place of communications), 43 (information to be provided); CPA ss 49
     * (prohibition of unfair contract terms), 50 (consumer agreements in plain
     * language).
     */
    function tscs($company, $address, $policyurl = '') {
        $company   = esc_html($company);
        $address   = esc_html($address);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-tscs" class="d3v-legal d3v-legal-tscs">
            <p>Welcome to the <?php echo $company ?: 'our'; ?> website. By accessing and using this website, you agree to be bound by these terms and conditions. If you do not agree with any part of these terms, please do not use this website.</p>

            <p><strong>Definitions</strong></p>
            <p>“We”, “us” or “<?php echo $company ?: 'the owner'; ?>” refers to <?php echo $company ?: 'the website owner'; ?><?php if ($address) : ?>, whose registered/physical address is <?php echo $address; endif; ?>. “You” or “user” refers to any person who accesses or uses this website.</p>

            <p><strong>Use of the website</strong></p>
            <p>You agree to use this website only for lawful purposes and in a manner that does not infringe the rights of, or restrict or inhibit the use and enjoyment of this website by, any third party.</p>

            <p><strong>Intellectual property</strong></p>
            <p>All content on this website, including text, graphics, logos, images, software and code, is the property of <?php echo $company ?: 'us'; ?> or our licensors and is protected by copyright, trademark and other intellectual property laws. You may not reproduce, distribute, modify, transmit or otherwise use the content for commercial purposes without our prior written consent.</p>

            <p><strong>Prohibited use</strong></p>
            <p>You may not use this website in any way that causes, or may cause, damage to the website or impairment of its availability, or in any way that is unlawful, fraudulent, harmful or connected with any unlawful, fraudulent or harmful purpose or activity.</p>

            <p><strong>Links to third-party websites</strong></p>
            <p>This website may include links to third-party websites for your convenience. We do not endorse the content of those websites and are not responsible for their content, accuracy, privacy practices or availability.</p>

            <p><strong>Limitation of liability</strong></p>
            <p>To the fullest extent permitted by law, <?php echo $company ?: 'we'; ?> will not be liable for any direct, indirect, incidental, consequential or punitive damages arising out of or in connection with your use of this website or reliance on any information contained on it.</p>

            <p><strong>Governing law</strong></p>
            <p>These terms and conditions are governed by and construed in accordance with the laws of the Republic of South Africa. You submit to the non-exclusive jurisdiction of the South African courts.</p>

            <p><strong>Variation</strong></p>
            <p><?php echo $company ?: 'We'; ?> reserve the right to amend these terms and conditions at any time. The amended terms will be effective immediately upon publication on this website. Continued use of the website constitutes acceptance of the amended terms.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full terms and conditions'); } ?>
        </div>
        <?php
    }

    /**
     * Competition terms and conditions.
     *
     * Legal sources: CPA s36 (promotional competitions); Lotteries Act 57 of 1997;
     * common law relating to offers, acceptance and breach.
     */
    function comp_tscs($company, $email, $address, $tel, $social_media_platform, $policyurl = '') {
        $company               = esc_html($company);
        $email                 = esc_html($email);
        $address               = esc_html($address);
        $tel                   = esc_html($tel);
        $social_media_platform = esc_html($social_media_platform);
        $policyurl             = esc_url($policyurl);
        ?>
        <div id="d3v-legal-comp-tscs" class="d3v-legal d3v-legal-comp-tscs">
            <p>Please read these terms and conditions carefully. By entering this competition, you agree to be bound by these terms. If you do not agree, do not enter.</p>

            <p><strong>Promoter</strong></p>
            <p>The promoter is <?php echo $company ?: 'the promoter'; ?><?php if ($address || $tel || $email) : ?>, <?php echo implode(', ', array_filter(array($address, $tel, $email))); endif; ?>.</p>

            <p><strong>Eligibility</strong></p>
            <ul>
                <li>The competition is open to permanent residents of South Africa who are 18 years of age or older, unless otherwise stated.</li>
                <li>Directors, members, partners, employees, agents and consultants of the promoter, their immediate family members and any supplier directly involved with the competition are not eligible to enter, unless expressly permitted.</li>
            </ul>

            <p><strong>Entry</strong></p>
            <ul>
                <li>Entry is via the mechanism specified in the competition communication.</li>
                <li>Only one entry per person may be permitted unless otherwise stated.</li>
                <li>Entries received after the closing date and time will not be considered.</li>
                <li>The promoter reserves the right to disqualify any entry that does not comply with these terms or that is fraudulent or offensive.</li>
            </ul>

            <p><strong>Prizes</strong></p>
            <ul>
                <li>Prizes are as described in the competition communication and are not transferable or exchangeable for cash, unless otherwise stated.</li>
                <li>The promoter reserves the right to substitute a prize of equal or greater value if the advertised prize is unavailable.</li>
                <li>Prizes will be delivered or made available within a reasonable period after winner verification.</li>
            </ul>

            <p><strong>Winner selection and publicity</strong></p>
            <ul>
                <li>Winners will be selected in accordance with the selection criteria stated in the competition communication.</li>
                <li>The promoter will attempt to contact winners using the details provided. If a winner cannot be contacted within the period specified, the prize may be forfeited and an alternative winner selected.</li>
                <li>Winners may be required to provide identification and consent to the use of their name and image for publicity purposes.</li>
            </ul>

            <p><strong>Personal information</strong></p>
            <p>Personal information collected for the competition will be used to administer the competition, award prizes and communicate future promotions. By entering, you consent to this processing in accordance with our privacy policy.</p>

            <?php if ($social_media_platform) : ?>
            <p><strong>Social media platform release</strong></p>
            <p>This competition is in no way sponsored, endorsed or administered by, or associated with <?php echo $social_media_platform; ?>. By entering, you release <?php echo $social_media_platform; ?> from any and all liability relating to the competition.</p>
            <?php endif; ?>

            <p><strong>Errors and omissions</strong></p>
            <p>While every care has been taken in presenting this competition, the promoter accepts no responsibility for errors or omissions. The promoter reserves the right to cancel, amend or suspend the competition if circumstances arise beyond its reasonable control.</p>

            <p><strong>General provisions</strong></p>
            <ul>
                <li>These terms and conditions are governed by the laws of South Africa.</li>
                <li>If any provision is found to be invalid, the remaining provisions will continue in full force and effect.</li>
                <?php if ($email) : ?><li>A copy of these terms is available on request by emailing <?php echo $email; ?>.</li><?php endif; ?>
            </ul>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full competition terms and conditions'); } ?>
        </div>
        <?php
    }

    /**
     * Data administration / contact notice.
     *
     * Legal sources: POPIA s55 (appointment and responsibilities of Information
     * Officer); PAIA s51 (private-body manual and contact details).
     */
    function contact($company, $email, $policyurl = '') {
        $company   = esc_html($company);
        $email     = esc_html($email);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-contact" class="d3v-legal d3v-legal-contact">
            <p>If you have any questions about how <?php echo $company ?: 'we'; ?> process your personal information, wish to access or correct your information, or want to object to direct marketing, please contact us<?php if ($email) : ?> at <?php echo $email; endif; ?>.</p>
            <p>You may also contact our Information Officer to make a request in terms of PAIA or to lodge a complaint relating to the processing of your personal information.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full data administration notice'); } ?>
        </div>
        <?php
    }

    /**
     * Social media release statement.
     *
     * Legal sources: Common law release; POPIA s11 (consent as lawful basis for
     * processing).
     */
    function social_media_release($social_media_platform, $company, $policyurl = '') {
        $social_media_platform = esc_html($social_media_platform);
        $company               = esc_html($company);
        $policyurl             = esc_url($policyurl);
        ?>
        <div id="d3v-legal-smr" class="d3v-legal d3v-legal-smr">
            <p>This activity is in no way sponsored, endorsed or administered by, or associated with <?php echo $social_media_platform ?: 'the social media platform'; ?>.</p>
            <p>By participating, you agree to a complete release of <?php echo $social_media_platform ?: 'the social media platform'; ?> from any liability arising out of or relating to this activity. You understand that you are providing your information to <?php echo $company ?: 'us'; ?> and not to <?php echo $social_media_platform ?: 'the social media platform'; ?>.</p>
            <p>Any questions, comments or complaints about this activity should be directed to <?php echo $company ?: 'us'; ?> and not to <?php echo $social_media_platform ?: 'the social media platform'; ?>.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full social media release statement'); } ?>
        </div>
        <?php
    }

    /**
     * Social media netiquette.
     *
     * Legal sources: Common law relating to defamation, harassment and
     * intellectual property; POPIA s11 (consent); PEPUDA (equality and
     * non-discrimination).
     */
    function social_media_netiquette($social_media_platform, $company, $policyurl = '') {
        $social_media_platform = esc_html($social_media_platform);
        $company               = esc_html($company);
        $policyurl             = esc_url($policyurl);
        ?>
        <div id="d3v-legal-smn" class="d3v-legal d3v-legal-smn">
            <p>Welcome to the <?php echo $company ?: 'our'; ?> <?php echo $social_media_platform ?: 'social media'; ?> community. We encourage open, respectful and relevant conversation. By participating, you agree to the following guidelines.</p>

            <p><strong>Relevance</strong></p>
            <p>Please keep your contributions relevant to our business, products, services and community purpose. We may remove content that is off-topic, repetitive, promotional or otherwise irrelevant.</p>

            <p><strong>Respectful conduct</strong></p>
            <p>Do not post content that is unlawful, harmful, threatening, abusive, harassing, defamatory, hateful, discriminatory, obscene, fraudulent or misleading. Respect the privacy and rights of others.</p>

            <p><strong>Personal information</strong></p>
            <p>For your own privacy and safety, do not share personal information such as your identity number, home address, phone number or banking details in public posts or comments.</p>

            <p><strong>Intellectual property</strong></p>
            <p>Do not post content that infringes the copyright, trademark or other intellectual property rights of <?php echo $company ?: 'us'; ?> or any third party.</p>

            <p><strong>Moderation</strong></p>
            <p>We reserve the right to hide, edit or remove any content that breaches these guidelines or applicable law, and to suspend or ban users who repeatedly breach the rules. Our moderation decisions are final.</p>

            <p><strong>Not our views</strong></p>
            <p>Views expressed by community members are their own and do not necessarily reflect the views of <?php echo $company ?: 'us'; ?>. Likes, shares or other interactions do not constitute endorsement.</p>

            <p><strong>Contact</strong></p>
            <p>If you have questions about these guidelines or wish to report inappropriate conduct, please contact us via direct message on <?php echo $social_media_platform ?: 'the platform'; ?> or through our website.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full social media netiquette guidelines'); } ?>
        </div>
        <?php
    }

    /**
     * Returns and refunds policy.
     *
     * Legal sources: CPA ss 16 (right to fair and honest dealing), 17 (right to
     * fair, just and reasonable terms), 20 (right to safe, good-quality goods),
     * 44 (cooling-off period after direct marketing), 55 (implied warranty of
     * quality), 56 (remedy for unsafe/defective goods); ECTA ss 43(1)(n)
     * (return/exchange/refund policy disclosure), 44 (cooling-off period for
     * electronic transactions).
     */
    function returns_refunds($company, $email, $address, $returnwindow, $policyurl = '') {
        $company      = esc_html($company);
        $email        = esc_html($email);
        $address      = esc_html($address);
        $returnwindow = esc_html($returnwindow) ?: '30';
        $policyurl    = esc_url($policyurl);
        ?>
        <div id="d3v-legal-returns-refunds" class="d3v-legal d3v-legal-returns-refunds">
            <p>At <?php echo $company ?: 'we'; ?> want you to be satisfied with your purchase. This returns and refunds policy sets out your rights and our procedures.</p>

            <p><strong>CPA-implied warranty</strong></p>
            <p>In addition to any manufacturer warranty, the Consumer Protection Act gives you an implied warranty that goods are reasonably suitable for the purpose for which they are generally intended, are of good quality, free of defects and useable and durable for a reasonable period.</p>

            <p><strong>Defective or unsafe goods</strong></p>
            <?php if ($email) : ?>
            <p>If goods are defective, unsafe or not as described, please contact us at <?php echo $email; ?> within a reasonable time after discovering the defect. We will, at our discretion and where permitted by law, repair, replace or refund the goods.</p>
            <?php else : ?>
            <p>If goods are defective, unsafe or not as described, please contact us within a reasonable time after discovering the defect. We will, at our discretion and where permitted by law, repair, replace or refund the goods.</p>
            <?php endif; ?>

            <p><strong>Cooling-off for direct marketing</strong></p>
            <p>If you purchased goods as a result of direct marketing, you have the right to cancel the transaction within five business days after the date of purchase or delivery, whichever is later, for any reason. You will receive a full refund within the period prescribed by law.</p>

            <p><strong>Return window</strong></p>
            <p>Subject to the exclusions below, you may return goods within <?php echo $returnwindow; ?> days of delivery, provided the goods are unused, undamaged and in their original packaging with all tags and accessories.</p>

            <p><strong>Condition of returned goods</strong></p>
            <p>Returned goods must be in original condition. We reserve the right to refuse a refund or exchange if the goods show signs of use, damage or wear that is not due to a defect, or if original packaging, accessories or documentation are missing.</p>

            <p><strong>Refund method</strong></p>
            <p>Refunds will be processed using the original payment method or by electronic funds transfer, unless otherwise agreed. Refund processing times may vary depending on your payment provider.</p>

            <p><strong>Exclusions</strong></p>
            <p>Unless required by law, the following items may not be returned or refunded:</p>
            <ul>
                <li>perishable goods;</li>
                <li>personalised or custom-made goods;</li>
                <li>goods that are intimate or hygienic in nature;</li>
                <li>sealed audio recordings, video recordings or computer software that have been opened after delivery; and</li>
                <li>any other goods excluded by law or clearly identified as non-returnable at the time of purchase.</li>
            </ul>

            <?php if ($email || $address) : ?>
            <p><strong>How to return goods</strong></p>
            <p>To initiate a return, please contact us<?php if ($email) : ?> at <?php echo $email; endif; ?><?php if ($address) : ?> or write to us at <?php echo $address; endif; ?>. We will provide you with return instructions.</p>
            <?php endif; ?>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full returns and refunds policy'); } ?>
        </div>
        <?php
    }

    /**
     * Customer support notice.
     *
     * Legal sources: ECTA s43(1)(a)-(g) (supplier contact and description
     * requirements); CPA s33 (national consumer commission and complaints).
     */
    function customer_support($company, $email, $tel, $address, $policyurl = '') {
        $company   = esc_html($company);
        $email     = esc_html($email);
        $tel       = esc_html($tel);
        $address   = esc_html($address);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-customer-support" class="d3v-legal d3v-legal-customer-support">
            <p><?php echo $company ?: 'We'; ?> are committed to providing accessible and responsive customer support. Our support channels are listed below.</p>

            <p><strong>Contact channels</strong></p>
            <ul>
                <?php if ($email) : ?><li>Email: <?php echo $email; ?></li><?php endif; ?>
                <?php if ($tel) : ?><li>Telephone: <?php echo $tel; ?></li><?php endif; ?>
                <?php if ($address) : ?><li>Physical address: <?php echo $address; ?></li><?php endif; ?>
            </ul>

            <p><strong>Support hours</strong></p>
            <p>Our support team is available during normal South African business hours, excluding public holidays. Specific operating hours may be published on our website from time to time.</p>

            <p><strong>Response times</strong></p>
            <p>We aim to acknowledge support queries within one business day and to resolve standard queries within a reasonable time. Complex issues may require additional time, and we will keep you informed of progress.</p>

            <p><strong>Escalation</strong></p>
            <p>If you are dissatisfied with the handling of your query, you may request that it be escalated to a supervisor or manager. We will provide escalation contact details upon request.</p>

            <p><strong>Complaints</strong></p>
            <p>If your complaint is not resolved to your satisfaction, you may approach the relevant industry ombud or consumer tribunal in terms of the Consumer Protection Act.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full customer support notice'); } ?>
        </div>
        <?php
    }

    /**
     * Shipping and delivery notice.
     *
     * Legal sources: ECTA s46 (performance of electronic transactions); CPA s19
     * (delivery of goods and passing of risk).
     */
    function shipping_delivery($company, $email, $policyurl = '') {
        $company   = esc_html($company);
        $email     = esc_html($email);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-shipping-delivery" class="d3v-legal d3v-legal-shipping-delivery">
            <p><?php echo $company ?: 'We'; ?> provides delivery services to addresses within South Africa and, where indicated, to selected international destinations. Delivery areas and options are displayed during the checkout process.</p>

            <p><strong>Delivery timeframes</strong></p>
            <p>Estimated delivery timeframes are provided at checkout and are calculated from the date of payment confirmation. Under the Electronic Communications and Transactions Act, goods must generally be delivered within 30 days of purchase unless a different period is expressly agreed. We will notify you if unforeseen delays occur.</p>

            <p><strong>Risk and title</strong></p>
            <p>Risk in the goods passes to you upon delivery to the address provided. Title to the goods passes once full payment has been received and the goods have been delivered, unless otherwise agreed in writing.</p>

            <p><strong>Failed delivery</strong></p>
            <p>If delivery fails because you provided an incorrect address or were not available to receive the goods, we may charge an additional delivery fee or cancel the order in accordance with our terms and conditions.</p>

            <p><strong>Inspection on delivery</strong></p>
            <p>Please inspect goods on delivery and notify us of any damage, defect or discrepancy within a reasonable time. Failure to report visible damage promptly may affect your ability to claim a remedy.</p>

            <?php if ($email) : ?>
            <p><strong>Delivery queries</strong></p>
            <p>For delivery queries, please contact us at <?php echo $email; ?>.</p>
            <?php endif; ?>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full shipping and delivery notice'); } ?>
        </div>
        <?php
    }

    /**
     * Payment and security notice.
     *
     * Legal sources: ECTA s43(1)(j) (manner of payment disclosure), s43(5)
     * (secure payment systems); POPIA s19 (security safeguards); National
     * Payment System Act 78 of 1998.
     */
    function payment_security($company, $policyurl = '') {
        $company   = esc_html($company);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-payment-security" class="d3v-legal d3v-legal-payment-security">
            <p><?php echo $company ?: 'We'; ?> are committed to providing secure payment processing. This notice explains our payment practices and security measures.</p>

            <p><strong>Payment methods</strong></p>
            <p>We accept the payment methods displayed at checkout, which may include credit and debit cards, electronic funds transfer and approved third-party payment services.</p>

            <p><strong>Secure payment</strong></p>
            <p>Payment information is transmitted using industry-standard encryption and security technologies. Our payment processors are required to comply with the Payment Card Industry Data Security Standard (PCI-DSS) or equivalent applicable standards.</p>

            <p><strong>Currency</strong></p>
            <p>All transactions are processed in South African Rand (ZAR) unless another currency is expressly offered and accepted during checkout.</p>

            <p><strong>Privacy of payment information</strong></p>
            <p>We do not store your full credit card details on our servers unless you expressly consent to secure storage for future transactions. Where card details are stored, they are encrypted and protected in accordance with POPIA and PCI-DSS requirements.</p>

            <p><strong>Fraud prevention</strong></p>
            <p>We reserve the right to verify orders, request additional information and refuse transactions that appear fraudulent or unauthorised. We may report suspected fraud to the relevant authorities and payment networks.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full payment and security notice'); } ?>
        </div>
        <?php
    }

    /**
     * E-commerce terms and conditions.
     *
     * Legal sources: ECTA ss 22 (formation and validity of agreements), 23 (time
     * and place of communications), 43 (information to be provided), 44
     * (cooling-off period), 46 (performance); CPA ss 16, 17 (fair dealing),
     * 19 (delivery and passing of risk), 49 (unfair contract terms), 50 (plain
     * language), 55 (implied warranty), 56 (remedy for unsafe/defective goods).
     */
    function ecommerce_tscs($company, $address, $email, $tel, $vatno, $policyurl = '') {
        $company   = esc_html($company);
        $address   = esc_html($address);
        $email     = esc_html($email);
        $tel       = esc_html($tel);
        $vatno     = esc_html($vatno);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-ecommerce-tscs" class="d3v-legal d3v-legal-ecommerce-tscs">
            <p>These e-commerce terms and conditions govern the sale of goods and services by <?php echo $company ?: 'us'; ?> through our website. By placing an order, you agree to be bound by these terms.</p>

            <p><strong>Our details</strong></p>
            <ul>
                <li><?php echo $company ?: 'Supplier'; ?></li>
                <?php if ($address) : ?><li><?php echo $address; ?></li><?php endif; ?>
                <?php if ($email) : ?><li>Email: <?php echo $email; ?></li><?php endif; ?>
                <?php if ($tel) : ?><li>Telephone: <?php echo $tel; ?></li><?php endif; ?>
                <?php if ($vatno) : ?><li>VAT number: <?php echo $vatno; ?></li><?php endif; ?>
            </ul>

            <p><strong>Ordering process</strong></p>
            <p>Placing an item in your cart does not constitute an offer to purchase. An order is placed when you complete the checkout process and submit payment. We will confirm acceptance of your order by email.</p>

            <p><strong>Acceptance and rejection</strong></p>
            <p>We reserve the right to accept or reject any order for any reason, including product availability, errors in pricing or product descriptions, or suspected fraud. If we reject your order, we will refund any payment received.</p>

            <p><strong>Price and payment</strong></p>
            <p>Prices are displayed in South African Rand and include value-added tax where applicable. Payment must be made before goods are dispatched, unless a credit account has been approved in writing.</p>

            <p><strong>Delivery</strong></p>
            <p>We will deliver goods to the address provided during checkout within the timeframe communicated at the time of order, or within 30 days if no timeframe is specified, subject to ECTA and CPA requirements.</p>

            <p><strong>Risk and title</strong></p>
            <p>Risk passes on delivery. Title passes once full payment has been received and delivery has occurred, unless otherwise agreed in writing.</p>

            <p><strong>Returns and refunds</strong></p>
            <p>Our returns and refunds policy applies to all purchases made through this website. You may also have statutory rights under the Consumer Protection Act, including the cooling-off right for direct marketing sales.</p>

            <p><strong>Warranty</strong></p>
            <p>Goods are sold with the implied warranty of quality provided by the Consumer Protection Act and any applicable manufacturer warranty. Defective goods will be repaired, replaced or refunded in accordance with the CPA.</p>

            <p><strong>Limitation of liability</strong></p>
            <p>To the fullest extent permitted by law, <?php echo $company ?: 'we'; ?> will not be liable for indirect, consequential or special damages arising from the use of this website or the purchase of goods or services, except where liability cannot be excluded by law.</p>

            <p><strong>Governing law</strong></p>
            <p>These terms are governed by the laws of the Republic of South Africa. Any dispute arising from these terms or your purchase will be subject to the jurisdiction of the South African courts.</p>

            <p><strong>Variation</strong></p>
            <p>We may amend these terms from time to time. The terms in force at the time you place your order will apply to that order.</p>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full e-commerce terms and conditions'); } ?>
        </div>
        <?php
    }

    /**
     * Accessibility notice.
     *
     * Legal sources: Constitution s9 (equality); ECTA s2(l) (special needs of
     * particular communities and the disabled); Promotion of Equality and
     * Prevention of Unfair Discrimination Act 4 of 2000.
     */
    function accessibility($company, $email, $policyurl = '') {
        $company   = esc_html($company);
        $email     = esc_html($email);
        $policyurl = esc_url($policyurl);
        ?>
        <div id="d3v-legal-accessibility" class="d3v-legal d3v-legal-accessibility">
            <p><?php echo $company ?: 'We'; ?> are committed to ensuring that our website and digital services are accessible to as many people as possible, including persons with disabilities. We aim to comply with the equality and non-discrimination principles in the Constitution of the Republic of South Africa, 1996, and the Promotion of Equality and Prevention of Unfair Discrimination Act 4 of 2000.</p>

            <p><strong>Our commitment</strong></p>
            <p>We endeavour to present information in a clear and consistent manner, provide readable text, support keyboard navigation, and make reasonable adjustments to improve accessibility.</p>

            <p><strong>Alternative formats</strong></p>
            <p>If you require information from this website in an alternative format, such as larger print, plain language or another accessible format, please contact us and we will make reasonable efforts to assist you.</p>

            <?php if ($email) : ?>
            <p><strong>Feedback</strong></p>
            <p>We welcome feedback on how we can improve the accessibility of our website. Please contact us at <?php echo $email; ?> with your suggestions or to report any accessibility barriers.</p>
            <?php endif; ?>
            <?php if ($policyurl) { echo d3v_legal_policy_link($policyurl, 'Read our full accessibility statement'); } ?>
        </div>
        <?php
    }
}
