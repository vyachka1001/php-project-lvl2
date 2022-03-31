<?php

namespace Tests\Differ\Structures;

use PHPUnit\Framework\TestCase;
use Differ\Structures\DiffTree;

class DiffTreeTest extends TestCase
{
    private array $node;
    private string $name = "testNode";

    public function setUp(): void
    {
        $this->node = DiffTree\makeNode("testNode");
    }

    public function testGetName(): void
    {
        $expected = DiffTree\getName($this->node);
        $this->assertEquals($expected, $this->name);
    }

    public function testGetValue(): void
    {
        $expected = DiffTree\getValue($this->node);
        $this->assertNull($expected);
    }

    public function testGetSign(): void
    {
        $expected = DiffTree\getSign($this->node);
        $this->assertEquals($expected, ' ');
    }
}
