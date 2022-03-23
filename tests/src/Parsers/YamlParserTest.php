<?php

namespace Test\src\DataDecoding;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Parsers\YamlParser\getYamlParsedArray;

class YamlParserTest extends TestCase
{
    public function testGetYamlParsedArray(): void
    {
        $yamlParsedExpected = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];

        $path = __DIR__ . "/../../fixtures/yaml/file1.yaml";
        $yamlParsedActual = getYamlParsedArray($path);

        $this->assertEquals($yamlParsedExpected, $yamlParsedActual);
    }
}
