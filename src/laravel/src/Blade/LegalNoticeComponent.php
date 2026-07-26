<?php
/**
 * Blade component for rendering a D3V Legal notice.
 */

declare(strict_types=1);

namespace D3vDigital\D3vLegal\Laravel\Blade;

use D3vDigital\D3vLegal\D3vLegalRenderer;
use Illuminate\View\Component;

class LegalNoticeComponent extends Component
{
    /**
     * Create a new legal notice component instance.
     */
    public function __construct(
        private D3vLegalRenderer $renderer,
        public string $notice = '',
        public string $country = '',
        public string $language = '',
        public string $company = '',
        public string $email = '',
        public string $support_email = '',
        public string $officer_email = '',
        public string $address = '',
        public string $tel = '',
        public string $smp = '',
        public string $websiteurl = '',
        public string $officer = '',
        public string $regno = '',
        public string $vatno = '',
        public string $returnwindow = '',
        public string $policyurl = '',
    ) {
    }

    /**
     * Render the notice as HTML.
     */
    public function render(): string
    {
        $config = function_exists('config') ? config('d3v-legal', []) : [];
        $business = isset($config['business']) && is_array($config['business'])
            ? $config['business']
            : [];

        $props = [
            'notice'        => $this->notice,
            'country'       => $this->country ?: (string) ($business['country'] ?? ($config['country'] ?? '')),
            'language'      => $this->language ?: (string) ($business['language'] ?? ($config['language'] ?? '')),
            'company'       => $this->company ?: (string) ($business['company'] ?? ''),
            'email'         => $this->email ?: (string) ($business['email'] ?? ''),
            'support_email' => $this->support_email ?: (string) ($business['support_email'] ?? ''),
            'officer_email' => $this->officer_email ?: (string) ($business['officer_email'] ?? ''),
            'address'       => $this->address ?: (string) ($business['address'] ?? ''),
            'tel'           => $this->tel ?: (string) ($business['tel'] ?? ''),
            'smp'           => $this->smp ?: (string) ($business['smp'] ?? ''),
            'websiteurl'    => $this->websiteurl ?: (string) ($business['websiteurl'] ?? ''),
            'officer'       => $this->officer ?: (string) ($business['officer'] ?? ''),
            'regno'         => $this->regno ?: (string) ($business['regno'] ?? ''),
            'vatno'         => $this->vatno ?: (string) ($business['vatno'] ?? ''),
            'returnwindow'  => $this->returnwindow ?: (string) ($business['returnwindow'] ?? ''),
            'policyurl'     => $this->policyurl ?: (string) ($business['policyurl'] ?? ''),
        ];

        return $this->renderer->render($this->notice, $props);
    }
}
