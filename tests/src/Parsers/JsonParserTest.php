<?php

namespace Test\src\DataDecoding;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Parsers\JsonParser\getJsonParsedArray;

class JsonParserTest extends TestCase
{
    public function testGetJsonParsedArray(): void
    {
        $jsonParsedExpected = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];

        $path = __DIR__ . "/../../fixtures/json/file1.json";
        $jsonParsedActual = getJsonParsedArray($path);
        $this->assertEquals($jsonParsedExpected, $jsonParsedActual);
    }
}
