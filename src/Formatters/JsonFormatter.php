<?php

namespace Formatters\JsonFormatter;

use Differ\Structures\DiffTree;
use Formatters\PlainFormatter;

function formatInJson(array $tree): string
{
    $deletedValues = buildChangedValues($tree, '-');
    $addedValues = buildChangedValues($tree, '+');
    \print_r($addedValues);

    $updatedValues = findIntersectionsOfArrays($addedValues, $deletedValues);
    $deletedValues = findDifferencesOfArrays($deletedValues, $updatedValues);
    $addedValues = findDifferencesOfArrays($addedValues, $updatedValues);

    print_r($updatedValues);
    \print_r($addedValues);

    return createJsonFormattedString($deletedValues, $addedValues, $updatedValues);
}

function buildChangedValues(array $tree, string $sign = ' '): array
{
    $defaultSign = ' ';
    $formattedTree = \array_reduce($tree, function ($acc, $key) use ($sign, $defaultSign) {
        $children = DiffTree\getNodeChildren($key);
        $keySign = DiffTree\getSign($key);
        $name = DiffTree\getName($key);
        if (!\is_null($children) && $keySign === $sign) {
            $acc[$name] = buildChangedValues($children, $defaultSign);
        } elseif (!\is_null($children) && $keySign === $defaultSign) {
            $acc[$name] = buildChangedValues($children, $sign);
        } elseif ($keySign === $sign) {
            $acc[$name] = DiffTree\getValue($key);
        }

        return $acc;
    }, []);

    return $formattedTree;
}

function findIntersectionsOfArrays(array $array1, $array2): array
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

function createDifference(array $key): string
{
    $spacesCount = 4;
    $name = DiffTree\getName($key);
    $value = DiffTree\getValue($key);

    return \str_repeat(" ", $spacesCount) . "{$name}: {$value},";
}

function createCurrNode(array $tree, int $spacesCount = 0): string
{
    $formattedTree = \array_reduce($tree, function ($acc, $key) use ($spacesCount) {
        $children = DiffTree\getNodeChildren($key);
        $spacesStep = 4;
        if (!\is_null($children)) {
            $sign = DiffTree\getSign($key);
            $name = DiffTree\getName($key);
            $acc .= \str_repeat(' ', $spacesCount) . "    {$name}: {\n";
            $acc .= createCurrNode($children, $spacesCount + $spacesStep);
            return $acc . \str_repeat(" ", $spacesCount + $spacesStep) . "}\n";
        } else {
            return $acc . \str_repeat(" ", $spacesCount) . createDifference($key) . "\n";
        }
    }, '');

    return $formattedTree;
}

function createJsonFormattedString(array $deletedValues, array $addedValues, array $updatedValues): string
{
    return "";
}
