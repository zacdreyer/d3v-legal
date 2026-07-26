<?php
/**
 * Block that renders a jurisdiction-aware D3V Legal notice.
 */

declare(strict_types=1);

namespace D3vDigital\D3vLegal\Block;

use D3vDigital\D3vLegal\D3vLegalRenderer;
use D3vDigital\D3vLegal\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class LegalNotice extends Template
{
    /**
     * @var Data
     */
    protected Data $helper;

    /**
     * @var string
     */
    protected $_template = 'D3vDigital_D3vLegal::legal_notice.phtml';

    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function setNotice(string $value): self
    {
        return $this->setData('notice', $value);
    }

    public function getNotice(): string
    {
        return (string) $this->getData('notice');
    }

    public function setCountry(string $value): self
    {
        return $this->setData('country', $value);
    }

    public function getCountry(): string
    {
        return (string) $this->getData('country');
    }

    public function setLanguage(string $value): self
    {
        return $this->setData('language', $value);
    }

    public function getLanguage(): string
    {
        return (string) $this->getData('language');
    }

    public function setCompany(string $value): self
    {
        return $this->setData('company', $value);
    }

    public function getCompany(): string
    {
        return (string) $this->getData('company');
    }

    public function setEmail(string $value): self
    {
        return $this->setData('email', $value);
    }

    public function getEmail(): string
    {
        return (string) $this->getData('email');
    }

    public function setSupportEmail(string $value): self
    {
        return $this->setData('support_email', $value);
    }

    public function getSupportEmail(): string
    {
        return (string) $this->getData('support_email');
    }

    public function setOfficerEmail(string $value): self
    {
        return $this->setData('officer_email', $value);
    }

    public function getOfficerEmail(): string
    {
        return (string) $this->getData('officer_email');
    }

    public function setAddress(string $value): self
    {
        return $this->setData('address', $value);
    }

    public function getAddress(): string
    {
        return (string) $this->getData('address');
    }

    public function setTel(string $value): self
    {
        return $this->setData('tel', $value);
    }

    public function getTel(): string
    {
        return (string) $this->getData('tel');
    }

    public function setSmp(string $value): self
    {
        return $this->setData('smp', $value);
    }

    public function getSmp(): string
    {
        return (string) $this->getData('smp');
    }

    public function setWebsiteurl(string $value): self
    {
        return $this->setData('websiteurl', $value);
    }

    public function getWebsiteurl(): string
    {
        return (string) $this->getData('websiteurl');
    }

    public function setOfficer(string $value): self
    {
        return $this->setData('officer', $value);
    }

    public function getOfficer(): string
    {
        return (string) $this->getData('officer');
    }

    public function setRegno(string $value): self
    {
        return $this->setData('regno', $value);
    }

    public function getRegno(): string
    {
        return (string) $this->getData('regno');
    }

    public function setVatno(string $value): self
    {
        return $this->setData('vatno', $value);
    }

    public function getVatno(): string
    {
        return (string) $this->getData('vatno');
    }

    public function setReturnwindow(string $value): self
    {
        return $this->setData('returnwindow', $value);
    }

    public function getReturnwindow(): string
    {
        return (string) $this->getData('returnwindow');
    }

    public function setPolicyurl(string $value): self
    {
        return $this->setData('policyurl', $value);
    }

    public function getPolicyurl(): string
    {
        return (string) $this->getData('policyurl');
    }

    /**
     * Render the configured legal notice to HTML.
     */
    public function getHtml(): string
    {
        $renderer = $this->helper->getRenderer();

        return $renderer->render($this->getNotice(), $this->getRenderAttributes());
    }

    /**
     * Collect non-empty renderer attributes from block data.
     *
     * @return array<string, string>
     */
    protected function getRenderAttributes(): array
    {
        $attributes = [];

        foreach (D3vLegalRenderer::KNOWN_FIELDS as $field) {
            if ('notice' === $field) {
                continue;
            }

            $value = $this->getData($field);
            if (null === $value || '' === (string) $value) {
                continue;
            }

            $attributes[$field] = (string) $value;
        }

        return $attributes;
    }
}
