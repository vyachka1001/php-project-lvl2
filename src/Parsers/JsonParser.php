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
    if ($file > 0) {
        $size = \filesize($filePath);
        if ($size > 0) {
            $data = \fread($file, $size);
        }
    }

    \fclose($file);

    return $data;
}
