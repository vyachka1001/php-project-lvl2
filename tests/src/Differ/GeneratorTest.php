<?php

namespace Tests\src\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GeneratorTest extends TestCase
{
    private string $path1 = __DIR__ . "/../../fixtures/file1.json";
    private string $path2 = __DIR__ . "/../../fixtures/file2.json";

    public function testGenDiff1()
    {
        $diffExpected = [
            '- follow: false',
            '  host: \'hexlet.io\'',
            '- proxy: \'123.234.53.22\'',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true'
        ];

        $expected = $this::buildExpectedDiff($diffExpected);
        $actual = genDiff($this->path1, $this->path2);

        $this->assertEquals($expected, $actual);
    }

    public function testGenDiff2()
    {
        $diffExpected = [
            '+ follow: false',
            '  host: \'hexlet.io\'',
            '+ proxy: \'123.234.53.22\'',
            '- timeout: 20',
            '+ timeout: 50',
            '- verbose: true'
        ];

        $expected = $this::buildExpectedDiff($diffExpected);
        $actual = genDiff($this->path2, $this->path1);

        $this->assertEquals($expected, $actual);
    }

    public static function buildExpectedDiff($diff): string
    {
        $expectedDiff = \implode("\n  ", $diff);

        return "{\n  {$expectedDiff}\n}\n";
    }
}
