<?php

namespace Differ\Structures\DiffTree;

function makeNode(string $name, $value = null, $children = null, $sign = null): array
{
    return [
        "name" => $name,
        "value" => $value,
        "children" => $children,
        "sign" => $sign
    ];
}

function getName(array $tree): ?string
{
    return $tree['name'];
}

function setNodeValue(array $tree, string $value): array
{
    $name = getName($tree);
    $children = getNodeChildren($tree);
    $sign = getSign($tree);

    return makeNode($name, $value, $children, $sign);
}

function getValue(array $tree, string $default = null): ?string
{
    if (isset($tree['value'])) {
        return $tree['value'];
    }

    return $default;
}

function setChildren(array $tree, array $children): array
{
    $name = getName($tree);
    $value = getValue($tree);
    $sign = getSign($tree);

    return makeNode($name, $value, $children, $sign);
}

function getNodeChildren(array $tree, string $default = null): ?array
{
    if (isset($tree['children'])) {
        return $tree['children'];
    }

    return $default;
}

function setSign(array $tree, string $sign = null): array
{
    $name = getName($tree);
    $children = getNodeChildren($tree);
    $value = getValue($tree);

    return makeNode($name, $value, $children, $sign);
}

function getSign(array $tree, string $default = ' '): string
{
    if (isset($tree['sign'])) {
        return $tree['sign'];
    }

    return $default;
}
