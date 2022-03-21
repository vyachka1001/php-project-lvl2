<?php

namespace Tests\Src\DataDecoding;

use PHPUnit\Framework\TestCase;
use function Src\DataDecoding\Decoder\getJsonDecodedArray;

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

        $path = "tests/fixtures/file1.json";
        $jsonDecodedActual = getJsonDecodedArray($path);
        $this->assertEquals($jsonDecodedExpected, $jsonDecodedActual);
    }
}