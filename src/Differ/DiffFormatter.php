<?php

namespace Differ\DiffFormatter;

use Differ\TreeStructure\DiffTree;

function formatDiffStr(array $diffTree, string $format): string
{
    switch ($format) {
        case 'plain':
            return formatInPlain($diffTree);
        default:
            return formatInStylish($diffTree);
    }
}

function formatInPlain(array $tree): string
{
    return 'string';
}

function formatInStylish(array $tree): string
{
    $outputString = "{\n" . buildTreeWithValuesAndBrackets($tree) . "}\n";

    return $outputString;
}

function buildTreeWithValuesAndBrackets(array $tree, int $spacesCount = 0): string
{
    $formattedTree = '';
    $formattedTree .= \array_reduce($tree, function ($acc, $key) use ($spacesCount) {
        $children = DiffTree\getNodeChildren($key);
        $spacesStep = 4;
        if (!\is_null($children)) {
            $sign = DiffTree\getSign($key);
            $name = DiffTree\getName($key);
            $acc .= \str_repeat(' ', $spacesCount). "  {$sign} {$name}: {\n";
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
