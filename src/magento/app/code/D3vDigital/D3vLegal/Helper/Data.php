<?php
/**
 * Helper exposing the shared D3V Legal renderer.
 */

declare(strict_types=1);

namespace D3vDigital\D3vLegal\Helper;

use D3vDigital\D3vLegal\D3vLegalRenderer;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Return a configured renderer instance.
     *
     * The package directory is resolved to the module package root so the
     * renderer can locate the shared legal-libraries folder (either bundled in
     * the package or at the repository root during development).
     */
    public function getRenderer(): D3vLegalRenderer
    {
        return new D3vLegalRenderer(__DIR__ . '/../../../../../');
    }
}
