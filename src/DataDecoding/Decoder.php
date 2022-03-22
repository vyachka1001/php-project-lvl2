<?php

namespace Hexlet\Code\DataDecoding\Decoder;

function getJsonDecodedArray($path): array
{
    //$path = __DIR__ . "/../../resources/" . $path;
    $json = readDataFromFile($path);
    $decodedJson = json_decode($json, true);

    return $decodedJson;
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
