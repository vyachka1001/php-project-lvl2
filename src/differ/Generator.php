<?php

namespace Differ\Differ;

use function Src\DataDecoding\Decoder\getJsonDecodedArray;

function genDiff(string $path1, string $path2): string
{
    $decodedJson1 = getJsonDecodedArray($path1);
    $decodedJson2 = getJsonDecodedArray($path2);

    $keysJson1 = getSortedArrayKeys($decodedJson1);
    $keysJson2 = getSortedArrayKeys($decodedJson2);

    $indexJson1 = 0;
    $indexJson2 = 0;
    $lengthJson1 = \count($decodedJson1);
    $lengthJson2 = \count($decodedJson2);

    $diffSign1 = '-';
    $diffSign2 = '+';
    $diffSignEqual = ' ';
    $diff = '{';

    while ($indexJson1 < $lengthJson1 && $indexJson2 < $lengthJson2) {
        $key1 = $keysJson1[$indexJson1];
        $key2 = $keysJson2[$indexJson2];

        $comparisonResult = \strcmp($key1, $key2);
        if ($comparisonResult > 0) {
            $diff = addToDifference($diff, $diffSign2, $key2, $decodedJson2);
            $indexJson2++;
        } elseif ($comparisonResult < 0) {
            $diff = addToDifference($diff, $diffSign1, $key1, $decodedJson1);
            $indexJson1++;
        } else {
            if ($decodedJson1[$key1] != $decodedJson2[$key2]) {
                $diff = addToDifference($diff, $diffSign1, $key1, $decodedJson1);
                $diff = addToDifference($diff, $diffSign2, $key2, $decodedJson2);
            } else {
                $diff = addToDifference($diff, $diffSignEqual, $key1, $decodedJson1);
            }

            $indexJson1++;
            $indexJson2++;
        }
    }

    if ($indexJson1 < $lengthJson1) {
        $diff = addRestOfTheData($diff, $keysJson1, $decodedJson1, $indexJson1, $diffSign1);
    } elseif ($indexJson2 < $lengthJson2) {
        $diff = addRestOfTheData($diff, $keysJson2, $decodedJson2, $indexJson2, $diffSign2);
    }

    $diff .= "\n}\n";

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

function addToDifference(string $diff, string $diffSign, string $key, array $jsonData): string
{
    $strJson = formKeyValueStr($key, $jsonData);
    $diff = "{$diff}\n  {$diffSign} {$strJson}";

    return $diff;
}

function addRestOfTheData(string $diff, array $keys, array $jsonData, int $startIndex, string $diffChar): string
{
    $length = count($jsonData);
    for ($ind = $startIndex; $ind < $length; $ind++) {
        $key = $keys[$ind];
        $strJson = formKeyValueStr($key, $jsonData);
        $diff = "{$diff}\n  {$diffChar} {$strJson}";
    }

    return $diff;
}
