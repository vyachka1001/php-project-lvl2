<?php

namespace Test\src\DataDecoding;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\DataDecoding\Decoder\getJsonDecodedArray;

class DecoderTest extends TestCase
{
    public function testGetJsonDecodedArray()
    {
        $jsonDecodedExpected = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];

        $path = __DIR__ . "/../../fixtures/file1.json";
        $jsonDecodedActual = getJsonDecodedArray($path);
        $this->assertEquals($jsonDecodedExpected, $jsonDecodedActual);
    }
}
