<?php

namespace Differ\Differ;

use function Hexlet\Code\Parsers\JsonParser\getJsonParsedObject;
use function Hexlet\Code\Parsers\YamlParser\getYamlParsedObject;
use function Differ\DiffBuilder\buildDiffTree;
use function Differ\DiffFormatter\formatDiffStr;

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $parsed1 = getParsedObject($path1);
    $parsed2 = getParsedObject($path2);

    $obj_vars1 = \get_object_vars($parsed1);
    $obj_vars2 = \get_object_vars($parsed2);

    $diffTree = buildDiffTree($obj_vars1, $obj_vars2);
    $diffStr = formatDiffStr($diffTree, $format);

    return $diffStr;
}

function getParsedObject(string $path): object
{
    $extension = getFileExtension($path);
    if ($extension === '.yaml' || $extension === '.yml') {
        return getYamlParsedObject($path);
    } elseif ($extension === '.json') {
        return getJsonParsedObject($path);
    }
}

function getFileExtension(string $path): string
{
    $extensionPointInd = \strrpos($path, ".");
    $extension = \substr($path, $extensionPointInd);

    return $extension;
}
