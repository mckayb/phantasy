<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Set\Set;
use function Phantasy\DataTypes\Set\Set;
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};
use function Phantasy\Core\concat;

class SetTest extends TestCase
{
    public function testSetFunc()
    {
        $this->assertEquals(new Set(1, 2, 3), Set(1, 2, 3));
    }

    public function testSetUniqueness()
    {
        $this->assertEquals(Set('foo', 'bar', 'foo', 'baz'), Set('foo', 'bar', 'baz'));
    }

    public function testSetEquals()
    {
        $this->assertTrue(Set('foo', 'bar')->equals(Set('bar', 'foo')));
    }

    public function testSetFromArray()
    {
        $this->assertEquals(Set::fromArray([1, 2, 3, 3]), Set(1, 2, 3));
    }

    public function testSetFromList()
    {
        $this->assertEquals(Set::fromList(Cons(1, Cons(2, Nil()))), Set(1, 2));
    }

    public function testSetEmpty()
    {
        $this->assertEquals(Set::empty(), Set());
        $this->assertEquals(Set::empty(), Set::fromArray([]));
        $this->assertEquals(Set::empty(), Set::fromList(Nil()));
    }

    public function testSetOf()
    {
        $this->assertEquals(Set::of(null), Set(null));
        $this->assertEquals(Set::of(concat()), Set(concat()));
        $this->assertEquals(Set::of('foo'), Set('foo'));
    }

    public function testSetMap()
    {
        $s = Set(1, 2, 3, 4)->map(function ($x) {
            return $x + 1;
        });

        $this->assertEquals($s, Set(2, 3, 4, 5));
    }
}
