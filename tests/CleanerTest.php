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

    /**
     * @dataProvider getSlugTestCases
     */
    public function testSlug(string|int|float $string, string $allowedChars, string $replacement, bool $lowercase, string $output): void
    {
        $cleaner = new Cleaner();

        self::assertEquals($cleaner->slug($string, $allowedChars, $replacement, $lowercase), $output);
    }

    /**
     * @return array[] string, allowed, replacement, toLowercase, expected
     */
    public function getSlugTestCases(): array
    {
        return [
            ['valid string', '', '-', true, 'valid-string'],
            ['valid string', ' ', '-', true, 'valid string'],
            ['@valid string?', ' @', '_', false, '@valid string_'],
            ['@ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP', '', '-', true, 'arvizturotukorfurogep'],
            ['ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP?', '', '-', true, 'arvizturotukorfurogep'],
            ['Á@RVÍZTŰRŐTÜKÖRFÚRÓGÉP', '', '-', true, 'a-rvizturotukorfurogep'],
            ['ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP', '', '-', false, 'ARVIZTUROTUKORFUROGEP'],
            ['@Á:RVÍZTŰRŐTÜKÖRFÚRÓGÉP', 'ÁÍŰŐÜÖÚÓÉ', '-', false, 'Á-RVÍZTŰRŐTÜKÖRFÚRÓGÉP'],
            [' Trimmed String With Special Character_ ', '', '-', true, 'trimmed-string-with-special-character'],
            [' Trimmed String With Special Characte?r ', '', '-', true, 'trimmed-string-with-special-characte-r'],
            ['Magyar szöveg speciális karakterek tiltásával és szóközökkel. Üzbég.', ' ÁÍŰŐÜÖÚÓÉáíűőüöúóé', '', false, 'Magyar szöveg speciális karakterek tiltásával és szóközökkel Üzbég'],
            ['[ABC] a.d-2 (mód.txt', ' ÁÍŰŐÜÖÚÓÉáíűőüöúóé._-', '', false, 'ABC a.d-2 mód.txt'],
        ];
    }
}
