<?php

namespace Formatters\PlainFormatter;

use Differ\Structures\DiffTree;

function formatInPlain(array $tree): string
{
    $diffArray = rebuildDiffArrayAccordingToUpdates(buildDiffArray($tree));
    $plainOutput = formatDiffArray($diffArray);

    return $plainOutput;
}

function buildDiffArray(array $tree, string $namespace = null): array
{
    $diffArray = array_reduce($tree, function ($acc, $key) use ($namespace) {
        $children = DiffTree\getNodeChildren($key);
        $sign = DiffTree\getSign($key);
        $name = DiffTree\getName($key);

        if (!\is_null($children) && $sign === ' ') {
            $diff = !\is_null($namespace) ? buildDiffArray($children, "{$namespace}.{$name}") :
                buildDiffArray($children, $name);
            return array_merge($acc, $diff);
        } elseif ($sign !== ' ') {
            $value = !\is_null($children) ? '[complex value]' :  DiffTree\getValue($key);
            $currName = !\is_null($namespace) ? "{$namespace}.{$name}" : $name;
            $node = DiffTree\makeNode($currName, $value, null, $sign);
            return addValueToArray($acc, $node);
        } else {
            return $acc;
        }
    }, []);

    return $diffArray;
}

function rebuildDiffArrayAccordingToUpdates(array $diffArray): array
{
    $nameToValueSignMap = array_map(function ($item) {
        $name = DiffTree\getName($item);
        $value = DiffTree\getValue($item);
        $sign = DiffTree\getSign($item);

        return [
            $name => [
                "value" => $value,
                "sign" => $sign
            ]
        ];
    }, $diffArray);

    $nameToValueChanges = array_merge_recursive(...$nameToValueSignMap);

    return $nameToValueChanges;
}

function formatDiffArray(array $diffArray): string
{
    $keys = array_keys($diffArray);
    $formattedDiff = array_map(fn ($key) => makeDiffStr($key, $diffArray[$key]), $keys);

    return implode("\n", $formattedDiff);
}

function makeDiffStr(string $name, array $item): string
{
    if (\is_array($item['value'])) {
        $value1 = getDiffValue($item['value'][0]);
        $value2 = getDiffValue($item['value'][1]);
        return "Property '{$name}' was updated. From {$value1} to {$value2}";
    } else {
        switch ($item['sign']) {
            case '+':
                $value = getDiffValue($item['value']);
                return "Property '{$name}' was added with value: {$value}";
            default:
                return "Property '{$name}' was removed";
        }
    }
}

function getDiffValue(string $value): string
{
    $keywords = ['true', 'false', 'null', '[complex value]'];

    return (\in_array($value, $keywords, true) || \is_numeric($value)) ? $value : "'{$value}'";
}

function addValueToArray(array $arr, array $value): array
{
    return [...$arr, $value];
}
