<?php

namespace Hexlet\Code\Parsers\JsonParser;

function getJsonParsedObject(string $path): object
{
    $parsedJson = '';
    $jsonString = \file_get_contents($path);
    if ($jsonString !== false) {
        $parsedJson = \json_decode($jsonString);
    }

    return $parsedJson;
}
