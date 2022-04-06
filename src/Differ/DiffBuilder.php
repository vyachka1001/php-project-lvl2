<?php

namespace Differ\DiffBuilder;

use Differ\Structures\DiffTree;

function buildDiffTree(array $objVars1, array $objVars2): array
{
    $signs = ['first' => '-', 'second' => '+'];
    $treeWithCommonKeys = buildTreeWithCommonKeys($objVars1, $objVars2, $signs);
    //print_r($treeWithCommonKeys);

    return $treeWithCommonKeys;
}

function buildTreeWithCommonKeys(array $objVars1, array $objVars2, array $signs): array
{
    $keys1 = array_keys($objVars1);
    $keys2 = array_keys($objVars2);
    $commonKeys = sortArray(array_intersect($keys1, $keys2));
    $keys = sortArray(array_unique(array_merge($keys1, $keys2, $commonKeys)));

    $tree = array_reduce(
        $keys,
        function ($acc, $key) use ($keys1, $keys2, $commonKeys, $objVars1, $objVars2, $signs) {
            if (in_array($key, $commonKeys, true)) {
                if (is_object($objVars1[$key]) && is_object($objVars2[$key])) {
                    $node = DiffTree\setChildren(
                        DiffTree\makeNode($key),
                        buildTreeWithCommonKeys(
                            \get_object_vars($objVars1[$key]),
                            \get_object_vars($objVars2[$key]),
                            $signs
                        )
                    );
                    return [...$acc, $node];
                } else {
                    if ($objVars1[$key] === $objVars2[$key]) {
                        return [...$acc, buildCurrNode($key, $objVars1)];
                    } else {
                        return [...$acc, buildCurrNode($key, $objVars1, $signs['first']),
                            buildCurrNode($key, $objVars2, $signs['second'])];
                    }
                }
            } else {
                return (in_array($key, $keys1, true)) ?
                    [...$acc, buildCurrNode($key, $objVars1, $signs['first'])] :
                    [...$acc, buildCurrNode($key, $objVars2, $signs['second'])];
            }
        },
        []
    );

    return $tree;
}

function sortArray(array $arr): array
{
    $sorted = $arr;
    \sort($sorted);

    return $sorted;
}

function buildCurrNode(string $key, array $objVars, string $sign = ' '): array
{
    $node = DiffTree\makeNode($key, null, null, $sign);
    if (is_object($objVars[$key])) {
        return DiffTree\setChildren($node, buildTreeRecursive(\get_object_vars($objVars[$key])));
    } else {
        return DiffTree\setNodeValue($node, getStringValueOfElement($objVars[$key]));
    }
}

function buildTreeRecursive(array $objVars): array
{
    $keys = getSortedArrayKeys($objVars);
    $tree = array_map(function ($key) use ($objVars) {
        $node = DiffTree\makeNode($key);
        if (is_object($objVars[$key])) {
            return DiffTree\setChildren($node, buildTreeRecursive(\get_object_vars($objVars[$key])));
        } else {
            return DiffTree\setNodeValue($node, getStringValueOfElement($objVars[$key]));
        }
    }, $keys);

    return $tree;
}

function getSortedArrayKeys(array $arr): array
{
    $keys = array_keys($arr);
    sort($keys);

    return $keys;
}

function getStringValueOfElement(mixed $element): string
{
    if (\is_bool($element)) {
        return var_export($element, true);
    } elseif (\is_null($element)) {
        return 'null';
    } else {
        return \strval($element);
    }
}
