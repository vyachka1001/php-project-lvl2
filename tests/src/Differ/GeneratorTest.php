<?php

namespace Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GeneratorTest extends TestCase
{
    private string $jsonPath1 = __DIR__ . "/../../fixtures/json/file3.json";
    private string $jsonPath2 = __DIR__ . "/../../fixtures/json/file4.json";
    private string $yamlPath1 = __DIR__ . "/../../fixtures/yaml/file3.yaml";
    private string $yamlPath2 = __DIR__ . "/../../fixtures/yaml/file4.yaml";

    public function testGenDiffInStylish1(): void
    {
        $expected = "{\n" . "    co: {\n" . \str_repeat(' ', 4) . "    set: key\n" . \str_repeat(' ', 4) .
            "}\n" . "  + com: {\n" . \str_repeat(' ', 4) . "    setting1: val\n" . \str_repeat(' ', 4) .
            "}\n" . \str_repeat(' ', 4) . "common: {\n" . \str_repeat(' ', 4) . "  - setting4: val\n" .
            \str_repeat(' ', 4) . "  + setting4: al\n" . \str_repeat(' ', 4) . "}\n" . "  + follow: false\n" .
            "    host: hexlet.io\n" . "  - z: {\n" . \str_repeat(' ', 4) . "    key: {\n" . \str_repeat(' ', 8) .
            "    key: {\n" . \str_repeat(' ', 12) . "    key: null\n" . \str_repeat(' ', 12) . "}\n" .
            \str_repeat(' ', 8) . "}\n" . \str_repeat(' ', 4) . "}\n" . "}";

        $actual = genDiff($this->jsonPath1, $this->jsonPath2);
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath1, $this->yamlPath2);
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiffInStylish2(): void
    {
        $expected = "{\n" . "    co: {\n" . \str_repeat(' ', 4) . "    set: key\n" . \str_repeat(' ', 4) .
            "}\n" . "  - com: {\n" . \str_repeat(' ', 4) . "    setting1: val\n" . \str_repeat(' ', 4) .
            "}\n" . \str_repeat(' ', 4) . "common: {\n" . \str_repeat(' ', 4) . "  - setting4: al\n" .
            \str_repeat(' ', 4) . "  + setting4: val\n" . \str_repeat(' ', 4) . "}\n" . "  - follow: false\n" .
            "    host: hexlet.io\n" . "  + z: {\n" . \str_repeat(' ', 4) . "    key: {\n" . \str_repeat(' ', 8) .
            "    key: {\n" . \str_repeat(' ', 12) . "    key: null\n" . \str_repeat(' ', 12) . "}\n" .
            \str_repeat(' ', 8) . "}\n" . \str_repeat(' ', 4) . "}\n" . "}";

        $actual = genDiff($this->jsonPath2, $this->jsonPath1);
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath2, $this->yamlPath1);
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiffInPlain1(): void
    {
        $expected = "Property 'com' was added with value: [complex value]" . "\n" .
            "Property 'common.setting4' was updated. From 'val' to 'al'" . "\n" .
            "Property 'follow' was added with value: false" . "\n" .
            "Property 'z' was removed";

        $actual = genDiff($this->jsonPath1, $this->jsonPath2, 'plain');
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath1, $this->yamlPath2, 'plain');
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiffInPlain2(): void
    {
        $expected = "Property 'com' was removed" . "\n" .
            "Property 'common.setting4' was updated. From 'al' to 'val'" . "\n" .
            "Property 'follow' was removed" . "\n" .
            "Property 'z' was added with value: [complex value]";

        $actual = genDiff($this->jsonPath2, $this->jsonPath1, 'plain');
        $this->assertEquals($expected, $actual);

        $actual = genDiff($this->yamlPath2, $this->yamlPath1, 'plain');
        $this->assertEquals($expected, $actual);
    }

    public function testGenDiffInJson1(): void
    {
        $expected = [
            "deleted" => [
                "z" => [
                    "key" => [
                        "key" => [
                            "key" => "null"
                        ]
                    ]
                ]
            ],
            "added" => [
                "com" => [
                    "setting1" => "val"
                ],
                "follow" => "false"
            ],
            "updated" => [
                "common" => [
                    "setting4" => "al"
                ]
            ]
        ];

        $actual = genDiff($this->jsonPath1, $this->jsonPath2, 'json');
        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), $actual);

        $actual = genDiff($this->yamlPath1, $this->yamlPath2, 'json');
        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), $actual);
    }
}
