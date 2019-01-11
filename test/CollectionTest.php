<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Collection\Collection;
use Phantasy\DataTypes\Set\Set;
use Phantasy\DataTypes\LinkedList\{LinkedList, Cons, Nil};
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\DataTypes\Maybe\{Just, Nothing};
use function Phantasy\DataTypes\Collection\Collection;
use Phantasy\Test\Traits\LawAssertions;
use function Phantasy\Core\concat;

class CollectionTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        // $this->assertSetoidLaws(Collection::of());
        // $this->assertSemigroupLaws(Collection::of());
        // $this->assertMonoidLaws(Collection::class, Collection::of());
        $this->assertFunctorLaws(Collection::of());
        // $this->assertApplyLaws(Collection::of());
        // $this->assertApplicativeLaws(Collection::class, Collection::of());
        // $this->assertAltLaws(Collection::of());
        // $this->assertPlusLaws(Collection::class, Collection::of());
        // $this->assertAlternativeLaws(Collection::class, Collection::of());
        // $this->assertChainLaws(Collection::of());
        // $this->assertMonadLaws(Collection::class, Collection::of());
        // $this->assertTraversableLaws(Collection::class, Collection::of());
    }

    public function testCollectionFunc()
    {
        $this->assertEquals(Collection(1, 2, 3), new Collection(1, 2, 3));
        $this->assertEquals(Collection(), new Collection());
    }

    public function testFromSet()
    {
        $this->assertEquals(Collection(1, 2, 3), Collection::fromSet(new Set(1, 2, 3, 1)));
        $this->assertEquals(Collection(), Collection::fromSet(new Set()));
    }

    public function testFromSetCurried()
    {
        $fromSet = Collection::fromSet();
        $this->assertEquals(Collection(1, 2, 3), $fromSet(new Set(1, 2, 3, 1)));
    }

    public function testFromArray()
    {
        $this->assertEquals(Collection(), Collection::fromArray([]));
        $this->assertEquals(Collection(1, 2, 3, 1), Collection::fromArray([1, 2, 3, 1]));
    }

    public function testFromArrayCurried()
    {
        $fromArray = Collection::fromArray();
        $this->assertEquals(Collection(), $fromArray([]));
        $this->assertEquals(Collection(1, 2, 3, 1), $fromArray([1, 2, 3, 1]));
    }

    public function testFromLinkedList()
    {
        $this->assertEquals(Collection(), Collection::fromLinkedList(new Nil()));
        $this->assertEquals(
            Collection(1, 2, 3, 1),
            Collection::fromLinkedList(new Cons(1, new Cons(2, new Cons(3, new Cons(1, new Nil())))))
        );
    }

    public function testFromLinkedListCurried()
    {
        $fromLL = Collection::fromLinkedList();
        $this->assertEquals(Collection(), $fromLL(new Nil()));
        $this->assertEquals(
            Collection(1, 2, 3, 1),
            $fromLL(new Cons(1, new Cons(2, new Cons(3, new Cons(1, new Nil())))))
        );
    }

    public function testOf()
    {
        $this->assertEquals(
            Collection::of(1),
            Collection(1)
        );
    }

    public function testOfCurried()
    {
        $of = Collection::of();
        $this->assertEquals(
            $of(1),
            Collection(1)
        );
    }

    public function testEmpty()
    {
        $this->assertEquals(Collection(), Collection::empty());
    }

    public function testMap()
    {
        $a = Collection(1, 2, 3)->map(function ($x) {
            return $x + 1;
        });
        $this->assertEquals(
            $a,
            Collection(2, 3, 4)
        );
    }

    public function testMapCurried()
    {
        $f = function ($x) {
            return $x + 1;
        };

        $a = Collection(1, 2, 3);
        $e = Collection(2, 3, 4);
        $map = $a->map;
        $map_ = $a->map();
        $map__ = $map();

        $this->assertEquals($map($f), $e);
        $this->assertEquals($map_($f), $e);
        $this->assertEquals($map__($f), $e);
    }

    public function testAp()
    {
        $f = function ($x) {
            return 'foo' . $x;
        };

        $g = function ($x) {
            return 'bar' . $x;
        };

        $this->assertEquals(
            Collection('baz', 'quux')->ap(Collection($f, $g)),
            Collection('foobaz', 'fooquux', 'barbaz', 'barquux')
        );
    }

    public function testApCurried()
    {
        $f = function ($x) {
            return 'foo' . $x;
        };

        $g = function ($x) {
            return 'bar' . $x;
        };

        $c = Collection('baz', 'quux');
        $e = Collection('foobaz', 'fooquux', 'barbaz', 'barquux');
        $ap = $c->ap;
        $ap_ = $c->ap();
        $ap__ = $ap();

        $this->assertEquals($ap(Collection($f, $g)), $e);
        $this->assertEquals($ap_(Collection($f, $g)), $e);
        $this->assertEquals($ap__(Collection($f, $g)), $e);
    }

    public function testChain()
    {
        $c = Collection(1, 2, 3)->chain(function ($x) {
            return Collection($x + 1);
        });
        $this->assertEquals($c, Collection(2, 3, 4));
    }

    public function testChainCurried()
    {
        $c = Collection(1, 2, 3);
        $e = Collection(2, 3, 4);
        $f = function ($x) {
            return Collection($x + 1);
        };

        $chain = $c->chain;
        $chain_ = $c->chain();
        $chain__ = $chain();

        $this->assertEquals($chain($f), $e);
        $this->assertEquals($chain_($f), $e);
        $this->assertEquals($chain__($f), $e);
    }

    public function testConcat()
    {
        $this->assertEquals(
            Collection('foo', 'bar')->concat(Collection('baz', 'quux')),
            Collection('foo', 'bar', 'baz', 'quux')
        );
    }

    public function testConcatCurried()
    {
        $a = Collection('foo', 'bar');
        $b = Collection('baz', 'quux');
        $e = Collection('foo', 'bar', 'baz', 'quux');

        $concat = $a->concat;
        $concat_ = $a->concat();
        $concat__ = $concat();

        $this->assertEquals($concat($b), $e);
        $this->assertEquals($concat_($b), $e);
        $this->assertEquals($concat__($b), $e);
    }

    public function testReduce()
    {
        $a = Collection(1, 2, 3, 4, 5)->reduce(function ($prev, $curr) {
            return $prev + $curr;
        }, 0);

        $this->assertEquals($a, 15);
    }

    public function testReduceCurried()
    {
        $a = Collection(1, 2, 3, 4, 5);
        $e = 15;
        $f = function ($prev, $curr) {
            return $prev + $curr;
        };

        $reduce = $a->reduce;
        $reduce_ = $a->reduce();
        $reduce__ = $reduce();

        $reduceF = $reduce($f);
        $reduceF_ = $reduce_($f);
        $reduceF__ = $reduce__($f);

        $this->assertEquals($reduce($f, 0), $e);
        $this->assertEquals($reduce_($f, 0), $e);
        $this->assertEquals($reduce__($f, 0), $e);
        $this->assertEquals($reduceF(0), $e);
        $this->assertEquals($reduceF_(0), $e);
        $this->assertEquals($reduceF__(0), $e);
    }

    public function testSequence()
    {
        $a = Collection(Just(1), Just(2), Just(3))->sequence(Maybe::of());
        $this->assertEquals($a, Just(Collection(1, 2, 3)));

        $b = Collection(Just(1), Just(2), Nothing())->sequence(Maybe::of());
        $this->assertEquals($b, Nothing());
    }

    public function testSequenceCurried()
    {
        $a = Collection(Just(1), Just(2), Just(3));
        $e = Just(Collection(1, 2, 3));

        $seq = $a->sequence;
        $seq_ = $a->sequence();
        $seq__ = $seq();

        $this->assertEquals($seq(Maybe::of()), $e);
        $this->assertEquals($seq_(Maybe::of()), $e);
        $this->assertEquals($seq__(Maybe::of()), $e);
    }

    public function testTraverse()
    {
        $a = Collection(1, 2, 3)->traverse(Maybe::of(), Maybe::fromNullable());
        $this->assertEquals($a, Just(Collection(1, 2, 3)));

        $b = Collection(1, 2, null)->traverse(Maybe::of(), Maybe::fromNullable());
        $this->assertEquals($b, Nothing());
    }

    public function testTraverseCurried()
    {
        $a = Collection(1, 2, 3, null);
        $b = Collection(1, 2, 3);
        $ae = Nothing();
        $be = Just(Collection(1, 2, 3));
        $f = Maybe::fromNullable();

        $atraverse = $a->traverse;
        $atraverse_ = $a->traverse();
        $atraverse__ = $atraverse();

        $atraverseMaybe = $atraverse(Maybe::of());
        $atraverseMaybe_ = $atraverse_(Maybe::of());
        $atraverseMaybe__ = $atraverse__(Maybe::of());

        $this->assertEquals($atraverse(Maybe::of(), $f), $ae);
        $this->assertEquals($atraverse_(Maybe::of(), $f), $ae);
        $this->assertEquals($atraverse__(Maybe::of(), $f), $ae);
        $this->assertEquals($atraverseMaybe($f), $ae);
        $this->assertEquals($atraverseMaybe_($f), $ae);
        $this->assertEquals($atraverseMaybe__($f), $ae);

        $btraverse = $b->traverse;
        $btraverse_ = $b->traverse();
        $btraverse__ = $btraverse();

        $btraverseMaybe = $btraverse(Maybe::of());
        $btraverseMaybe_ = $btraverse_(Maybe::of());
        $btraverseMaybe__ = $btraverse__(Maybe::of());

        $this->assertEquals($btraverse(Maybe::of(), $f), $be);
        $this->assertEquals($btraverse_(Maybe::of(), $f), $be);
        $this->assertEquals($btraverse__(Maybe::of(), $f), $be);
        $this->assertEquals($btraverseMaybe($f), $be);
        $this->assertEquals($btraverseMaybe_($f), $be);
        $this->assertEquals($btraverseMaybe__($f), $be);
    }

    public function testEquals()
    {
        $this->assertTrue(Collection(1, 2, 3)->equals(Collection(1, 2, 3)));
        $this->assertFalse(Collection(1, 2, 3)->equals(Collection(2, 1, 3)));
    }

    public function testEqualsCurried()
    {
        $a = Collection(1, 2, 3);
        $b = Collection(2, 3, 1);
        $eq = $a->equals;
        $eq_ = $a->equals();
        $eq__ = $eq();

        $this->assertTrue($eq($a));
        $this->assertTrue($eq_($a));
        $this->assertTrue($eq__($a));
        $this->assertFalse($eq($b));
        $this->assertFalse($eq_($b));
        $this->assertFalse($eq__($b));
    }

    public function testJoin()
    {
        $this->assertEquals(
            Collection(Collection(1), Collection(2), Collection(3))->join(),
            Collection(1, 2, 3)
        );
    }

    public function testBind()
    {
        $c = Collection(1, 2, 3)->bind(function ($x) {
            return Collection($x + 1);
        });
        $this->assertEquals($c, Collection(2, 3, 4));
    }

    public function testBindCurried()
    {
        $c = Collection(1, 2, 3);
        $e = Collection(2, 3, 4);
        $f = function ($x) {
            return Collection($x + 1);
        };

        $chain = $c->bind;
        $chain_ = $c->bind();
        $chain__ = $chain();

        $this->assertEquals($chain($f), $e);
        $this->assertEquals($chain_($f), $e);
        $this->assertEquals($chain__($f), $e);
    }

    public function testFlatMap()
    {
        $c = Collection(1, 2, 3)->flatMap(function ($x) {
            return Collection($x + 1);
        });
        $this->assertEquals($c, Collection(2, 3, 4));
    }

    public function testFlatMapCurried()
    {
        $c = Collection(1, 2, 3);
        $e = Collection(2, 3, 4);
        $f = function ($x) {
            return Collection($x + 1);
        };

        $chain = $c->flatMap;
        $chain_ = $c->flatMap();
        $chain__ = $chain();

        $this->assertEquals($chain($f), $e);
        $this->assertEquals($chain_($f), $e);
        $this->assertEquals($chain__($f), $e);
    }

    public function testToArray()
    {
        $this->assertEquals(
            Collection(1, 2, 3)->toArray(),
            [1, 2, 3]
        );
    }

    public function testToSet()
    {
        $this->assertEquals(
            Collection(1, 2, 3, 1)->toSet(),
            new Set(1, 2, 3)
        );
    }

    public function testToLinkedList()
    {
        $this->assertEquals(
            Collection(1, 2, 3)->toLinkedList(),
            new Cons(1, new Cons(2, new Cons(3, new Nil())))
        );
    }

    public function testHead()
    {
        $this->assertEquals(
            Collection(1, 2)->head(),
            1
        );

        $this->assertNull(Collection()->head());
    }

    public function testTail()
    {
        $this->assertEquals(
            Collection()->tail(),
            Collection()
        );

        $this->assertEquals(
            Collection(1, 2)->tail(),
            Collection(2)
        );

        $this->assertEquals(
            Collection(1)->tail(),
            Collection()
        );
    }

    public function testToString()
    {
        $a = Collection([1, 2]);
        $b = Collection('foo');
        $c = Collection(12);

        $expectedA = "Collection(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Collection('foo')";
        $expectedC = "Collection(12)";
        ob_start();
        echo $a;
        $actualA = ob_get_contents();
        ob_end_clean();

        ob_start();
        echo $b;
        $actualB = ob_get_contents();
        ob_end_clean();

        ob_start();
        echo $c;
        $actualC = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expectedA, $actualA);
        $this->assertEquals($expectedB, $actualB);
        $this->assertEquals($expectedC, $actualC);
    }
}
