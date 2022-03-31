<?php

namespace Formatters\PlainFormatter;

use Differ\TreeStructure\DiffTree;

function formatInPlain(array $tree): string
{
    $diffArray = buildDiffArray($tree);
    $plainOutput = formatDiffArray($diffArray);

    return $plainOutput;
}

function buildDiffArray(array $tree, string $namespace = null): array
{
    $diffArray = array_reduce($tree, function($acc, $key) use ($namespace) {
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

function formatDiffArray(array $diffArray): string
{

}
