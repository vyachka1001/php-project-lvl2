<?php

namespace Differ\Differ;

use function Src\DataDecoding\Decoder\getJsonDecodedArray;

function genDiff(string $path1, string $path2): string
{
    $decodedJson1 = getJsonDecodedArray($path1);
    $decodedJson2 = getJsonDecodedArray($path2);

    return "it works!\n";
}
