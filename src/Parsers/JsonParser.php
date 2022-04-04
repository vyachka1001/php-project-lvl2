<?php

namespace Hexlet\Code\Parsers\JsonParser;

function getJsonParsedObject(string $path): object
{
    $parsedJson = '';
    $jsonString = \file_get_contents($path);
    return \json_decode((string)$jsonString);
}
