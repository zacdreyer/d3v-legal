<?php
/**
 * DependencyInjection extension for D3V Legal Notices.
 *
 * Registers the shared renderer and Twig extension as services.
 */

declare(strict_types=1);

namespace D3vDigital\D3vLegal\Symfony\DependencyInjection;

use D3vDigital\D3vLegal\D3vLegalRenderer;
use D3vDigital\D3vLegal\Symfony\Twig\LegalNoticeExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class D3vLegalExtension extends Extension
{
    /**
     * Load bundle services into the container.
     *
     * @param array<string,mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // The bundle root is two directories above this file.
        // From there the renderer discovers legal-libraries/ in development
        // (../../legal-libraries relative to src/symfony/) or inside the
        // distribution package (legal-libraries/ inside the bundle root).
        $packageDir = dirname(__DIR__, 2);
        $container->setParameter('d3v_legal.package_dir', $packageDir);

        $container
            ->register(D3vLegalRenderer::class, D3vLegalRenderer::class)
            ->setPublic(true)
            ->setArgument(0, '%d3v_legal.package_dir%');

        $container
            ->register(LegalNoticeExtension::class, LegalNoticeExtension::class)
            ->setPublic(false)
            ->addArgument(D3vLegalRenderer::class)
            ->addTag('twig.extension');
    }
}
