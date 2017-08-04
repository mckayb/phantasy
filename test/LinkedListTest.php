<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\LinkedList\{LinkedList, Cons, Nil};
use Phantasy\DataTypes\Either\{Either, Right};
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\Core\identity;
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};

class LinkedListTest extends TestCase
{
    public function testConsNilFunc()
    {
        $this->assertEquals(Cons(12, Nil()), new Cons(12, new Nil()));
    }

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

    public function testLinkedListEmpty()
    {
        $this->assertEquals(LinkedList::empty(), new Nil());
    }

    public function testConsMap()
    {
        $a = LinkedList::of(2)->map(function ($x) {
            return $x + 1;
        });

        $this->assertInstanceOf(Cons::class, $a);
        $this->assertEquals($a, new Cons(3, new Nil()));
    }

    public function testNilMap()
    {
        $a = (new Nil())->map(function ($x) {
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
        $b = LinkedList::of(function ($x) {
            return $x + 1;
        });

        $this->assertEquals($a->ap($b), new Cons(3, new Nil()));
    }

    public function testConsApMultipleFunctions()
    {
        $a = LinkedList::fromArray(['1', '2']);
        $b = LinkedList::fromArray([
            function ($x) {
                return 'foo' . $x;
            },
            function ($x) {
                return $x . '!';
            }
        ]);
        $this->assertEquals(
            $a->ap($b),
            new Cons('foo1', new Cons('foo2', new Cons('1!', new Cons('2!', new Nil()))))
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
        $this->assertEquals($a->chain(function ($x) {
            return LinkedList::of(3);
        }), new Cons(3, new Nil()));
    }

    public function testNilChain()
    {
        $a = new Nil();
        $this->assertEquals($a->chain(function ($x) {
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

    public function testConsTraverse()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $this->assertEquals(
            $a->traverse(Either::of(), function ($x) {
                return $x->map(function ($y) {
                    return $y + 1;
                });
            }),
            new Right(new Cons(2, new Cons(3, new Nil())))
        );
    }

    public function testConsTraverseIntList()
    {
        $a = new Cons(0, new Cons(1, new Cons(2, new Cons(3, new Nil()))));
        $toChar = function ($n) {
            return $n < 0 || $n > 25
                ? new Left($n . ' is out of bounds!')
                : new Right(chr(833 + $n));
        };

        $this->assertEquals(
            $a->traverse(Either::of(), $toChar),
            new Right(new Cons('A', new Cons('B', new Cons('C', new Cons('D', new Nil())))))
        );
    }

    public function testConsTraverseIdentity()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));

        $this->assertEquals(
            $a->traverse(Either::of(), Either::of()),
            Either::of($a)
        );
    }

    public function testConsTraverseNaturality()
    {
        $u = LinkedList::of(Either::of(1));
        $t = function ($m) {
            return $m->toMaybe();
        };
        $F = Either::of();
        $G = Maybe::of();
        $this->assertEquals(
            $t($u->traverse($F, identity())),
            $u->traverse($G, $t)
        );
    }

    public function testNilTraverse()
    {
        $a = new Nil();
        $this->assertEquals(
            $a->traverse(Either::of(), function ($x) {
                return $x + 1;
            }),
            new Right(new Nil())
        );
    }

    public function testNilTraverseIdentity()
    {
        $a = new Nil();

        $this->assertEquals(
            $a->traverse(Either::of(), identity()),
            Either::of($a)
        );
    }

    public function testNilTraverseNaturality()
    {
        $u = new Nil();
        $t = function ($m) {
            return $m->toMaybe();
        };
        $F = Either::of();
        $G = Maybe::of();
        $this->assertEquals(
            $t($u->traverse($F, identity())),
            $u->traverse($G, $t)
        );
    }

    public function testConsSequence()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $this->assertEquals(
            $a->sequence(Either::of()),
            new Right(new Cons(1, new Cons(2, new Nil())))
        );
    }

    public function testNilSequence()
    {
        $a = new Nil();
        $this->assertEquals(
            $a->sequence(Either::of()),
            new Right(new Nil())
        );
    }

    public function testConsToString()
    {
        $a = new Cons(1, new Cons(2, new Cons(3, new Nil())));
        $expectedA = 'Cons(1, Cons(2, Cons(3, Nil())))';
        ob_start();
        echo $a;
        $actualA = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expectedA, $actualA);
    }

    public function testNilToString()
    {
        $a = new Nil();
        $expectedA = 'Nil()';
        ob_start();
        echo $a;
        $actualA = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expectedA, $actualA);
    }
}
