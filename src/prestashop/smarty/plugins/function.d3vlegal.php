<?php
/**
 * Smarty plugin for the D3V Legal Notices PrestaShop module.
 *
 * Type:     function
 * Name:     d3vlegal
 * Purpose:  Render a jurisdiction-aware legal notice from the shared libraries.
 * Example:
 *   {d3vlegal notice='privacy' company='Acme Inc.' email='legal@example.com' country='ZAF' language='ENG'}
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function smarty_function_d3vlegal(array $params, $smarty): string
{
    $module = Module::getInstanceByName('d3vlegal');
    if (! $module instanceof D3vlegal) {
        return '';
    }

    return $module->render($params);
}
