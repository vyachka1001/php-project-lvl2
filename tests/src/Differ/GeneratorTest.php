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
        $expected = "{\n" . "    co: {\n" . \str_repeat(' ', 4) . "    set: key\n" . \str_repeat(' ', 4) .
            "}\n" . "  + com: {\n" . \str_repeat(' ', 4) . "    setting1: val\n" . \str_repeat(' ', 4) .
            "}\n" . \str_repeat(' ', 4) . "common: {\n" . \str_repeat(' ', 4) . "  - setting4: val\n" .
            \str_repeat(' ', 4) . "  + setting4: al\n" . \str_repeat(' ', 4) . "}\n" . "  + follow: false\n" .
            "    host: hexlet.io\n" . "  - z: {\n" . \str_repeat(' ', 4) . "    key: {\n" . \str_repeat(' ', 8) .
            "    key: {\n" . \str_repeat(' ', 12) . "    key: null\n" . \str_repeat(' ', 12) . "}\n" .
            \str_repeat(' ', 8) . "}\n" . \str_repeat(' ', 4) . "}\n" . "}\n";

        $actual = genDiff($this->jsonPath1, $this->jsonPath2);
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath1, $this->yamlPath2);
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiff2()
    {
        $expected = "{\n" . "    co: {\n" . \str_repeat(' ', 4) . "    set: key\n" . \str_repeat(' ', 4) .
            "}\n" . "  - com: {\n" . \str_repeat(' ', 4) . "    setting1: val\n" . \str_repeat(' ', 4) .
            "}\n" . \str_repeat(' ', 4) . "common: {\n" . \str_repeat(' ', 4) . "  - setting4: al\n" .
            \str_repeat(' ', 4) . "  + setting4: val\n" . \str_repeat(' ', 4) . "}\n" . "  - follow: false\n" .
            "    host: hexlet.io\n" . "  + z: {\n" . \str_repeat(' ', 4) . "    key: {\n" . \str_repeat(' ', 8) .
            "    key: {\n" . \str_repeat(' ', 12) . "    key: null\n" . \str_repeat(' ', 12) . "}\n" .
            \str_repeat(' ', 8) . "}\n" . \str_repeat(' ', 4) . "}\n" . "}\n";

        $actual = genDiff($this->jsonPath2, $this->jsonPath1);
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath2, $this->yamlPath1);
        $this->assertEquals($expected, $actual);
    }
}
