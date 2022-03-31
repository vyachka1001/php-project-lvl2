<?php

namespace Formatters\PlainFormatter;

use Differ\Structures\DiffTree;

function formatInPlain(array $tree): string
{
    $diffArray = buildDiffArray($tree);
    $diffArray = rebuildDiffArrayAccordingToUpdates($diffArray);
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
            $acc = [...$acc, ...$diff];
            return $acc;
        } elseif ($sign !== ' ') {
            $value = !\is_null($children) ? '[complex value]' :  DiffTree\getValue($key);
            $currName = !\is_null($namespace) ? "{$namespace}.{$name}" : $name;
            $node = DiffTree\makeNode($currName);
            DiffTree\setSign($node, $sign);
            DiffTree\setNodeValue($node, $value);
            $acc[] = $node;
            return $acc;
        } else {
            return $acc;
        }
    }, []);

    return $diffArray;
}

function rebuildDiffArrayAccordingToUpdates(array $diffArray): array
{
    $diff = [];
    foreach ($diffArray as $item) {
        $name = DiffTree\getName($item);
        $value = DiffTree\getValue($item);
        $sign = DiffTree\getSign($item);

        $diff[$name]['sign'] = $sign;
        if (\array_key_exists('value', $diff[$name])) {
            $prevValue = $diff[$name]['value'];
            $diff[$name]['value'] = [$prevValue, $value];
        } else {
            $diff[$name]['value'] = $value;
        }
    }

    return $diff;
}

function formatDiffArray(array $diffArray): string
{
    $formattedDiff = [];
    foreach ($diffArray as $key => $item) {
        $formattedDiff[] = makeDiffStr($key, $item);
    }

    return implode($formattedDiff);
}

function makeDiffStr(string $name, array $item): string
{
    $diffVerdict = '';
    if (\is_array($item['value'])) {
        $value1 = getDiffValue($item['value'][0]);
        $value2 = getDiffValue($item['value'][1]);
        $diffVerdict = "updated. From {$value1} to {$value2}";
    } else {
        switch ($item['sign']) {
            case '+':
                $value = getDiffValue($item['value']);
                $diffVerdict = "added with value: {$value}";
                break;
            case '-':
                $diffVerdict = "removed";
                break;
        }
    }

    return "Property '{$name}' was {$diffVerdict}\n";
}

function getDiffValue(string $value): string
{
    $keywords = ['true', 'false', 'null', '[complex value]'];

    return \in_array($value, $keywords, true) ? $value : "'{$value}'";
}
