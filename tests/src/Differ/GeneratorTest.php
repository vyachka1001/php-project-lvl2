<?php

namespace Tests\src\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GeneratorTest extends TestCase
{
    private string $jsonPath1 = __DIR__ . "/../../fixtures/json/file3.json";
    private string $jsonPath2 = __DIR__ . "/../../fixtures/json/file4.json";
    private string $yamlPath1 = __DIR__ . "/../../fixtures/yaml/file3.yaml";
    private string $yamlPath2 = __DIR__ . "/../../fixtures/yaml/file4.yaml";

    public function testGenDiff1()
    {
        $diffExpected = [
            '- follow: false',
            '  host: hexlet.io',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true'
        ];

        $expected = $this::buildExpectedDiff($diffExpected);

        $actual = genDiff($this->jsonPath1, $this->jsonPath2);
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath1, $this->yamlPath2);
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiff2()
    {
        $diffExpected = [
            '+ follow: false',
            '  host: hexlet.io',
            '+ proxy: 123.234.53.22',
            '- timeout: 20',
            '+ timeout: 50',
            '- verbose: true'
        ];

        $expected = $this::buildExpectedDiff($diffExpected);

        $actual = genDiff($this->jsonPath2, $this->jsonPath1);
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath2, $this->yamlPath1);
        $this->assertEquals($expected, $actual);
    }

    public static function buildExpectedDiff($diff): string
    {
        $expectedDiff = \implode("\n  ", $diff);

        return "{\n  {$expectedDiff}\n}\n";
    }
}
