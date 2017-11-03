<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\LinkedList\{LinkedList, Cons, Nil};
use Phantasy\DataTypes\Either\{Either, Right};
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\Core\identity;
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};
use function Phantasy\DataTypes\Maybe\{Just, Nothing};
use function Phantasy\DataTypes\Either\Right;
use function Phantasy\DataTypes\Collection\Collection;
use function Phantasy\DataTypes\Set\Set;
use Phantasy\Test\Traits\LawAssertions;

class LinkedListTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $f = function ($x) {
            return Cons($x, Nil());
        };
        $g = function ($x) {
            return Nil();
        };

        $this->assertFunctorLaws($f);
        $this->assertFunctorLaws($g);
        $this->assertApplyLaws($f);
        $this->assertApplyLaws($g);
        $this->assertApplicativeLaws(LinkedList::class, $f);
        $this->assertApplicativeLaws(LinkedList::class, $g);
        $this->assertAltLaws($f);
        $this->assertAltLaws($g);
    }

    public function testConsNilFunc()
    {
        $this->assertEquals(Cons(12, Nil()), new Cons(12, new Nil()));
    }

    public function testConsCurried()
    {
        $cons = Cons();
        $this->assertEquals($cons(12, Nil()), new Cons(12, new Nil()));
    }

    public function testLinkedListOf()
    {
        $a = LinkedList::of(2);

        $this->assertInstanceOf(Cons::class, $a);
        $this->assertEquals($a, new Cons(2, new Nil()));
    }

    public function testLinkedListOfCurried()
    {
        $ll = LinkedList::of();
        $this->assertEquals($ll(12), Cons(12, Nil()));
    }

    public function testLinkedListFromArray()
    {
        $a = LinkedList::fromArray([1, 2, 3]);
        $this->assertInstanceOf(Cons::class, $a);
        $this->assertEquals($a, new Cons(1, new Cons(2, new Cons(3, new Nil()))));
    }

    public function testLinkedListFromArrayCurried()
    {
        $llfa = LinkedList::fromArray();
        $this->assertEquals($llfa([1, 2]), Cons(1, Cons(2, Nil())));
    }

    public function testLinkedListEmpty()
    {
        $this->assertEquals(LinkedList::empty(), new Nil());
    }

    public function testConsEquals()
    {
        $this->assertTrue(Cons(1, Nil())->equals(Cons(1, Nil())));
        $this->assertFalse(Cons(1, Nil())->equals(Cons(2, Nil())));
        $this->assertFalse(Cons(1, Nil())->equals(Nil()));
    }

    public function testNilEquals()
    {
        $this->assertTrue(Nil()->equals(Nil()));
        $this->assertFalse(Nil()->equals(Cons(1, Nil())));
    }

    public function testConsMap()
    {
        $a = LinkedList::of(2)->map(function ($x) {
            return $x + 1;
        });

        $this->assertInstanceOf(Cons::class, $a);
        $this->assertEquals($a, new Cons(3, new Nil()));
    }

    public function testConsMapCurried()
    {
        $a = LinkedList::of(2);
        $mapA = $a->map;
        $mapA_ = $a->map();
        $mapA__ = $mapA_();

        $f = function ($x) {
            return $x + 1;
        };

        $this->assertEquals($mapA($f), Cons(3, Nil()));
        $this->assertEquals($mapA_($f), Cons(3, Nil()));
        $this->assertEquals($mapA__($f), Cons(3, Nil()));
    }

    public function testNilMap()
    {
        $a = (new Nil())->map(function ($x) {
            return $x + 1;
        });

        $this->assertInstanceOf(Nil::class, $a);
        $this->assertEquals($a, new Nil());
    }

    public function testNilMapCurried()
    {
        $a = Nil();
        $mapA = $a->map;
        $mapA_ = $a->map();
        $mapA__ = $mapA_();

        $f = function ($x) {
            return $x + 1;
        };

        $this->assertEquals($mapA($f), Nil());
        $this->assertEquals($mapA_($f), Nil());
        $this->assertEquals($mapA__($f), Nil());
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

    public function testConsConcatCurried()
    {
        $a = Cons(1, Nil());
        $b = Cons(2, Nil());

        $concat = $a->concat;
        $concat_ = $a->concat();
        $concat__ = $concat();

        $expected = Cons(1, Cons(2, Nil()));

        $this->assertEquals($concat($b), $expected);
        $this->assertEquals($concat_($b), $expected);
        $this->assertEquals($concat__($b), $expected);
    }

    public function testNilConcatCurried()
    {
        $a = Nil();
        $b = Cons(2, Nil());

        $concat = $a->concat;
        $concat_ = $a->concat();
        $concat__ = $concat();

        $expected = Cons(2, Nil());

        $this->assertEquals($concat($b), $expected);
        $this->assertEquals($concat_($b), $expected);
        $this->assertEquals($concat__($b), $expected);
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

    public function testConsApCurried()
    {
        $a = LinkedList::of(2);
        $b = LinkedList::of(function ($x) {
            return $x + 1;
        });

        $apToA = $a->ap;
        $apToA_ = $a->ap();
        $apToA__ = $apToA();

        $this->assertEquals($apToA($b), new Cons(3, new Nil()));
        $this->assertEquals($apToA_($b), new Cons(3, new Nil()));
        $this->assertEquals($apToA__($b), new Cons(3, new Nil()));
    }

    public function testNilAp()
    {
        $a = new Nil();
        $b = LinkedList::of(2);
        $this->assertEquals($a->ap($b), new Nil());
    }

    public function testNilApCurried()
    {
        $a = Nil();
        $b = LinkedList::of(2);

        $apToA = $a->ap;
        $apToA_ = $a->ap();
        $apToA__ = $apToA();

        $this->assertEquals($apToA($b), Nil());
        $this->assertEquals($apToA_($b), Nil());
        $this->assertEquals($apToA__($b), Nil());
    }

    public function testConsChain()
    {
        $a = LinkedList::of(2);
        $this->assertEquals($a->chain(function ($x) {
            return LinkedList::of(3);
        }), new Cons(3, new Nil()));
    }

    public function testConsChainCurried()
    {
        $a = LinkedList::of(2);
        $chain = $a->chain;
        $chain_ = $a->chain();
        $chain__ = $chain();

        $f = function ($x) {
            return LinkedList::of($x + 1);
        };

        $this->assertEquals($chain($f), Cons(3, Nil()));
        $this->assertEquals($chain_($f), Cons(3, Nil()));
        $this->assertEquals($chain__($f), Cons(3, Nil()));
    }

    public function testConsBind()
    {
        $a = LinkedList::of(2);
        $this->assertEquals($a->bind(function ($x) {
            return LinkedList::of(3);
        }), new Cons(3, new Nil()));
    }

    public function testConsBindCurried()
    {
        $a = LinkedList::of(2);
        $chain = $a->bind;
        $chain_ = $a->bind();
        $chain__ = $chain();

        $f = function ($x) {
            return LinkedList::of($x + 1);
        };

        $this->assertEquals($chain($f), Cons(3, Nil()));
        $this->assertEquals($chain_($f), Cons(3, Nil()));
        $this->assertEquals($chain__($f), Cons(3, Nil()));
    }

    public function testConsFlatMap()
    {
        $a = LinkedList::of(2);
        $this->assertEquals($a->flatMap(function ($x) {
            return LinkedList::of(3);
        }), new Cons(3, new Nil()));
    }

    public function testConsFlatMapCurried()
    {
        $a = LinkedList::of(2);
        $chain = $a->flatMap;
        $chain_ = $a->flatMap();
        $chain__ = $chain();

        $f = function ($x) {
            return LinkedList::of($x + 1);
        };

        $this->assertEquals($chain($f), Cons(3, Nil()));
        $this->assertEquals($chain_($f), Cons(3, Nil()));
        $this->assertEquals($chain__($f), Cons(3, Nil()));
    }

    public function testNilChain()
    {
        $a = new Nil();
        $this->assertEquals($a->chain(function ($x) {
            return LinkedList::of(3);
        }), new Nil());
    }

    public function testNilChainCurried()
    {
        $a = Nil();
        $chain = $a->chain;
        $chain_ = $a->chain();
        $chain__ = $chain();

        $f = function ($x) {
            return LinkedList::of($x + 1);
        };

        $this->assertEquals($chain($f), Nil());
        $this->assertEquals($chain_($f), Nil());
        $this->assertEquals($chain__($f), Nil());
    }

    public function testNilBind()
    {
        $a = new Nil();
        $this->assertEquals($a->bind(function ($x) {
            return LinkedList::of(3);
        }), new Nil());
    }

    public function testNilBindCurried()
    {
        $a = Nil();
        $chain = $a->bind;
        $chain_ = $a->bind();
        $chain__ = $chain();

        $f = function ($x) {
            return LinkedList::of($x + 1);
        };

        $this->assertEquals($chain($f), Nil());
        $this->assertEquals($chain_($f), Nil());
        $this->assertEquals($chain__($f), Nil());
    }

    public function testNilFlatMap()
    {
        $a = new Nil();
        $this->assertEquals($a->flatMap(function ($x) {
            return LinkedList::of(3);
        }), new Nil());
    }

    public function testNilFlatMapCurried()
    {
        $a = Nil();
        $chain = $a->flatMap;
        $chain_ = $a->flatMap();
        $chain__ = $chain();

        $f = function ($x) {
            return LinkedList::of($x + 1);
        };

        $this->assertEquals($chain($f), Nil());
        $this->assertEquals($chain_($f), Nil());
        $this->assertEquals($chain__($f), Nil());
    }

    public function testConsReduce()
    {
        $a = new Cons(1, new Nil());

        $this->assertEquals($a->reduce(function ($prev, $curr) {
            return $prev + $curr;
        }, 1), 2);
    }

    public function testConsReduceCurried()
    {
        $a = Cons(1, Cons(2, Nil()));
        $reduce = $a->reduce;
        $reduce_ = $a->reduce();
        $reduce__ = $reduce();

        $f = function ($prev, $curr) {
            return $prev + $curr;
        };

        $reduce___ = $a->reduce($f);
        $reduce____ = $a->reduce($f, 2);

        $this->assertEquals($reduce($f, 0), 3);
        $this->assertEquals($reduce_($f, 0), 3);
        $this->assertEquals($reduce__($f, 0), 3);
        $this->assertEquals($reduce___(0), 3);
        $this->assertEquals($reduce____, 5);
    }

    public function testNilReduce()
    {
        $a = new Nil();
        $this->assertEquals($a->reduce(function ($prev, $curr) {
            return $prev - $curr;
        }, 3), 3);
    }

    public function testNilReduceCurried()
    {
        $a = Nil();
        $reduce = $a->reduce;
        $reduce_ = $a->reduce();
        $reduce__ = $reduce();

        $f = function ($prev, $curr) {
            return $prev + $curr;
        };

        $reduce___ = $a->reduce($f);
        $reduce____ = $a->reduce($f, 2);

        $this->assertEquals($reduce($f, 0), 0);
        $this->assertEquals($reduce_($f, 0), 0);
        $this->assertEquals($reduce__($f, 0), 0);
        $this->assertEquals($reduce___(0), 0);
        $this->assertEquals($reduce____, 2);
    }

    public function testConsTraverse()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $this->assertEquals(
            $a->traverse(Either::class, function ($x) {
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
            $a->traverse(Either::class, $toChar),
            new Right(new Cons('A', new Cons('B', new Cons('C', new Cons('D', new Nil())))))
        );
    }

    public function testConsTraverseIdentity()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));

        $this->assertEquals(
            $a->traverse(Either::class, Either::of()),
            Either::of($a)
        );
    }

    public function testConsTraverseNaturality()
    {
        $u = LinkedList::of(Either::of(1));
        $t = function ($m) {
            return $m->toMaybe();
        };
        $F = Either::class;
        $G = Maybe::class;
        $this->assertEquals(
            $t($u->traverse($F, identity())),
            $u->traverse($G, $t)
        );
    }

    public function testConsTraverseCurried()
    {
        $u = Cons(Right(1), Cons(Right(2), Nil()));
        $t = function ($m) {
            return $m->toMaybe();
        };
        $G = Maybe::class;
        $traverse = $u->traverse;
        $traverse_ = $u->traverse();
        $traverse__ = $traverse();

        $traverseMaybe = $u->traverse($G);
        $traverseMaybe_ = $traverse_($G);
        $traverseMaybe__ = $traverse__($G);

        $expected = Just(Cons(1, Cons(2, Nil())));

        $this->assertEquals($u->traverse($G, $t), $expected);
        $this->assertEquals($traverse($G, $t), $expected);
        $this->assertEquals($traverse_($G, $t), $expected);
        $this->assertEquals($traverse__($G, $t), $expected);
        $this->assertEquals($traverseMaybe($t), $expected);
        $this->assertEquals($traverseMaybe_($t), $expected);
        $this->assertEquals($traverseMaybe__($t), $expected);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConsTraverseInvalidClass()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $a->traverse('Foo\Bar', Either::of());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNilTraverseInvalidClass()
    {
        Nil()->traverse('Foo\Bar', Either::of());
    }

    public function testNilTraverse()
    {
        $a = new Nil();
        $this->assertEquals(
            $a->traverse(Either::class, function ($x) {
                return $x + 1;
            }),
            new Right(new Nil())
        );
    }

    public function testNilTraverseIdentity()
    {
        $a = new Nil();

        $this->assertEquals(
            $a->traverse(Either::class, identity()),
            Either::of($a)
        );
    }

    public function testNilTraverseNaturality()
    {
        $u = new Nil();
        $t = function ($m) {
            return $m->toMaybe();
        };
        $F = Either::class;
        $G = Maybe::class;
        $this->assertEquals(
            $t($u->traverse($F, identity())),
            $u->traverse($G, $t)
        );
    }

    public function testNilTraverseCurried()
    {
        $u = new Nil();
        $t = function ($m) {
            return $m->toMaybe();
        };
        $G = Maybe::class;

        $traverse = $u->traverse;
        $traverse_ = $u->traverse();
        $traverse__ = $traverse();

        $traverseMaybe = $u->traverse($G);
        $traverseMaybe_ = $traverse_($G);
        $traverseMaybe__ = $traverse__($G);

        $expected = Just(Nil());

        $this->assertEquals($u->traverse($G, $t), $expected);
        $this->assertEquals($traverse($G, $t), $expected);
        $this->assertEquals($traverse_($G, $t), $expected);
        $this->assertEquals($traverse__($G, $t), $expected);
        $this->assertEquals($traverseMaybe($t), $expected);
        $this->assertEquals($traverseMaybe_($t), $expected);
        $this->assertEquals($traverseMaybe__($t), $expected);
    }

    public function testConsSequence()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $this->assertEquals(
            $a->sequence(Either::class),
            new Right(new Cons(1, new Cons(2, new Nil())))
        );
    }

    public function testConsSequenceCurried()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $expected = new Right(new Cons(1, new Cons(2, new Nil())));
        $seq = $a->sequence;
        $seq_ = $a->sequence();
        $seq__ = $seq();
        $this->assertEquals($seq(Either::class), $expected);
        $this->assertEquals($seq_(Either::class), $expected);
        $this->assertEquals($seq__(Either::class), $expected);
    }

    public function testNilSequence()
    {
        $a = new Nil();
        $this->assertEquals(
            $a->sequence(Either::class),
            new Right(new Nil())
        );
    }

    public function testNilSequenceCurried()
    {
        $a = new Nil();
        $expected = new Right(new Nil());
        $seq = $a->sequence;
        $seq_ = $a->sequence();
        $seq__ = $seq();
        $this->assertEquals($seq(Either::class), $expected);
        $this->assertEquals($seq_(Either::class), $expected);
        $this->assertEquals($seq__(Either::class), $expected);
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

    public function testConsHead()
    {
        $this->assertEquals(Cons(1, Nil())->head(), 1);
        $this->assertEquals(Cons(1, Cons(2, Nil()))->head(), 1);
    }

    public function testConsTail()
    {
        $this->assertEquals(Cons(1, Cons(2, Nil()))->tail(), Cons(2, Nil()));
        $this->assertEquals(Cons(1, Nil())->tail(), Nil());
    }

    public function testNilHead()
    {
        $this->assertNull(Nil()->head());
    }

    public function testNilTail()
    {
        $this->assertEquals(Nil()->tail(), Nil());
    }

    public function testConsToArray()
    {
        $this->assertEquals(Cons(1, Nil())->toArray(), [1]);
        $this->assertEquals(Cons(1, Cons(2, Nil()))->toArray(), [1, 2]);
        $this->assertEquals(Cons(1, Cons(2, Cons(1, Nil())))->toArray(), [1, 2, 1]);
    }

    public function testNilToArray()
    {
        $this->assertEquals(Nil()->toArray(), []);
    }

    public function testConsToSet()
    {
        $this->assertEquals(Cons(1, Nil())->toSet(), Set(1));
        $this->assertEquals(Cons(1, Cons(2, Nil()))->toSet(), Set(1, 2));
        $this->assertEquals(Cons(1, Cons(2, Cons(1, Nil())))->toSet(), Set(1, 2));
    }

    public function testNilToSet()
    {
        $this->assertEquals(Nil()->toSet(), Set());
    }

    public function testConsToCollection()
    {
        $this->assertEquals(Cons(1, Nil())->toCollection(), Collection(1));
        $this->assertEquals(Cons(1, Cons(2, Nil()))->toCollection(), Collection(1, 2));
        $this->assertEquals(Cons(1, Cons(2, Cons(1, Nil())))->toCollection(), Collection(1, 2, 1));
    }

    public function testNilToCollection()
    {
        $this->assertEquals(Nil()->toCollection(), Collection());
    }
}
