<?php
require_once dirname(__DIR__) . '/d3v-legal.php';

use PHPUnit\Framework\TestCase;

class TestPluginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        unset($GLOBALS['d3v_legal_test_settings']);
    }

    public function testShortcodeReturnsEmptyStringForUnknownNotice(): void
    {
        $output = d3v_legal_notices(array('notice' => 'unknown'));
        $this->assertSame('', $output);
    }

    public function testCookieNoticeContainsExpectedText(): void
    {
        $output = d3v_legal_notices(array('notice' => 'cookies', 'company' => 'Example Co'));
        $this->assertStringContainsString('cookies', strtolower($output));
        $this->assertStringContainsString('tracking technologies', strtolower($output));
        $this->assertStringContainsString('Example Co', $output);
    }

    public function testPrivacyNoticeContainsPopiaText(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'privacy',
            'company' => 'Example Co',
            'email' => 'privacy@example.co.za',
        ));
        $this->assertStringContainsString('responsible party', strtolower($output));
        $this->assertStringContainsString('Information Regulator', $output);
        $this->assertStringContainsString('privacy@example.co.za', $output);
    }

    public function testPaiaNoticeContainsManualText(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'paia',
            'company' => 'Example Co',
            'officer' => 'Jane Doe',
            'regno' => '1999/123456/07',
        ));
        $this->assertStringContainsString('PAIA', $output);
        $this->assertStringContainsString('Jane Doe', $output);
        $this->assertStringContainsString('1999/123456/07', $output);
    }

    public function testReturnsNoticeContainsReturnWindow(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'returns',
            'company' => 'Example Co',
            'returnwindow' => '14',
        ));
        $this->assertStringContainsString('14 days of delivery', $output);
        $this->assertStringContainsString('Consumer Protection Act', $output);
    }

    public function testEcommerceTscsNoticeContainsSupplierDetails(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'ecomtscs',
            'company' => 'Example Co',
            'vatno' => '4567890123',
            'email' => 'orders@example.co.za',
        ));
        $this->assertStringContainsString('VAT number', $output);
        $this->assertStringContainsString('4567890123', $output);
        $this->assertStringContainsString('orders@example.co.za', $output);
    }

    public function testShippingNoticeContainsEctaThirtyDayDefault(): void
    {
        $output = d3v_legal_notices(array('notice' => 'shipping'));
        $this->assertStringContainsString('30 days', $output);
    }

    public function testPaymentNoticeContainsPciDss(): void
    {
        $output = d3v_legal_notices(array('notice' => 'payments'));
        $this->assertStringContainsString('PCI-DSS', $output);
    }

    public function testSupportNoticeContainsContactChannels(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'support',
            'email' => 'help@example.co.za',
            'tel' => '011 123 4567',
        ));
        $this->assertStringContainsString('help@example.co.za', $output);
        $this->assertStringContainsString('011 123 4567', $output);
    }

    public function testAccessibilityNoticeContainsEqualityText(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'accessibility',
            'company' => 'Example Co',
        ));
        $this->assertStringContainsString('disabilities', strtolower($output));
        $this->assertStringContainsString('Promotion of Equality and Prevention of Unfair Discrimination Act', $output);
    }

    public function testUkCookieNoticeContainsGdprText(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'company' => 'Example Co',
            'country' => 'GBR',
        ));
        $this->assertStringContainsString('UK GDPR', $output);
        $this->assertStringContainsString('cookie banner', strtolower($output));
    }

    public function testUkPrivacyNoticeContainsIco(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'privacy',
            'company' => 'Example Co',
            'country' => 'GBR',
        ));
        $this->assertStringContainsString('Information Commissioner\'s Office', $output);
        $this->assertStringContainsString('data controller', strtolower($output));
    }

    public function testCountryAttributeIsCaseInsensitive(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'country' => 'gbr',
        ));
        $this->assertStringContainsString('UK GDPR', $output);
    }

    public function testDefaultCountryIsZafWhenNoBackendSetting(): void
    {
        $output = d3v_legal_notices(array('notice' => 'cookies'));
        $this->assertStringContainsString('POPIA', $output);
    }

    public function testBackendDefaultCountryIsUsedWhenNoCountryAttribute(): void
    {
        $GLOBALS['d3v_legal_test_settings'] = array('default_country' => 'GBR');
        $output = d3v_legal_notices(array('notice' => 'cookies'));
        $this->assertStringContainsString('UK GDPR', $output);
    }

    public function testShortcodeCountryAttributeOverridesBackendDefault(): void
    {
        $GLOBALS['d3v_legal_test_settings'] = array('default_country' => 'GBR');
        $output = d3v_legal_notices(array('notice' => 'cookies', 'country' => 'ZAF'));
        $this->assertStringContainsString('POPIA', $output);
        $this->assertStringNotContainsString('UK GDPR', $output);
    }

    public function testLanguageAttributeOverridesBackendDefault(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'country' => 'ZAF',
            'language' => 'AFR',
        ));
        $this->assertStringContainsString('koekies', strtolower($output));
        $this->assertStringContainsString('POPIA', $output);
        $this->assertStringNotContainsString('tracking technologies', strtolower($output));
    }

    public function testUnsupportedLanguageFallsBackToDefault(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'country' => 'ZAF',
            'language' => 'FRE',
        ));
        $this->assertStringContainsString('tracking technologies', strtolower($output));
    }

    public function testLanguageAttributeIsCaseInsensitive(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'country' => 'ZAF',
            'language' => 'afr',
        ));
        $this->assertStringContainsString('koekies', strtolower($output));
    }

    public function testDynamicValuesAreEscapedInOutput(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'contact',
            'company' => 'Example & "quoted"',
            'email' => 'demo@example.com',
        ));

        $this->assertStringContainsString('Example &amp; &quot;quoted&quot;', $output);
        $this->assertStringNotContainsString('Example & "quoted"', $output);
    }

    public function testPolicyUrlRendersLinkInCookieNotice(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'company' => 'Example Co',
            'policyurl' => 'https://example.co.za/cookie-policy',
        ));

        $this->assertStringContainsString('href="https://example.co.za/cookie-policy"', $output);
        $this->assertStringContainsString('Read our full cookie policy', $output);
    }

    public function testPolicyUrlRendersLinkInPrivacyNotice(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'privacy',
            'company' => 'Example Co',
            'policyurl' => 'https://example.co.za/privacy-policy',
        ));

        $this->assertStringContainsString('href="https://example.co.za/privacy-policy"', $output);
        $this->assertStringContainsString('Read our full privacy policy', $output);
    }

    public function testPolicyUrlWithoutProtocolGetsHttpsPrefix(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'policyurl' => 'example.co.za/cookie-policy',
        ));

        $this->assertStringContainsString('href="https://example.co.za/cookie-policy"', $output);
    }

    public function testPolicyUrlIsEscapedInOutput(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'cookies',
            'policyurl' => 'https://example.co.za/?a=1&b=2',
        ));

        $this->assertStringContainsString('href="https://example.co.za/?a=1&amp;b=2"', $output);
    }

    public function testShortcodeValueTakesPriorityOverBackendDefault(): void
    {
        $GLOBALS['d3v_legal_test_settings'] = array('company' => 'Backend Co');
        $output = d3v_legal_notices(array('notice' => 'cookies', 'company' => 'Example Co'));

        $this->assertStringContainsString('Example Co', $output);
        $this->assertStringNotContainsString('Backend Co', $output);
    }

    public function testBackendDefaultUsedWhenShortcodeValueMissing(): void
    {
        $GLOBALS['d3v_legal_test_settings'] = array('company' => 'Backend Co');
        $output = d3v_legal_notices(array('notice' => 'cookies'));

        $this->assertStringContainsString('Backend Co', $output);
    }

    public function testBackendReturnWindowFallsBackWhenNotInShortcode(): void
    {
        $GLOBALS['d3v_legal_test_settings'] = array('returnwindow' => '14');
        $output = d3v_legal_notices(array('notice' => 'returns'));

        $this->assertStringContainsString('14 days of delivery', $output);
    }

    public function testIncludingPluginTwiceDoesNotFatal(): void
    {
        $pluginPath = dirname(__DIR__) . '/d3v-legal.php';
        $command = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg("include '" . $pluginPath . "'; include '" . $pluginPath . "'; echo 'ok';");
        $output = shell_exec($command);

        $this->assertSame('ok', trim((string) $output));
    }

    public function testSupportEmailIsUsedInSupportNoticeWhenProvided(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'support',
            'email' => 'general@example.com',
            'support_email' => 'help@example.com',
        ));

        $this->assertStringContainsString('help@example.com', $output);
        $this->assertStringNotContainsString('general@example.com', $output);
    }

    public function testSupportNoticeFallsBackToGeneralEmailWhenSupportEmailMissing(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'support',
            'email' => 'general@example.com',
        ));

        $this->assertStringContainsString('general@example.com', $output);
    }

    public function testOfficerEmailIsUsedInPrivacyNoticeWhenProvided(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'privacy',
            'email' => 'general@example.com',
            'officer_email' => 'dpo@example.com',
        ));

        $this->assertStringContainsString('dpo@example.com', $output);
        $this->assertStringNotContainsString('general@example.com', $output);
    }

    public function testPrivacyNoticeFallsBackToGeneralEmailWhenOfficerEmailMissing(): void
    {
        $output = d3v_legal_notices(array(
            'notice' => 'privacy',
            'email' => 'general@example.com',
        ));

        $this->assertStringContainsString('general@example.com', $output);
    }
}
