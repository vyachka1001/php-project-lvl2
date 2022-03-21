<?php

namespace Differ\Differ;

use function Src\DataDecoding\Decoder\getJsonDecodedArray;

function genDiff(string $path1, string $path2): string
{
    $decodedJson1 = getJsonDecodedArray($path1);
    $decodedJson2 = getJsonDecodedArray($path2);

    $keysJson1 = getSortedArrayKeys($decodedJson1);
    $keysJson2 = getSortedArrayKeys($decodedJson2);

    $diff = getDifferencesString($decodedJson1, $decodedJson2, $keysJson1, $keysJson2);

    return "{{$diff}\n}\n";
}

function getDifferencesString(array $decodedJson1, array $decodedJson2, array $keysJson1, array $keysJson2)
{
    $indexJson1 = 0;
    $indexJson2 = 0;
    $lengthJson1 = \count($decodedJson1);
    $lengthJson2 = \count($decodedJson2);
    $signs = ['first' => '-', 'second' => '+', 'equal' => ' '];
    $diff = '';

    while ($indexJson1 < $lengthJson1 && $indexJson2 < $lengthJson2) {
        $key1 = $keysJson1[$indexJson1];
        $key2 = $keysJson2[$indexJson2];

        $comparisonResult = \strcmp($key1, $key2);
        if ($comparisonResult > 0) {
            $diff .= createDifference($signs['second'], $key2, $decodedJson2);
            $indexJson2++;
        } elseif ($comparisonResult < 0) {
            $diff .= createDifference($signs['first'], $key1, $decodedJson1);
            $indexJson1++;
        } else {
            if ($decodedJson1[$key1] != $decodedJson2[$key2]) {
                $diff .= createDifference($signs['first'], $key1, $decodedJson1);
                $diff .= createDifference($signs['second'], $key2, $decodedJson2);
            } else {
                $diff .= createDifference($signs['equal'], $key1, $decodedJson1);
            }

            $indexJson1++;
            $indexJson2++;
        }
    }

    if ($indexJson1 < $lengthJson1) {
        $diff .= getRestOfData($decodedJson1, $indexJson1, $signs['first']);
    } elseif ($indexJson2 < $lengthJson2) {
        $diff .= getRestOfData($decodedJson2, $indexJson2, $signs['second']);
    }

    return $diff;
}

function getSortedArrayKeys(array $arr): array
{
    $keys = array_keys($arr);
    sort($keys);

    return $keys;
}

function formKeyValueStr(string $key, array $decodedJson): string
{
    $result = $key . ": " . var_export($decodedJson[$key], true);

    return $result;
}

function createDifference(string $diffSign, string $key, array $jsonData): string
{
    $strJson = formKeyValueStr($key, $jsonData);
    $diff = "\n  {$diffSign} {$strJson}";

    return $diff;
}

function getRestOfData(array $jsonData, int $startIndex, string $diffChar): string
{
    $length = count($jsonData);
    $keys = getSortedArrayKeys($jsonData);
    $diff = '';
    for ($ind = $startIndex; $ind < $length; $ind++) {
        $key = $keys[$ind];
        $strJson = formKeyValueStr($key, $jsonData);
        $diff = "{$diff}\n  {$diffChar} {$strJson}";
    }

    return $diff;
}
