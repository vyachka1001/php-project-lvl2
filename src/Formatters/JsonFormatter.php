<?php

namespace Formatters\JsonFormatter;

use Differ\Structures\DiffTree;
use Formatters\PlainFormatter;

use function Functional\flatten;

function formatInJson(array $tree): string
{
    $deletedValues = buildChangedValues($tree, '-');
    $addedValues = buildChangedValues($tree, '+');

    $updatedValues = findIntersectionsOfArrays($addedValues, $deletedValues);
    $deleted = findDifferencesOfArrays($deletedValues, $updatedValues);
    $added = findDifferencesOfArrays($addedValues, $updatedValues);

    return createJsonFormattedString($deleted, $added, $updatedValues);
}

function buildChangedValues(array $tree, string $sign = ''): array
{
    $defaultSign = ' ';
    $formattedTree = \array_reduce($tree, function ($acc, $key) use ($sign, $defaultSign) {
        $children = DiffTree\getNodeChildren($key);
        $keySign = DiffTree\getSign($key);
        $name = DiffTree\getName($key);
        if (!\is_null($children) && $keySign === $sign) {
            return array_merge($acc, [$name => buildChangedValues($children, $defaultSign)]);
        } elseif (!\is_null($children) && $keySign === $defaultSign) {
            $possibleChange = buildChangedValues($children, $sign);
            if ((bool)($possibleChange)) {
                return array_merge($acc, [$name => $possibleChange]);
            }
        } elseif ($keySign === $sign) {
            return array_merge($acc, [$name => DiffTree\getValue($key)]);
        }

        return $acc;
    }, []);

    return $formattedTree;
}

function findIntersectionsOfArrays(array $array1, array $array2): array
{
    $keys1 = \array_keys($array1);
    $keys2 = \array_keys($array2);
    $commonKeys = array_intersect($keys1, $keys2);

    $intersections = \array_reduce($commonKeys, function ($acc, $item) use ($array1, $array2) {
        if (\is_array($array1[$item]) && \is_array($array2[$item])) {
            $acc[$item] = findIntersectionsOfArrays($array1[$item], $array2[$item]);
            return $acc;
        } else {
            $acc[$item] = $array1[$item];
            return $acc;
        }
    }, []);

    return $intersections;
}

function findDifferencesOfArrays(array $array1, array $array2): array
{
    $keys1 = \array_keys($array1);
    $keys2 = \array_keys($array2);
    $commonKeys = array_intersect($keys1, $keys2);

    $differences = \array_reduce($keys1, function ($acc, $item) use ($array1, $array2, $commonKeys) {
        if (\in_array($item, $commonKeys, true)) {
            if (is_array($array1[$item]) && is_array($array2[$item])) {
                $possibleDiff = findDifferencesOfArrays($array1[$item], $array2[$item]);
                if (!empty($possibleDiff)) {
                    $acc[$item] = $possibleDiff;
                }
            }
        } else {
            $acc[$item] = $array1[$item];
        }

        return $acc;
    }, []);

    return $differences;
}

function createJsonFormattedString(array $deletedValues, array $addedValues, array $updatedValues): string
{
    $resultingArray = [
        "deleted" => $deletedValues,
        "added" => $addedValues,
        "updated" => $updatedValues
    ];

    $jsonFormattedString = json_encode($resultingArray, JSON_PRETTY_PRINT);

    return $jsonFormattedString;
}
