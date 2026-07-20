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

    public function testIncludingPluginTwiceDoesNotFatal(): void
    {
        $pluginPath = dirname(__DIR__) . '/d3v-legal.php';
        $command = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg("include '" . $pluginPath . "'; include '" . $pluginPath . "'; echo 'ok';");
        $output = shell_exec($command);

        $this->assertSame('ok', trim((string) $output));
    }
}
