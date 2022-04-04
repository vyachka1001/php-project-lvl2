<?php

namespace Formatters\JsonFormatter;

use Differ\Structures\DiffTree;
use Formatters\PlainFormatter;

function formatInJson(array $tree): string
{
    $addedValues = buildChangedValues($tree, '+');
    $deletedValues = buildChangedValues($tree, '-');

    //somehow find updates and filter this stupid values;
    $outputString = createJsonFormattedString($deletedValues, $addedValues);    

    return "{\n" . $outputString . "}";
}

function buildChangedValues(array $tree, string $sign = ''): array
{
    $formattedTree = \array_reduce($tree, function ($acc, $key) use ($sign) {
        $children = DiffTree\getNodeChildren($key);
        $keySign = DiffTree\getSign($key);
        $name = DiffTree\getName($key);
        if (!\is_null($children) && $keySign === $sign) {
            $acc[] = $key;
        } elseif (!\is_null($children) && $keySign === ' ') {
            $node = DiffTree\makeNode($name);
            DiffTree\setChildren($node, buildChangedValues($children, $sign));
            $acc[] = $node;
        } elseif ($keySign === $sign) {
            $acc[] = $key;
        }

        return $acc;
    }, []);

    return $formattedTree;
}

function createDifference(array $key): string
{
    $spacesCount = 4;
    $name = DiffTree\getName($key);
    $value = DiffTree\getValue($key);

    return \str_repeat(" ", $spacesCount) . "{$name}: {$value}";
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

function createJsonFormattedString(array $deletedValues, array $addedValues): string
{
    $spacesStep = 4;
    $output = \str_repeat(' ', $spacesStep) . "deleted: {\n" . createCurrNode($deletedValues, $spacesStep) . 
        \str_repeat(' ', $spacesStep) . "}\n";

    $output .= \str_repeat(' ', $spacesStep) . "added: {\n" . createCurrNode($addedValues, $spacesStep) . 
        \str_repeat(' ', $spacesStep) . "}\n";
    
    return $output;
}
