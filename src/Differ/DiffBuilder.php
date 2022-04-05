<?php

namespace Differ\DiffBuilder;

use Differ\Structures\DiffTree;
use function Functional\sort;

function buildDiffTree(array $obj_vars1, array $obj_vars2): array
{
    $keys1 = getSortedArrayKeys($obj_vars1);
    $keys2 = getSortedArrayKeys($obj_vars2);

    $keysCount1 = \count($keys1);
    $keysCount2 = \count($keys2);

    $index1 = 0;
    $index2 = 0;
    $diffTree = [];
    $signs = ['first' => '-', 'second' => '+'];

    while ($index1 < $keysCount1 && $index2 < $keysCount2) {
        $key1 = $keys1[$index1];
        $key2 = $keys2[$index2];
        $node = [];
        $comparisonResult = \strcmp($key1, $key2);
        if ($comparisonResult === 0) {
            if (is_object($obj_vars1[$key1]) && is_object($obj_vars2[$key2])) {
                $node = DiffTree\makeNode($key1);
                $node = DiffTree\setChildren(
                    $node,
                    buildDiffTree(\get_object_vars($obj_vars1[$key1]), \get_object_vars($obj_vars2[$key1]))
                );
            } else {
                if ($obj_vars1[$key1] === $obj_vars2[$key2]) {
                    $node = buildCurrNode($key1, $obj_vars1);
                } else {
                    $node = buildCurrNode($key1, $obj_vars1, $signs['first']);
                    $diffTree[] = $node;
                    $node = buildCurrNode($key2, $obj_vars2, $signs['second']);
                }
            }
            $index1++;
            $index2++;
        } elseif ($comparisonResult > 0) {
            $node = buildCurrNode($key2, $obj_vars2, $signs['second']);
            $index2++;
        } else {
            $node = buildCurrNode($key1, $obj_vars1, $signs['first']);
            $index1++;
        }

        $diffTree[] = $node;
    }

    if ($index1 < $keysCount1) {
        $diffTree = [...$diffTree, ...getRestOfData($obj_vars1, $keys1, $index1, $signs['first'])];
    } else {
        $diffTree = [...$diffTree, ...getRestOfData($obj_vars2, $keys2, $index2, $signs['second'])];
    }

    return $diffTree;
}

function buildCurrNode(string $key, array $obj_vars, string $sign = ' '): array
{
    $node = DiffTree\makeNode($key);
    $node = DiffTree\setSign($node, $sign);
    if (is_object($obj_vars[$key])) {
        $node = DiffTree\setChildren($node, buildTreeRecursive(\get_object_vars($obj_vars[$key])));
    } else {
        $node = DiffTree\setNodeValue($node, getStringValueOfElement($obj_vars[$key]));
    }

    return $node;
}

function buildTreeRecursive(array $obj_vars): array
{
    $tree = [];
    $keys = $keys = getSortedArrayKeys($obj_vars);
    foreach ($keys as $key) {
        $node = DiffTree\makeNode($key);
        if (is_object($obj_vars[$key])) {
            $node = DiffTree\setChildren($node, buildTreeRecursive(\get_object_vars($obj_vars[$key])));
        } else {
            $node = DiffTree\setNodeValue($node, getStringValueOfElement($obj_vars[$key]));
        }

        $tree[] = $node;
    }

    return $tree;
}

function getSortedArrayKeys(array $arr): array
{
    $keys = array_keys($arr);
    \usort($keys, 'strcmp');

    return $keys;
}

function getRestOfData(array $obj_vars, array $keys, int $startIndex, string $sign): array
{
    $length = count($obj_vars);
    $tree = [];
    for ($ind = $startIndex; $ind < $length; $ind++) {
        $key = $keys[$ind];
        $node = buildCurrNode($key, $obj_vars, $sign);
        $tree[] = $node;
    }

    return $tree;
}

function getStringValueOfElement($element): string
{
    if (\is_bool($element)) {
        return var_export($element, true);
    } elseif (\is_null($element)) {
        return 'null';
    } else {
        return \strval($element);
    }
}
