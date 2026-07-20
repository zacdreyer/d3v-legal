<?php
require_once dirname(__DIR__) . '/d3v-legal.php';

use PHPUnit\Framework\TestCase;

class TestPluginTest extends TestCase
{
    public function testShortcodeReturnsEmptyStringForUnknownNotice(): void
    {
        $output = d3v_legal_notices(array('notice' => 'unknown'));
        $this->assertSame('', $output);
    }

    public function testCookieNoticeContainsExpectedText(): void
    {
        $output = d3v_legal_notices(array('notice' => 'cookies', 'company' => 'Example Co'));
        $this->assertStringContainsString('cookies', strtolower($output));
        $this->assertStringContainsString('better website experience', strtolower($output));
    }
}
