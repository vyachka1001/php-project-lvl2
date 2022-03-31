<?php

namespace Differ\Structures\DiffTree;

function makeNode(string $name): array
{
    return [
        "name" => $name,
        "value" => null,
        "children" => null,
        "sign" => null
    ];
}

function getName(array $tree): ?string
{
    return $tree['name'];
}

function setNodeValue(array &$tree, string $value): void
{
    $tree['value'] = $value;
}

function getValue(array $tree, string $default = null): ?string
{
    if (isset($tree['value'])) {
        return $tree['value'];
    }

    return $default;
}

function setChildren(array &$tree, array $children): void
{
    $tree['children'] = $children;
}

function getNodeChildren(array $tree, string $default = null): ?array
{
    if (isset($tree['children'])) {
        return $tree['children'];
    }

    return $default;
}

function setSign(array &$tree, string $sign = null): void
{
    $tree['sign'] = $sign;
}

function getSign(array $tree, string $default = ' '): string
{
    if (isset($tree['sign'])) {
        return $tree['sign'];
    }

    return $default;
}
