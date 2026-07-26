<?php
/**
 * Shared PHP renderer for D3V Legal Notices.
 *
 * This class is used by backend/platform adapters (Laravel, Symfony, Drupal,
 * Joomla, Magento, PrestaShop, OpenCart, etc.) to load the shared JSON legal
 * libraries and render notices to HTML. It has no framework dependencies and
 * requires only PHP 8.1+.
 */

declare(strict_types=1);

namespace D3vDigital\D3vLegal;

use DateTime;
use RuntimeException;

/**
 * Render jurisdiction-aware legal notices from shared JSON libraries.
 */
class D3vLegalRenderer
{
    /**
     * Fields accepted by the renderer.
     */
    public const KNOWN_FIELDS = [
        'notice',
        'country',
        'language',
        'company',
        'email',
        'support_email',
        'officer_email',
        'address',
        'tel',
        'smp',
        'websiteurl',
        'officer',
        'regno',
        'vatno',
        'returnwindow',
        'policyurl',
    ];

    /**
     * @var string Base directory of the consuming package/adapter.
     */
    private string $packageDir;

    /**
     * @param string $packageDir Absolute path to the adapter package root. In
     *                           development this is usually the platform folder
     *                           under src/. In a distribution package it is the
     *                           folder that contains legal-libraries/.
     */
    public function __construct(string $packageDir)
    {
        $real = realpath($packageDir);
        $this->packageDir = false !== $real ? $real : rtrim($packageDir, '/\\');
    }

    /**
     * Resolve effective attribute values from user-supplied props.
     *
     * @param array<string,string> $props
     * @return array<string,string>
     */
    public function resolveAtts(array $props): array
    {
        $defaults = [
            'notice'        => '',
            'country'       => '',
            'language'      => '',
            'company'       => '',
            'email'         => '',
            'support_email' => '',
            'officer_email' => '',
            'address'       => '',
            'tel'           => '',
            'smp'           => '',
            'websiteurl'    => '',
            'officer'       => '',
            'regno'         => '',
            'vatno'         => '',
            'returnwindow'  => '',
            'policyurl'     => '',
        ];

        $atts = $defaults;
        foreach ($props as $key => $value) {
            if (array_key_exists($key, $defaults)) {
                $atts[$key] = $this->sanitize((string) $value);
            }
        }

        if ('' === $atts['support_email'] && '' !== $atts['email']) {
            $atts['support_email'] = $atts['email'];
        }
        if ('' === $atts['officer_email'] && '' !== $atts['email']) {
            $atts['officer_email'] = $atts['email'];
        }
        if ('' === $atts['returnwindow']) {
            $atts['returnwindow'] = '30';
        }

        return $atts;
    }

    /**
     * Render a single notice by key.
     *
     * @param string               $noticeKey
     * @param array<string,string> $props
     * @return string Rendered HTML, or an empty string if the notice cannot be loaded.
     */
    public function render(string $noticeKey, array $props = []): string
    {
        $atts = $this->resolveAtts(array_merge(['notice' => $noticeKey], $props));

        $country  = strtoupper($atts['country']);
        $language = strtolower($atts['language']);

        $supportedCountries = $this->supportedCountries();
        if (! in_array($country, $supportedCountries, true)) {
            $country = $this->defaultCountry();
        }

        $supportedLanguages = $this->supportedLanguages($country);
        if (! in_array(strtoupper($language), $supportedLanguages, true)) {
            $language = strtolower($this->defaultLanguage($country));
        } else {
            $language = strtolower(strtoupper($language));
        }

        $library = $this->loadLibrary($country, $language);
        if (empty($library) || ! isset($library[$country]['notices'][$noticeKey])) {
            return '';
        }

        $notice = $library[$country]['notices'][$noticeKey];
        if (! is_array($notice)) {
            return '';
        }

        return $this->renderNotice($noticeKey, $notice, $atts, $country);
    }

    /**
     * Return the list of supported country ISO3 codes.
     *
     * @return string[]
     */
    public function supportedCountries(): array
    {
        $libraries = $this->discoverLibraries();
        $countries = array_keys($libraries);

        if (empty($countries)) {
            return ['ZAF'];
        }

        sort($countries);
        return $countries;
    }

    /**
     * Return supported language codes for a country.
     *
     * @return string[]
     */
    public function supportedLanguages(string $country): array
    {
        $libraries = $this->discoverLibraries();
        $country = strtoupper($this->sanitize($country));

        if (! isset($libraries[$country]) || empty($libraries[$country])) {
            return ['ENG'];
        }

        $languages = $libraries[$country];
        sort($languages);
        return $languages;
    }

    /**
     * Default country, preferring ZAF when available.
     */
    public function defaultCountry(): string
    {
        $supported = $this->supportedCountries();

        if (in_array('ZAF', $supported, true)) {
            return 'ZAF';
        }

        return $supported[0] ?? 'ZAF';
    }

    /**
     * Default language for a country, preferring ENG when available.
     */
    public function defaultLanguage(string $country): string
    {
        $supported = $this->supportedLanguages($country);

        if (in_array('ENG', $supported, true)) {
            return 'ENG';
        }

        return $supported[0] ?? 'ENG';
    }

    /**
     * Resolve the legal-libraries directory.
     *
     * In development the adapter lives under src/<platform>/ and the shared
     * libraries are two levels up at the repository root. In a distribution
     * package the libraries are bundled inside the package folder.
     */
    public function getLibraryDirectory(): string
    {
        $candidates = [
            $this->packageDir . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'legal-libraries',
            $this->packageDir . DIRECTORY_SEPARATOR . 'legal-libraries',
        ];

        foreach ($candidates as $dir) {
            $real = realpath($dir);
            if (false !== $real && is_dir($real)) {
                return $real . DIRECTORY_SEPARATOR;
            }
        }

        return $this->packageDir . DIRECTORY_SEPARATOR . 'legal-libraries' . DIRECTORY_SEPARATOR;
    }

    /**
     * Discover available library files.
     *
     * @return array<string,string[]>
     */
    public function discoverLibraries(): array
    {
        $dir = $this->getLibraryDirectory();
        $allowedBase = realpath($dir);
        if (false === $allowedBase) {
            return [];
        }

        $files = glob($dir . '*-legals.json');
        if (! is_array($files)) {
            return [];
        }

        $libraries = [];
        foreach ($files as $file) {
            $basename = basename((string) $file);
            if (! preg_match('/^([A-Za-z]{3})-([A-Za-z]+)-legals\.json$/', $basename, $matches)) {
                continue;
            }

            $realFile = realpath($file);
            if (false === $realFile) {
                continue;
            }

            if (0 !== strpos($realFile, $allowedBase . DIRECTORY_SEPARATOR)) {
                continue;
            }

            $country = strtoupper($matches[1]);
            $language = strtoupper($matches[2]);

            if (! isset($libraries[$country])) {
                $libraries[$country] = [];
            }

            if (! in_array($language, $libraries[$country], true)) {
                $libraries[$country][] = $language;
            }
        }

        return $libraries;
    }

    /**
     * Load a specific country/language library.
     *
     * @return array<string,mixed>|null
     */
    public function loadLibrary(string $country, string $language): ?array
    {
        $file = $this->getLibraryPath($country, $language);
        if ('' === $file) {
            return null;
        }

        $json = file_get_contents($file);
        if (false === $json) {
            return null;
        }

        $data = json_decode($json, true);
        if (! is_array($data) || JSON_ERROR_NONE !== json_last_error()) {
            return null;
        }

        return $data;
    }

    /**
     * Resolve the absolute path to a library file, case-insensitively.
     */
    public function getLibraryPath(string $country, string $language): string
    {
        $country = strtoupper($this->sanitize($country));
        $language = strtolower($this->sanitize($language));

        if (! preg_match('/^[A-Z]{3}$/', $country)) {
            return '';
        }

        if (! preg_match('/^[a-z]+$/', $language)) {
            return '';
        }

        $dir = $this->getLibraryDirectory();
        $allowedBase = realpath($dir);
        if (false === $allowedBase) {
            return '';
        }

        $expectedFile = $country . '-' . $language . '-legals.json';
        $files = glob($dir . '*-legals.json');
        if (! is_array($files)) {
            return '';
        }

        foreach ($files as $file) {
            $basename = basename((string) $file);
            if (strtoupper($basename) !== strtoupper($expectedFile)) {
                continue;
            }

            $realFile = realpath($file);
            if (false === $realFile) {
                continue;
            }

            if (0 !== strpos($realFile, $allowedBase . DIRECTORY_SEPARATOR)) {
                continue;
            }

            return $realFile;
        }

        return '';
    }

    /**
     * Render a notice definition to HTML.
     *
     * @param string              $noticeKey
     * @param array<string,mixed> $notice
     * @param array<string,string> $atts
     * @param string              $country
     */
    public function renderNotice(string $noticeKey, array $notice, array $atts, string $country): string
    {
        $id = 'd3v-legal-' . preg_replace('/[^a-z0-9-]+/', '-', strtolower($noticeKey));
        $html = '<div id="' . $this->escapeAttr($id) . '" class="d3v-legal d3v-legal-' . $this->escapeAttr(strtolower($noticeKey)) . '">';

        if (isset($notice['sections']) && is_array($notice['sections'])) {
            foreach ($notice['sections'] as $section) {
                if (! is_array($section)) {
                    continue;
                }

                if (! $this->sectionIsVisible($section, $atts)) {
                    continue;
                }

                $type = isset($section['type']) ? $this->sanitize((string) $section['type']) : 'paragraph';
                $html .= match ($type) {
                    'list'          => $this->renderListSection($section, $atts),
                    'contact_list'  => $this->renderContactListSection($section, $atts),
                    'joined_fields' => $this->renderJoinedFieldsSection($section, $atts),
                    'policy_link'   => $this->renderPolicyLinkSection($section, $atts),
                    default         => $this->renderParagraphSection($section, $atts, $country),
                };
            }
        }

        $html .= $this->renderLegalSources($notice['legal_sources'] ?? null);
        $html .= '</div>';

        return $html;
    }

    /**
     * @param array<string,mixed> $section
     * @param array<string,string> $atts
     */
    private function sectionIsVisible(array $section, array $atts): bool
    {
        if (! isset($section['condition'])) {
            return true;
        }

        $condition = $this->sanitize((string) $section['condition']);

        if ('any' === $condition) {
            if (! isset($section['fields']) || ! is_array($section['fields'])) {
                return true;
            }
            foreach ($section['fields'] as $field) {
                if (! is_array($field)) {
                    continue;
                }
                $template = isset($field['value']) ? (string) $field['value'] : '';
                if ($this->hasReplacementValue($template, $atts)) {
                    return true;
                }
            }
            return false;
        }

        return isset($atts[$condition]) && '' !== $atts[$condition];
    }

    private function hasReplacementValue(string $template, array $atts): bool
    {
        foreach ($this->extractPlaceholders($template) as $token) {
            if ('' !== $this->getPlaceholderValue($token, $atts)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string[]
     */
    private function extractPlaceholders(string $template): array
    {
        if (! preg_match_all('/\{\{([a-z0-9_]+)/i', $template, $matches)) {
            return [];
        }
        return array_unique($matches[1]);
    }

    private function getPlaceholderValue(string $key, array $atts): string
    {
        if ('year' === $key) {
            return (new DateTime())->format('Y');
        }

        return isset($atts[$key]) ? $this->sanitize((string) $atts[$key]) : '';
    }

    private function replacePlaceholders(string $template, array $atts): string
    {
        $rendered = $template;

        for ($i = 0; $i < 5; $i++) {
            $previous = $rendered;
            $rendered = (string) preg_replace_callback(
                '/\{\{([a-z0-9_]+)(?:\|\|([^}]*)?)?\}\}/i',
                function (array $matches) use ($atts): string {
                    $key = $matches[1];
                    $fallback = $matches[2] ?? '';
                    $value = $this->getPlaceholderValue($key, $atts);
                    return '' !== $value ? $this->escapeHtml($value) : $this->escapeHtml($fallback);
                },
                $rendered
            );

            if ($rendered === $previous) {
                break;
            }
        }

        return $rendered;
    }

    /**
     * @param array<string,mixed> $section
     * @param array<string,string> $atts
     */
    private function renderParagraphSection(array $section, array $atts, string $country): string
    {
        $html = '';

        if (isset($section['title']) && '' !== trim((string) $section['title'])) {
            $html .= '<p><strong>' . $this->escapeHtml((string) $section['title']) . '</strong></p>';
        }

        if (isset($section['template'])) {
            $html .= '<p>' . $this->replacePlaceholders((string) $section['template'], $atts) . '</p>';
        }

        return $html;
    }

    /**
     * @param array<string,mixed> $section
     * @param array<string,string> $atts
     */
    private function renderListSection(array $section, array $atts): string
    {
        $html = '';

        if (isset($section['title']) && '' !== trim((string) $section['title'])) {
            $html .= '<p><strong>' . $this->escapeHtml((string) $section['title']) . '</strong></p>';
        }

        if (isset($section['items']) && is_array($section['items'])) {
            $html .= '<ul>';
            foreach ($section['items'] as $item) {
                $html .= '<li>' . $this->replacePlaceholders((string) $item, $atts) . '</li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * @param array<string,mixed> $section
     * @param array<string,string> $atts
     */
    private function renderContactListSection(array $section, array $atts): string
    {
        $items = [];

        if (isset($section['fields']) && is_array($section['fields'])) {
            foreach ($section['fields'] as $field) {
                if (! is_array($field) || ! isset($field['value'])) {
                    continue;
                }

                if (isset($field['condition'])) {
                    $condition = $this->sanitize((string) $field['condition']);
                    if ('any' === $condition) {
                        if (! $this->hasReplacementValue((string) $field['value'], $atts)) {
                            continue;
                        }
                    } elseif (! isset($atts[$condition]) || '' === $atts[$condition]) {
                        continue;
                    }
                }

                $rendered = $this->replacePlaceholders((string) $field['value'], $atts);
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
            $html .= '<p><strong>' . $this->escapeHtml((string) $section['title']) . '</strong></p>';
        }

        $html .= '<ul>';
        foreach ($items as $item) {
            $html .= '<li>' . $item . '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * @param array<string,mixed> $section
     * @param array<string,string> $atts
     */
    private function renderJoinedFieldsSection(array $section, array $atts): string
    {
        $template = isset($section['template']) ? (string) $section['template'] : '';
        $separator = isset($section['separator']) ? (string) $section['separator'] : ', ';

        $parts = [];
        foreach ($this->extractPlaceholders($template) as $token) {
            $value = $this->getPlaceholderValue($token, $atts);
            if ('' !== $value) {
                $parts[] = $this->escapeHtml($value);
            }
        }

        if (empty($parts)) {
            return '';
        }

        $html = '';
        if (isset($section['title']) && '' !== trim((string) $section['title'])) {
            $html .= '<p><strong>' . $this->escapeHtml((string) $section['title']) . '</strong></p>';
        }

        $html .= '<p>' . implode($this->escapeHtml($separator), $parts) . '</p>';
        return $html;
    }

    /**
     * @param array<string,mixed> $section
     * @param array<string,string> $atts
     */
    private function renderPolicyLinkSection(array $section, array $atts): string
    {
        $policyurl = $this->escapeUrl($atts['policyurl'] ?? '');
        if ('' === $policyurl) {
            return '';
        }

        $label = isset($section['label']) ? (string) $section['label'] : 'Read full policy';
        return '<p><a href="' . $policyurl . '" target="_blank" rel="noopener noreferrer">' . $this->escapeHtml($label) . '</a></p>';
    }

    /**
     * @param string[]|null $sources
     */
    private function renderLegalSources(?array $sources): string
    {
        if (! is_array($sources) || empty($sources)) {
            return '';
        }

        $items = '';
        foreach ($sources as $source) {
            $items .= '<li>' . $this->escapeHtml((string) $source) . '</li>';
        }

        return '<details class="d3v-legal__sources"><summary>Legal sources</summary><ul>' . $items . '</ul></details>';
    }

    private function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function escapeAttr(string $text): string
    {
        return $this->escapeHtml($text);
    }

    private function escapeUrl(string $url): string
    {
        $url = trim($url);
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

    private function sanitize(string $value): string
    {
        return trim(strip_tags($value));
    }
}
