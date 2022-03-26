<?php

namespace Differ\TreeStructure\DiffTree;

function makeNode(string $name): array
{
    return [
        "name" => $name,
        "value" => null,
        "children" => null,
        "sign" => null
    ];
}

function setNodeValue(array &$tree, string $value): void
{
    $tree['value'] = $value;
}

function getValue(array $tree, mixed $default = null): mixed
{
    if (array_key_exists('value', $tree)) {
        return $tree['value'];
    }

    return $default;
}

function setChildren(array &$tree, array $children): void
{
    $tree['children'] = $children;
}

function getChildren(array $tree, mixed $default = null): mixed
{
    if (array_key_exists('children', $tree)) {
        return $tree['children'];
    }

    return $default;
}

function setSign(array &$tree, string $sign = null): void
{
    $tree['sign'] = $sign;
}

function getSign(array $tree, $value, mixed $default = null): mixed
{
    if (array_key_exists('sign', $tree)) {
        return $tree['sign'];
    }

    return $default;
}
