<?php

namespace Hexlet\Code\Parsers\YamlParser;

use Symfony\Component\Yaml\Yaml;

function getYamlParsedObject($path): object
{
    $yamlParsed = Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);

    return $yamlParsed;
}
