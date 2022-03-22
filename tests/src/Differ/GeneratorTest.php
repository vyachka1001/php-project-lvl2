<?php

namespace Tests\src\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GeneratorTest extends TestCase
{
    public function testGenDiff()
    {
        $diffExpected = [
            '- follow: false',
            '  host: \'hexlet.io\'',
            '- proxy: \'123.234.53.22\'',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true'
        ];

        $expected = \implode("\n  ", $diffExpected);
        $expected = "{\n  {$expected}\n}\n";

        $path1 = __DIR__ . "/../../fixtures/file1.json";
        $path2 = __DIR__ . "/../../fixtures/file2.json";

        $actual = genDiff($path1, $path2);

        $this->assertEquals($expected, $actual);
    }
}
