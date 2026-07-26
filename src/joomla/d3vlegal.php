<?php
/**
 * @package     D3V Legal Notices
 * @subpackage  Content - D3V Legal
 * @version     2026.07.30
 * @author      Zac Dreyer
 * @license     GPL-2.0-or-later
 *
 * Content plugin that replaces {d3v-legal notice="privacy" company="..."} tags
 * with jurisdiction-aware legal notices rendered by D3vLegalRenderer.
 */

declare(strict_types=1);

defined('_JEXEC') or die;

use D3vDigital\D3vLegal\D3vLegalRenderer;

require_once __DIR__ . '/D3vLegalRenderer.php';

if (! class_exists('JPlugin')) {
    if (class_exists('Joomla\\CMS\\Plugin\\CMSPlugin')) {
        class_alias('Joomla\\CMS\\Plugin\\CMSPlugin', 'JPlugin');
    }
}

if (! class_exists('Joomla\\CMS\\Plugin\\CMSPlugin') && ! class_exists('JPlugin')) {
    /**
     * Minimal stub so the class can be syntax-checked outside Joomla.
     */
    class JPlugin
    {
        public function __construct($subject = null, $config = null)
        {
        }
    }
}

/**
 * Content plugin for D3V Legal Notices.
 */
class PlgContentD3vLegal extends JPlugin
{
    /**
     * @var D3vLegalRenderer
     */
    private D3vLegalRenderer $renderer;

    /**
     * Constructor.
     *
     * @param object $subject The plugin dispatcher.
     * @param array  $config  Plugin configuration.
     */
    public function __construct($subject = null, $config = null)
    {
        parent::__construct($subject, $config);
        $this->renderer = new D3vLegalRenderer(__DIR__);
    }

    /**
     * Handle content preparation and replace {d3v-legal ...} tags.
     *
     * @param string  $context  The context of the content being passed.
     * @param object  $article  The content object. Passed by reference.
     * @param object  $params   The content params. Passed by reference.
     * @param integer $page     Optional page number.
     * @return void
     */
    public function onContentPrepare($context, &$article, &$params, $page = 0): void
    {
        if (empty($article->text)) {
            return;
        }

        $article->text = (string) preg_replace_callback(
            '/\{d3v-legal\s+([^}]*)\}/i',
            function (array $matches): string {
                $attributes = $this->parseAttributes($matches[1]);
                $notice = $attributes['notice'] ?? '';
                if ('' === $notice) {
                    return '';
                }

                return $this->renderer->render($notice, $attributes);
            },
            $article->text
        );
    }

    /**
     * Parse key="value" / key='value' attributes from a tag body.
     *
     * @param string $body Raw attribute string.
     * @return array<string,string>
     */
    private function parseAttributes(string $body): array
    {
        $attributes = [];
        if ('' === $body) {
            return $attributes;
        }

        preg_match_all(
            '/([a-zA-Z0-9_]+)\s*=\s*(["\'])(.*?)\2/s',
            $body,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $key = strtolower($match[1]);
            $attributes[$key] = $match[3];
        }

        return $attributes;
    }
}
