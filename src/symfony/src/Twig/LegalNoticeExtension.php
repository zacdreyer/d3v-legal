<?php
/**
 * Twig extension exposing the D3V Legal Notices renderer.
 */

declare(strict_types=1);

namespace D3vDigital\D3vLegal\Symfony\Twig;

use D3vDigital\D3vLegal\D3vLegalRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LegalNoticeExtension extends AbstractExtension
{
    /**
     * Create the extension.
     */
    public function __construct(
        private D3vLegalRenderer $renderer,
    ) {
    }

    /**
     * Register Twig functions.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('d3v_legal_notice', [$this, 'd3vLegalNotice']),
        ];
    }

    /**
     * Render a D3V Legal notice.
     *
     * @param string               $notice  The notice key, e.g. "privacy".
     * @param array<string,string> $options Associative array of notice options
     *                                      (country, language, company, email,
     *                                      support_email, officer_email, address,
     *                                      tel, smp, websiteurl, officer, regno,
     *                                      vatno, returnwindow, policyurl).
     */
    public function d3vLegalNotice(string $notice, array $options = []): string
    {
        return $this->renderer->render($notice, $options);
    }
}
