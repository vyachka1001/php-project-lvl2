<?php

namespace Hexlet\Code\Parsers\JsonParser;

function getJsonParsedObject($path): object
{
    //$path = __DIR__ . "/../../resources/" . $path;
    $json = readDataFromFile($path);
    $parsedJson = \json_decode($json);

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
