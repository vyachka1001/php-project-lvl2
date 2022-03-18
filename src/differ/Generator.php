<?php

namespace Differ\Differ;

function genDiff(string $path1, string $path2): string
{
    $json1 = readDataFromFile($path1);
    $json2 = readDataFromFile($path2);

    $decodedJson1 = json_decode($json1, true);
    $decodedJson2 = json_decode($json2, true);

    return "it works!\n";
}

function readDataFromFile(string $filePath): string
{
    $file = fopen($filePath, 'r');
    $data = '';

    while(!feof($file))
    {
        $data .= fgets($file);
    }

    return $data;
}