<?php

namespace Formatters\StylishFormatter;

use Differ\Structures\DiffTree;

function formatInStylish(array $tree): string
{
    $outputString = "{\n" . buildTreeWithValuesAndBrackets($tree) . "}";

    return $outputString;
}

function buildTreeWithValuesAndBrackets(array $tree, int $spacesCount = 0): string
{
    $formattedTree = \array_reduce($tree, function ($acc, $key) use ($spacesCount) {
        $children = DiffTree\getNodeChildren($key);
        $spacesStep = 4;
        if (!\is_null($children)) {
            $sign = DiffTree\getSign($key);
            $name = DiffTree\getName($key);
            $acc .= \str_repeat(' ', $spacesCount) . "  {$sign} {$name}: {\n";
            $acc .= buildTreeWithValuesAndBrackets($children, $spacesCount + $spacesStep);
            return $acc . \str_repeat(" ", $spacesCount + $spacesStep) . "}\n";
        } else {
            return $acc . \str_repeat(" ", $spacesCount) . createDifference($key) . "\n";
        }
    }, '');

    return $formattedTree;
}

function createDifference(array $key): string
{
    $sign = DiffTree\getSign($key);
    $name = DiffTree\getName($key);
    $value = DiffTree\getValue($key);

    return "  {$sign} {$name}: {$value}";
}
