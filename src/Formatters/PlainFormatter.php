<?php

namespace Formatters\PlainFormatter;

function formatInPlain(array $tree): string
{
    print_r($tree);
    return 'string';
}