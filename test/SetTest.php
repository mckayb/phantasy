<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Set\Set;
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\DataTypes\Set\Set;
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};
use function Phantasy\DataTypes\Collection\Collection;
use function Phantasy\DataTypes\Maybe\{Just, Nothing};
use function Phantasy\Core\concat;
use Phantasy\Test\Traits\LawAssertions;

class SetTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $this->assertFunctorLaws(Set::of());
        $this->assertApplyLaws(Set::of());
        $this->assertApplicativeLaws(Set::class, Set::of());
    }

    public function testFunc()
    {
        $this->assertEquals(new Set(1, 2, 3), Set(1, 2, 3));
    }

    public function testUniqueness()
    {
        $this->assertEquals(Set('foo', 'bar', 'foo', 'baz'), Set('foo', 'bar', 'baz'));
    }

    public function testEquals()
    {
        $this->assertTrue(Set('foo', 'bar')->equals(Set('bar', 'foo')));
    }

    public function testEqualsCurried()
    {
        $a = Set('foo', 'bar');
        $b = Set('bar', 'foo');

        $eq = $a->equals;
        $eq_ = $a->equals();
        $eq__ = $eq();

        $this->assertTrue($eq($b));
        $this->assertTrue($eq_($b));
        $this->assertTrue($eq__($b));
    }

    public function testFromArray()
    {
        $this->assertEquals(Set::fromArray([1, 2, 3, 3]), Set(1, 2, 3));
    }

    public function testFromArrayCurried()
    {
        $fromArray = Set::fromArray();
        $this->assertEquals($fromArray([1, 2, 3, 3]), Set(1, 2, 3));
    }

    public function testFromLinkedList()
    {
        $this->assertEquals(Set::fromLinkedList(Cons(1, Cons(2, Nil()))), Set(1, 2));
    }

    public function testFromLinkedListCurried()
    {
        $fromLL = Set::fromLinkedList();
        $this->assertEquals($fromLL(Cons(1, Cons(2, Nil()))), Set(1, 2));
    }

    public function testFromCollection()
    {
        $this->assertEquals(Set::fromCollection(Collection(1, 2, 3, 1)), Set(1, 2, 3));
    }

    public function testFromCollectionCurried()
    {
        $fromColl = Set::fromCollection();
        $this->assertEquals($fromColl(Collection(1, 2, 3, 1)), Set(1, 2, 3));
    }

    public function testEmpty()
    {
        $this->assertEquals(Set::empty(), Set());
        $this->assertEquals(Set::empty(), Set::fromArray([]));
        $this->assertEquals(Set::empty(), Set::fromLinkedList(Nil()));
    }

    public function testOf()
    {
        $this->assertEquals(Set::of(null), Set(null));
        $this->assertEquals(Set::of(concat()), Set(concat()));
        $this->assertEquals(Set::of('foo'), Set('foo'));
    }

    public function testOfCurried()
    {
        $of = Set::of();
        $this->assertEquals($of('foo'), Set('foo'));
    }

    public function testMap()
    {
        $s = Set(1, 2, 3, 4)->map(function ($x) {
            return $x + 1;
        });

        $this->assertEquals($s, Set(2, 3, 4, 5));
    }

    public function testMapCurried()
    {
        $s = Set(1, 2, 3, 4);
        $f = function ($x) {
            return $x + 1;
        };
        $e = Set(2, 3, 4, 5);
        $map = $s->map;
        $map_ = $s->map();
        $map__ = $map();

        $this->assertEquals($map($f), $e);
        $this->assertEquals($map_($f), $e);
        $this->assertEquals($map__($f), $e);
    }

    public function testAp()
    {
        $a = Set('foo', 'bar');
        $f = concat('baz');
        $g = concat('quux');
        $b = Set($f, $g);
        $e = Set('bazfoo', 'bazbar', 'quuxfoo', 'quuxbar');
        $this->assertEquals($a->ap($b), $e);
    }

    public function testApCurried()
    {
        $a = Set('foo', 'bar');
        $f = concat('baz');
        $g = concat('quux');
        $b = Set($f, $g);
        $e = Set('bazfoo', 'bazbar', 'quuxfoo', 'quuxbar');

        $ap = $a->ap;
        $ap_ = $a->ap();
        $ap__ = $ap();

        $this->assertEquals($ap($b), $e);
        $this->assertEquals($ap_($b), $e);
        $this->assertEquals($ap__($b), $e);
    }

    public function testChain()
    {
        $this->assertEquals(
            Set(1, 2, 3)->chain(function ($x) {
                return Set($x + 1);
            }),
            Set(2, 3, 4)
        );
    }

    public function testChainCurried()
    {
        $a = Set(1, 2, 3);
        $chain = $a->chain;
        $chain_ = $a->chain();
        $chain__ = $chain();
        $f = function ($x) {
            return Set($x + 1);
        };

        $e = Set(2, 3, 4);

        $this->assertEquals($chain($f), $e);
        $this->assertEquals($chain_($f), $e);
        $this->assertEquals($chain__($f), $e);
    }

    public function testBind()
    {
        $this->assertEquals(
            Set(1, 2, 3)->bind(function ($x) {
                return Set($x + 1);
            }),
            Set(2, 3, 4)
        );
    }

    public function testBindCurried()
    {
        $a = Set(1, 2, 3);
        $chain = $a->bind;
        $chain_ = $a->bind();
        $chain__ = $chain();
        $f = function ($x) {
            return Set($x + 1);
        };

        $e = Set(2, 3, 4);

        $this->assertEquals($chain($f), $e);
        $this->assertEquals($chain_($f), $e);
        $this->assertEquals($chain__($f), $e);
    }

    public function testFlatMap()
    {
        $this->assertEquals(
            Set(1, 2, 3)->flatMap(function ($x) {
                return Set($x + 1);
            }),
            Set(2, 3, 4)
        );
    }

    public function testFlatMapCurried()
    {
        $a = Set(1, 2, 3);
        $chain = $a->flatMap;
        $chain_ = $a->flatMap();
        $chain__ = $chain();
        $f = function ($x) {
            return Set($x + 1);
        };

        $e = Set(2, 3, 4);

        $this->assertEquals($chain($f), $e);
        $this->assertEquals($chain_($f), $e);
        $this->assertEquals($chain__($f), $e);
    }
    
    public function testJoin()
    {
        $this->assertEquals(
            Set(Set(1), Set(2), Set(3))->join(),
            Set(1, 2, 3)
        );
    }

    public function testConcat()
    {
        $this->assertEquals(
            Set(1)->concat(Set(2)),
            Set(1, 2)
        );

        $this->assertEquals(
            Set(1)->concat(Set(1)),
            Set(1)
        );
    }

    public function testConcatCurried()
    {
        $a = Set(1);
        $concat = $a->concat;
        $concat_ = $a->concat();
        $concat__ = $concat();

        $this->assertEquals($concat(Set(2)), Set(1, 2));
        $this->assertEquals($concat_(Set(2)), Set(1, 2));
        $this->assertEquals($concat__(Set(2)), Set(1, 2));
    }

    public function testSequence()
    {
        $a = Set(Just(1), Just(2), Just(3));
        $ae = Just(Set(1, 2, 3));

        $b = Set(Just(1), Just(2), Nothing());
        $be = Nothing();

        $this->assertEquals($a->sequence(Maybe::of()), $ae);
        $this->assertEquals($b->sequence(Maybe::of()), $be);
    }

    public function testSequenceCurried()
    {
        $a = Set(Just(1), Just(2), Just(3));
        $ae = Just(Set(1, 2, 3));

        $aseq = $a->sequence;
        $aseq_ = $a->sequence();
        $aseq__ = $aseq();

        $this->assertEquals($aseq(Maybe::of()), $ae);
        $this->assertEquals($aseq_(Maybe::of()), $ae);
        $this->assertEquals($aseq__(Maybe::of()), $ae);

        $b = Set(Just(1), Just(2), Nothing());
        $be = Nothing();

        $bseq = $b->sequence;
        $bseq_ = $b->sequence();
        $bseq__ = $bseq();

        $this->assertEquals($bseq(Maybe::of()), $be);
        $this->assertEquals($bseq_(Maybe::of()), $be);
        $this->assertEquals($bseq__(Maybe::of()), $be);
    }

    public function testTraverse()
    {
        $this->assertEquals(
            Set(1, 2, 3)->traverse(Maybe::of(), Maybe::fromNullable()),
            Just(Set(1, 2, 3))
        );

        $this->assertEquals(
            Set(1, 2, 3, null)->traverse(Maybe::of(), Maybe::fromNullable()),
            Nothing()
        );
    }

    public function testTraverseCurried()
    {
        $f = Maybe::fromNullable();
        $a = Set(1, 2, 3);
        $ae = Just(Set(1, 2, 3));

        $atrav = $a->traverse;
        $atrav_ = $a->traverse();
        $atrav__ = $atrav();

        $atravM = $atrav(Maybe::of());
        $atravM_ = $atrav_(Maybe::of());
        $atravM__ = $atrav__(Maybe::of());

        $this->assertEquals($atrav(Maybe::of(), $f), $ae);
        $this->assertEquals($atrav_(Maybe::of(), $f), $ae);
        $this->assertEquals($atrav__(Maybe::of(), $f), $ae);
        $this->assertEquals($atravM($f), $ae);
        $this->assertEquals($atravM_($f), $ae);
        $this->assertEquals($atravM__($f), $ae);

        $b = Set(1, 2, null);
        $be = Nothing();

        $btrav = $b->traverse;
        $btrav_ = $b->traverse();
        $btrav__ = $btrav();

        $btravM = $btrav(Maybe::of());
        $btravM_ = $btrav_(Maybe::of());
        $btravM__ = $btrav__(Maybe::of());

        $this->assertEquals($btrav(Maybe::of(), $f), $be);
        $this->assertEquals($btrav_(Maybe::of(), $f), $be);
        $this->assertEquals($btrav__(Maybe::of(), $f), $be);
        $this->assertEquals($btravM($f), $be);
        $this->assertEquals($btravM_($f), $be);
        $this->assertEquals($btravM__($f), $be);
    }

    public function testReduce()
    {
        $this->assertEquals(
            Set(1, 2, 3, 4, 5)->reduce(function ($prev, $curr) {
                return $prev + $curr;
            }, 0),
            15
        );
    }

    public function testReduceCurried()
    {
        $a = Set(1, 2, 3, 4, 5);
        $e = 15;
        $f = function ($prev, $curr) {
            return $prev + $curr;
        };
        $r = $a->reduce;
        $r_ = $a->reduce();
        $r__ = $r();

        $rf = $r($f);
        $rf_ = $r_($f);
        $rf__ = $r__($f);

        $this->assertEquals($r($f, 0), $e);
        $this->assertEquals($r_($f, 0), $e);
        $this->assertEquals($r__($f, 0), $e);
        $this->assertEquals($rf(0), $e);
        $this->assertEquals($rf_(0), $e);
        $this->assertEquals($rf__(0), $e);
    }

    public function testUnion()
    {
        $this->assertEquals(
            Set(1)->union(Set(2)),
            Set(1, 2)
        );

        $this->assertEquals(
            Set(1)->union(Set(1)),
            Set(1)
        );
    }

    public function testUnionCurried()
    {
        $a = Set(1);
        $concat = $a->union;
        $concat_ = $a->union();
        $concat__ = $concat();

        $this->assertEquals($concat(Set(2)), Set(1, 2));
        $this->assertEquals($concat_(Set(2)), Set(1, 2));
        $this->assertEquals($concat__(Set(2)), Set(1, 2));
    }

    public function testIntersect()
    {
        $this->assertEquals(
            Set()->intersect(Set()),
            Set()
        );

        $this->assertEquals(
            Set(1)->intersect(Set()),
            Set()
        );

        $this->assertEquals(
            Set(1, 2, 3, 4)->intersect(Set(2, 4, 5)),
            Set(2, 4)
        );

        $this->assertEquals(
            Set(1, 2, 3)->intersect(Set(1, 2, 3)),
            Set(1, 2, 3)
        );

        $this->assertEquals(
            Set(1, 2, 3)->intersect(Set(5, 6, 7)),
            Set()
        );
    }

    public function testIntersectCurried()
    {
        $a = Set(1, 2, 3, 4);
        $b = Set(2, 4, 5);
        $e = Set(2, 4);

        $int = $a->intersect;
        $int_ = $a->intersect();
        $int__ = $int();

        $this->assertEquals($int($b), $e);
        $this->assertEquals($int_($b), $e);
        $this->assertEquals($int__($b), $e);
    }

    public function testDifference()
    {
        $this->assertEquals(
            Set(1, 2, 3, 4)->difference(Set(2, 3, 4, 5)),
            Set(1)
        );
    }

    public function testDifferenceCurried()
    {
        $a = Set(1, 2, 3, 4);
        $b = Set(2, 3, 4, 5);
        $e = Set(1);

        $diff = $a->difference;
        $diff_ = $a->difference();
        $diff__ = $diff();

        $this->assertEquals($diff($b), $e);
        $this->assertEquals($diff_($b), $e);
        $this->assertEquals($diff__($b), $e);
    }

    public function testIsSubsetOf()
    {
        $this->assertTrue(
            Set(1, 2, 3)->isSubsetOf(Set(1, 2, 3))
        );

        $this->assertTrue(
            Set(1, 2)->isSubsetOf(Set(1, 2, 3))
        );

        $this->assertTrue(Set()->isSubsetOf(Set('foo')));
        $this->assertFalse(Set(1)->isSubsetOf(Set()));
        $this->assertFalse(Set(1, 2)->isSubsetOf(Set(1)));
    }

    public function testIsSubsetOfCurried()
    {
        $a = Set(1, 2, 3);
        $b = Set(1, 2, 3, 4);

        $iso = $a->isSubsetOf;
        $iso_ = $a->isSubsetOf();
        $iso__ = $iso();

        $this->assertTrue($iso($b));
        $this->assertTrue($iso_($b));
        $this->assertTrue($iso__($b));
    }

    public function testIsProperSubsetOf()
    {
        $this->assertTrue(
            Set(1, 2)->isProperSubsetOf(Set(1, 2, 3))
        );

        $this->assertFalse(
            Set(1, 2)->isProperSubsetOf(Set(1, 2))
        );

        $this->assertTrue(Set()->isProperSubsetOf(Set(1)));
    }

    public function testIsProperSubsetOfCurried()
    {
        $a = Set(1, 2);
        $b = Set(1, 2, 3);
        $iso = $a->isProperSubsetOf;
        $iso_ = $a->isProperSubsetOf();
        $iso__ = $iso();

        $this->assertTrue($iso($b));
        $this->assertTrue($iso_($b));
        $this->assertTrue($iso__($b));
    }

    public function testContains()
    {
        $this->assertTrue(Set(1)->contains(1));
        $this->assertTrue(Set(2, 3, 4)->contains(4));
        $this->assertFalse(Set(2)->contains(3));
        $this->assertFalse(Set()->contains(1));
    }

    public function testContainsCurried()
    {
        $a = Set(1);
        $cont = $a->contains;
        $cont_ = $a->contains();
        $cont__ = $cont();

        $this->assertTrue($cont(1));
        $this->assertTrue($cont_(1));
        $this->assertTrue($cont__(1));
        $this->assertFalse($cont(2));
        $this->assertFalse($cont_(2));
        $this->assertFalse($cont__(2));
    }

    public function testSize()
    {
        $this->assertEquals(Set(2)->size(), 1);
        $this->assertEquals(Set(2, 1)->size(), 2);
        $this->assertEquals(Set()->size(), 0);
    }

    public function testCardinality()
    {
        $this->assertEquals(Set(2)->cardinality(), 1);
        $this->assertEquals(Set(2, 1)->cardinality(), 2);
        $this->assertEquals(Set()->cardinality(), 0);
    }

    public function testCount()
    {
        $this->assertEquals(Set(2)->count(), 1);
        $this->assertEquals(Set(2, 1)->count(), 2);
        $this->assertEquals(Set()->count(), 0);
    }

    public function testToArray()
    {
        $this->assertEquals(Set()->toArray(), []);
        $this->assertEquals(Set(1, 2, 'foo')->toArray(), [1, 2, 'foo']);
        $this->assertEquals(Set(1, 2, 3, 1)->toArray(), [1, 2, 3]);
    }

    public function testToCollection()
    {
        $this->assertEquals(Set()->toCollection(), Collection());
        $this->assertEquals(Set(1, 2, 'foo')->toCollection(), Collection(1, 2, 'foo'));
        $this->assertEquals(Set(1, 2, 3, 1)->toCollection(), Collection(1, 2, 3));
    }

    public function testToLinkedList()
    {
        $this->assertEquals(Set()->toLinkedList(), Nil());
        $this->assertEquals(Set(1, 2, 'foo')->toLinkedList(), Cons(1, Cons(2, Cons('foo', Nil()))));
        $this->assertEquals(Set(1, 2, 3, 1)->toLinkedList(), Cons(1, Cons(2, Cons(3, Nil()))));
    }

    public function testToString()
    {
        $a = Set([1, 2]);
        $b = Set('foo');
        $c = Set(12);

        $expectedA = "Set(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Set('foo')";
        $expectedC = "Set(12)";
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
