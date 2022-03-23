<?php

namespace Hexlet\Code\Parsers\JsonParser;

function getJsonParsedArray($path): array
{
    //$path = __DIR__ . "/../../resources/" . $path;
    $json = readDataFromFile($path);
    $parsedJson = \json_decode($json, true);

    return $parsedJson;
}

function readDataFromFile(string $filePath): string
{
    $file = fopen($filePath, 'r');
    $data = '';

    while (!feof($file)) {
        $data .= fgets($file);
    }

    return $data;
}
