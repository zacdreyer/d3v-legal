<?php

declare(strict_types=1);

namespace Drupal\d3v_legal\Plugin\Block;

use D3vDigital\D3vLegal\D3vLegalRenderer;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a block that renders a D3V legal notice.
 *
 * @Block(
 *   id = "d3v_legal_notice",
 *   admin_label = @Translation("D3V Legal Notice"),
 *   category = @Translation("D3V Digital")
 * )
 */
class LegalNoticeBlock extends BlockBase
{
    /**
     * Human-readable labels for the configuration fields.
     */
    private const FIELD_LABELS = [
        'notice'        => 'Notice key',
        'country'       => 'Country (ISO3)',
        'language'      => 'Language code',
        'company'       => 'Company name',
        'email'         => 'Email address',
        'support_email' => 'Support email',
        'officer_email' => 'Officer email',
        'address'       => 'Physical address',
        'tel'           => 'Telephone number',
        'smp'           => 'Social / messaging profile',
        'websiteurl'    => 'Website URL',
        'officer'       => 'Responsible officer',
        'regno'         => 'Registration number',
        'vatno'         => 'VAT number',
        'returnwindow'  => 'Return window (days)',
        'policyurl'     => 'Policy URL',
    ];

    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration(): array
    {
        $config = [];

        foreach (D3vLegalRenderer::KNOWN_FIELDS as $field) {
            $config[$field] = '';
        }

        $config['notice']       = 'privacy';
        $config['country']      = 'ZAF';
        $config['language']     = 'ENG';
        $config['returnwindow'] = '30';

        return $config + parent::defaultConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state): array
    {
        $form = parent::blockForm($form, $form_state);
        $config = $this->configuration;

        foreach (D3vLegalRenderer::KNOWN_FIELDS as $field) {
            $form[$field] = [
                '#type'          => 'textfield',
                '#title'         => $this->t(self::FIELD_LABELS[$field] ?? ucfirst(str_replace('_', ' ', $field))),
                '#default_value' => $config[$field] ?? '',
                '#maxlength'     => 512,
            ];
        }

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state): void
    {
        parent::blockSubmit($form, $form_state);

        foreach (D3vLegalRenderer::KNOWN_FIELDS as $field) {
            $this->configuration[$field] = (string) $form_state->getValue($field);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function build(): array
    {
        $config = $this->configuration;
        $notice = $config['notice'] ?? 'privacy';

        // The block class lives in <module>/src/Plugin/Block. Four levels up
        // points to the module root, which is what the renderer expects.
        $module_path = dirname(__DIR__, 4);
        $renderer = new D3vLegalRenderer($module_path);

        $html = $renderer->render($notice, $config);

        return [
            '#type'     => 'inline_template',
            '#template' => '{{ html|raw }}',
            '#context'  => ['html' => $html],
            '#cache'    => ['contexts' => ['url.path']],
        ];
    }
}
