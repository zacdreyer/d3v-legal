<?php
/**
 * D3V Legal Notices – PrestaShop module adapter.
 *
 * @author    Zac Dreyer
 * @copyright Copyright (c) 2026 Zac Dreyer
 * @license   GPL-2.0-or-later
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/D3vLegalRenderer.php';

class D3vlegal extends Module
{
    /** @var \D3vDigital\D3vLegal\D3vLegalRenderer */
    private $renderer;

    public function __construct()
    {
        $this->name = 'd3vlegal';
        $this->tab = 'front_office_features';
        $this->version = '2026.07.30';
        $this->author = 'Zac Dreyer';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '9.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('D3V Legal Notices');
        $this->description = $this->l('Render jurisdiction-aware legal notices in your PrestaShop storefront.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall D3V Legal Notices?');

        $this->renderer = new \D3vDigital\D3vLegal\D3vLegalRenderer(__DIR__);
    }

    public function install(): bool
    {
        return parent::install() && $this->registerHook('displayFooter');
    }

    public function uninstall(): bool
    {
        return parent::uninstall();
    }

    public function hookDisplayFooter(array $params): string
    {
        return $this->render($params);
    }

    /**
     * Render a legal notice.
     *
     * @param array<string, mixed> $params Must contain a "notice" key; other
     *                                     accepted keys are defined by
     *                                     D3vLegalRenderer::KNOWN_FIELDS.
     * @return string Rendered HTML.
     */
    public function render(array $params): string
    {
        if (empty($params['notice']) || ! is_string($params['notice'])) {
            return '';
        }

        $props = [];
        foreach (\D3vDigital\D3vLegal\D3vLegalRenderer::KNOWN_FIELDS as $field) {
            if (isset($params[$field])) {
                $props[$field] = (string) $params[$field];
            }
        }

        return $this->renderer->render($params['notice'], $props);
    }
}
