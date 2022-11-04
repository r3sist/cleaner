<?php declare(strict_types=1);

namespace resist\Cleaner\Tests;

use resist\Cleaner\Cleaner;
use PHPUnit\Framework\TestCase;

final class CleanerTest extends TestCase
{
    /**
     * @dataProvider getCleanerTestCases
     */
    public function testClean(string|int|float $input, array $enabledTags, string $output): void
    {
        $cleaner = new Cleaner();

        self::assertEquals($cleaner->clean($input, $enabledTags), $output);
    }

    public function getCleanerTestCases(): array
    {
        return [
            ['valid string', [], 'valid string'],
            ['valid string', ['strong'], 'valid string'],
            ['valid <strong>string</strong>', ['strong'], 'valid <strong>string</strong>'],
            ['valid <strong>string</strong><i>invalid</i>', ['strong', 'em'], 'valid <strong>string</strong>invalid'],
            [' trimmed ', [], 'trimmed'],
            [' valid <span class="has-class">string</span> ', [], 'valid string'],
            [' valid <span class="has-class">string</span> ', ['span'], 'valid <span class="has-class">string</span>'],
            ['<script>alert("alert");</script>', [''], 'alert("alert");'],
            ['<script><script>alert("alert");</script></script>', [''], 'alert("alert");'],
        ];
    }
}
