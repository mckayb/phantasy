<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\LinkedList\{LinkedList, Cons, Nil};
use Phantasy\DataTypes\Either\{Either, Right};
use function Phantasy\Core\identity;

class LinkedListTest extends TestCase
{
    public function testLinkedListOf()
    {
        $a = LinkedList::of(2);

        $this->assertInstanceOf(Cons::class, $a);
        $this->assertEquals($a, new Cons(2, new Nil()));
    }

    public function testLinkedListFromArray()
    {
        $a = LinkedList::fromArray([1, 2, 3]);
        $this->assertInstanceOf(Cons::class, $a);
        $this->assertEquals($a, new Cons(1, new Cons(2, new Cons(3, new Nil()))));
    }

    public function testConsMap()
    {
        $a = LinkedList::of(2)->map(function($x) {
            return $x + 1;
        });

        $this->assertInstanceOf(Cons::class, $a);
        $this->assertEquals($a, new Cons(3, new Nil()));
    }

    public function testNilMap()
    {
        $a = (new Nil())->map(function($x) {
            return $x + 1;
        });

        $this->assertInstanceOf(Nil::class, $a);
        $this->assertEquals($a, new Nil());
    }

    public function testConsConcatCons()
    {
        $a = LinkedList::of(2)->concat(LinkedList::of(3));
        $this->assertEquals($a, new Cons(2, new Cons(3, new Nil())));
    }

    public function testConsConcatNil()
    {
        $a = LinkedList::of(2)->concat(new Nil());
        $this->assertEquals($a, new Cons(2, new Nil()));
    }

    public function testNilConcatCons()
    {
        $a = (new Nil())->concat(LinkedList::of(3));
        $this->assertEquals($a, new Cons(3, new Nil()));
    }

    public function testNilConcatNil()
    {
        $a = (new Nil())->concat(new Nil());
        $this->assertEquals($a, new Nil());
    }

    public function testConsJoin()
    {
        $a = LinkedList::of(LinkedList::of(2));
        $this->assertEquals($a->join(), new Cons(2, new Nil()));
    }

    public function testNilJoin()
    {
        $a = new Nil();
        $this->assertEquals($a->join(), new Nil());
    }

    public function testConsAp()
    {
        $a = LinkedList::of(2);
        $b = LinkedList::of(function($x) {
            return $x + 1;
        });

        $this->assertEquals($a->ap($b), new Cons(3, new Nil()));
    }

    public function testConsApMultipleFunctions()
    {
        $a = LinkedList::fromArray(['1', '2', '3']);
        $b = LinkedList::fromArray([
            function($x) {
                return 'foo' . $x;
            },
            function($x) {
                return $x . '!';
            }
        ]);
        $this->assertEquals(
            $a->ap($b),
            new Cons('foo1', new Cons('foo2', new Cons('foo3', new Cons('1!', new Cons('2!', new Cons('3!', new Nil()))))))
        );
    }

    public function testNilAp()
    {
        $a = new Nil();
        $b = LinkedList::of(2);
        $this->assertEquals($a->ap($b), new Nil());
    }

    public function testConsChain()
    {
        $a = LinkedList::of(2);
        $this->assertEquals($a->chain(function($x) {
            return LinkedList::of(3);
        }), new Cons(3, new Nil()));
    }

    public function testNilChain()
    {
        $a = new Nil();
        $this->assertEquals($a->chain(function($x) {
            return LinkedList::of(3);
        }), new Nil());
    }

    public function testConsReduce()
    {
        $a = new Cons(1, new Nil());

        $this->assertEquals($a->reduce(function ($prev, $curr) {
            return $prev + $curr;
        }, 1), 2);
    }

    public function testNilReduce()
    {
        $a = new Nil();
        $this->assertEquals($a->reduce(function ($prev, $curr) {
            return $prev - $curr;
        }, 3), 3);
    }
}
