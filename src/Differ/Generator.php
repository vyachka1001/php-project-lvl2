<?php

namespace Differ\Differ;

use function Hexlet\Code\Parsers\JsonParser\getJsonParsedArray;
use function Hexlet\Code\Parsers\YamlParser\getYamlParsedArray;

function genDiff(string $path1, string $path2): string
{
    $parsed1 = getParsedArray($path1);
    $parsed2 = getParsedArray($path2);
    $keys1 = getSortedArrayKeys($parsed1);
    $keys2 = getSortedArrayKeys($parsed2);

    $diff = getDifferencesString($parsed1, $parsed2, $keys1, $keys2);

    return "{{$diff}\n}\n";
}

function getParsedArray(string $path): array
{
    $extension = getFileExtension($path);
    if ($extension === '.yaml' || $extension === '.yml') {
        return getYamlParsedArray($path);
    } elseif ($extension === '.json') {
        return getJsonParsedArray($path);
    }
}

function getFileExtension(string $path): string
{
    $extensionPointInd = \strrpos($path, ".");
    $extension = \substr($path, $extensionPointInd);

    return $extension;
}

function getDifferencesString(array $parsed1, array $parsed2, array $keys1, array $keys2): string
{
    $index1 = 0;
    $index2 = 0;
    $length1 = \count($parsed1);
    $length2 = \count($parsed2);
    $signs = ['first' => '-', 'second' => '+', 'equal' => ' '];
    $diff = '';

    while ($index1 < $length1 && $index2 < $length2) {
        $key1 = $keys1[$index1];
        $key2 = $keys2[$index2];

        $comparisonResult = \strcmp($key1, $key2);
        if ($comparisonResult > 0) {
            $diff .= createDifference($signs['second'], $key2, $parsed2);
            $index2++;
        } elseif ($comparisonResult < 0) {
            $diff .= createDifference($signs['first'], $key1, $parsed1);
            $index1++;
        } else {
            if ($parsed1[$key1] != $parsed2[$key2]) {
                $diff .= createDifference($signs['first'], $key1, $parsed1);
                $diff .= createDifference($signs['second'], $key2, $parsed2);
            } else {
                $diff .= createDifference($signs['equal'], $key1, $parsed1);
            }

            $index1++;
            $index2++;
        }
    }

    if ($index1 < $length1) {
        $diff .= getRestOfData($parsed1, $index1, $signs['first']);
    } elseif ($index2 < $length2) {
        $diff .= getRestOfData($parsed2, $index2, $signs['second']);
    }

    return $diff;
}

function getSortedArrayKeys(array $arr): array
{
    $keys = array_keys($arr);
    sort($keys);

    return $keys;
}

function formKeyValueStr(string $key, array $decoded): string
{
    $value = getStringValueOfElement($decoded[$key]);
    $result = $key . ": " . $value;

    return $result;
}

function getStringValueOfElement($element): string
{
    if (\is_bool($element)) {
        return var_export($element, true);
    } else {
        return \strval($element);
    }
}

function createDifference(string $diffSign, string $key, array $decoded): string
{
    $str = formKeyValueStr($key, $decoded);
    $diff = "\n  {$diffSign} {$str}";

    return $diff;
}

function getRestOfData(array $decoded, int $startIndex, string $diffChar): string
{
    $length = count($decoded);
    $keys = getSortedArrayKeys($decoded);
    $diff = '';
    for ($ind = $startIndex; $ind < $length; $ind++) {
        $key = $keys[$ind];
        $str = formKeyValueStr($key, $decoded);
        $diff = "{$diff}\n  {$diffChar} {$str}";
    }

    return $diff;
}
