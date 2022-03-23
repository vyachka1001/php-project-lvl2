<?php

namespace Hexlet\Code\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function getYamlParsedArray($path): array
{
    $yamlParsed = Yaml::parseFile($path);

    return $yamlParsed;
}
