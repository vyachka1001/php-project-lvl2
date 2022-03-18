<?php

namespace Src\DataDecoding\Decoder;

function getJsonDecodedArray($path): array
{
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
