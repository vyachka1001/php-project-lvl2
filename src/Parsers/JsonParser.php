<?php

namespace Hexlet\Code\Parsers\JsonParser;

function getJsonParsedObject(string $path): object
{
    $json = readDataFromFile($path);
    $parsedJson = \json_decode($json);

    return $parsedJson;
}

function readDataFromFile(string $filePath): string
{
    $file = fopen($filePath, 'r');
    $data = '';

    while (!feof($file)) {
        $data = $data . fgets($file);
    }

    return $data;
}
