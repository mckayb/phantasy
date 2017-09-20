<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Either\{Either, Left, Right};
use Phantasy\DataTypes\LinkedList\{LinkedList, Cons, Nil};
use Phantasy\DataTypes\Writer\Writer;
use function Phantasy\Core\{
    id,
    identity,
    equals,
    lte,
    of,
    compose,
    composeK,
    curry,
    curryN,
    prop,
    maybeProp,
    map,
    fmap,
    ap,
    alt,
    mempty,
    zero,
    filter,
    reduce,
    chain,
    bind,
    flatMap,
    chainRec,
    extend,
    extract,
    mjoin,
    join,
    sequence,
    traverse,
    isTraversable,
    trace,
    liftA,
    liftA2,
    liftA3,
    liftA4,
    liftA5,
    semigroupConcat,
    concat,
    contramap,
    cmap,
    flip,
    foldl,
    foldr,
    reduceRight,
    bimap,
    head,
    tail,
    cata,
    ana,
    fold,
    foldMap,
    unfold,
    hylo,
    refold
};

class TestVarClass
{
    public $x = 'foo';
    public static $x_ = 'foostatic';

    protected $y = 'bar';
    protected static $y_ = 'barstatic';

    private $z = 'baz';
    private static $z_ = 'baz';
}

class FunctionsTest extends TestCase
{
    public function testId()
    {
        $this->assertEquals(1, id(1));
        $this->assertEquals(false, id(false));
        $this->assertEquals(true, id(true));
        $this->assertEquals("Foo bar", id("Foo bar"));
    }

    public function testIdCurried()
    {
        $id = id();
        $this->assertEquals($id(1), 1);
    }

    public function testIdentity()
    {
        $this->assertEquals(1, identity(1));
        $this->assertEquals(false, identity(false));
        $this->assertEquals(true, identity(true));
        $this->assertEquals("Foo bar", identity("Foo bar"));
    }

    public function testIdentityCurried()
    {
        $identity = identity();
        $this->assertEquals(1, $identity(1));
        $this->assertEquals(false, $identity(false));
        $this->assertEquals(true, $identity(true));
        $this->assertEquals([1, 2], $identity([1, 2]));
    }

    public function testEquals()
    {
        $this->assertTrue(equals(1, 1));
        $this->assertFalse(equals(1, '1'));
        $this->assertFalse(equals(false, true));
        $this->assertFalse(equals('foo ', 'foo'));
        $this->assertTrue(equals(new Right(1), new Right(1)));
    }

    public function testEqualsCurried()
    {
        $equals = equals();
        $equalsOne = equals(1);
        $this->assertTrue($equals(1, 1));
        $this->assertTrue($equalsOne(1));
        $this->assertFalse($equals(1, 3));
        $this->assertFalse($equalsOne(3));
    }

    public function testLte()
    {
        $f = function ($x) {
            return new class($x) {
                public $val;
                public function __construct($x)
                {
                    $this->val = $x;
                }

                public function lte($a)
                {
                    return $this->val <= $a->val;
                }
            };
        };
        $a = $f(1);
        $b = $f(2);
        $this->assertTrue(lte($a, $b));
        $this->assertFalse(lte($b, $a));

        $this->assertTrue(lte(1, 2));
        $this->assertTrue(lte(2, 2));
        $this->assertTrue(lte(2, 3));
    }

    public function testLteCurried()
    {
        $f = function ($x) {
            return new class($x) {
                public $val;
                public function __construct($x)
                {
                    $this->val = $x;
                }

                public function lte($a)
                {
                    return $this->val <= $a->val;
                }
            };
        };
        $a = $f(1);
        $b = $f(2);
        $lte = lte();
        $lteA = $lte($a);
        $lteB = $lte($b);
        $this->assertTrue($lte($a, $b));
        $this->assertTrue($lteA($b));
        $this->assertFalse($lte($b, $a));
        $this->assertFalse($lteB($a));
    }

    public function testOf()
    {
        $this->assertEquals(of(Either::class, 2), new Right(2));
    }

    public function testOfCurried()
    {
        $of = of();
        $ofEither = $of(Either::class);
        $this->assertEquals($ofEither(2), new Right(2));
    }

    public function testOfNonStatic()
    {
        $a = new class () {
            public function of($x)
            {
                return $x . 'bar';
            }
        };

        $this->assertEquals(of($a, 'foo'), 'foobar');
    }

    public function testCompose()
    {
        $a = function ($x) {
            return $x + 1;
        };
        $b = function ($x) {
            return $x + 2;
        };

        $f = compose($a, $b);
        $this->assertEquals($f(2), 5);
    }

    public function testComposeMultiple()
    {
        $a = function ($x) {
            return $x / 3;
        };
        $b = function ($x) {
            return $x * 2;
        };
        $c = function ($x) {
            return $x + 4;
        };
        $f = compose($a, $b, $c);
        $this->assertEquals($f(2), 4);
    }

    public function testAlt()
    {
        $a = new Left(1);
        $b = new Left(2);
        $c = new Right(2);
        $this->assertEquals(alt($a, $b), $b);
        $this->assertEquals(alt($a, $c), $c);
        $this->assertEquals(alt($c, $a), $c);
        $this->assertEquals(alt($c, $b), $c);
    }

    public function testAltCurried()
    {
        $a = new Left(1);
        $b = new Left(2);
        $c = new Right(2);
        $alt = alt();
        $this->assertEquals($alt($a, $b), $b);
        $this->assertEquals($alt($a, $c), $c);
        $this->assertEquals($alt($c, $a), $c);
        $this->assertEquals($alt($c, $b), $c);
        $altA = $alt($a);
        $this->assertEquals($altA($b), $b);
        $this->assertEquals($altA($c), $c);
        $altC = $alt($c);
        $this->assertEquals($altC($a), $c);
        $this->assertEquals($altC($b), $c);
    }

    public function testAltBools()
    {
        $a = true;
        $b = false;
        $this->assertTrue(alt($a, $b));
    }

    public function testZero()
    {
        $this->assertEquals(zero(Maybe::class), new Nothing());
    }

    public function testZeroCurried()
    {
        $zero = zero();
        $this->assertEquals($zero(Maybe::class), new Nothing());
    }

    public function testZeroNonStatic()
    {
        $a = new class () {
            public function zero()
            {
                return 'bar';
            }
        };

        $this->assertEquals(zero($a), 'bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSequenceInvalidArgument()
    {
        $a = new Nil();
        sequence($a, 'Foo');
    }

    public function testSequenceObjectWithSequenceMethod()
    {
        $a = new class () {
            public function sequence($f)
            {
                return 'foo';
            }
        };

        $this->assertEquals(sequence(LinkedList::class, $a), 'foo');
    }

    public function testSequenceObjectWithTraverseMethod()
    {
        $a = new class () {
            public function traverse($clss, $f)
            {
                return 'foo';
            }
        };

        $this->assertEquals(sequence(LinkedList::class, $a), 'foo');
    }

    public function testSequenceCurried()
    {
        $a = new Cons(new Right(1), new Nil());
        $sequenceEither = sequence(Either::class);
        $this->assertEquals($sequenceEither($a), new Right(new Cons(1, new Nil())));
    }

    public function testTraverse()
    {
        $a = new Cons(new Right(1), new Nil());
        $f = function ($x) {
            return $x->toMaybe();
        };

        $this->assertEquals(
            traverse(Maybe::class, $f, $a),
            new Just(new Cons(1, new Nil()))
        );
    }

    public function testTraverseObjectWithTraverseMethod()
    {
        $a = new class () {
            public function traverse($clss, $f)
            {
                return 'foo';
            }
        };

        $this->assertEquals(traverse(LinkedList::class, function ($x) {
            return $x + 1;
        }, $a), 'foo');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTraverseInvalidArgument()
    {
        traverse('Foo', function ($x) {
            return $x + 1;
        }, null);
    }

    public function testTraverseCurried()
    {
        $a = new class () {
            public function traverse($clss, $f)
            {
                return 'foo';
            }
        };

        $traverseLinkedList = traverse(LinkedList::class);
        $traverseLLAddOne = $traverseLinkedList(function ($x) {
            return $x + 1;
        });

        $this->assertEquals($traverseLLAddOne($a), 'foo');
    }

    public function testChainRec()
    {
        $f = function ($next, $done, $v) {
            return new Writer(function () use ($next, $done, $v) {
                return [$v >= 100 ? $done($v) : $next($v + 1), [$v]];
            });
        };

        list($val, $log) = chainRec($f, 0, Writer::class)->run();
        $this->assertEquals(range(0, 100), $log);
        $this->assertEquals($val, 100);
    }

    public function testChainRecCurried()
    {
        $f = function ($next, $done, $v) {
            return new Writer(function () use ($next, $done, $v) {
                return [$v >= 100 ? $done($v) : $next($v + 1), [$v]];
            });
        };
        $chainRecF = chainRec($f);
        $chainRecFStartingAt0 = $chainRecF(0);
        list($val, $log) = $chainRecFStartingAt0(Writer::class)->run();
        $this->assertEquals(range(0, 100), $log);
        $this->assertEquals($val, 100);
    }

    public function testExtend()
    {
        $checkNum = function ($x) {
            list($comp, $log) = $x->run();
            return $comp > 10 ? $comp - 10 : $comp + 5;
        };

        $res = extend($checkNum, Writer::of(12))->run();
        $this->assertEquals([2, []], $res);
    }

    public function testExtendCurried()
    {
        $checkNum = function ($x) {
            list($comp, $log) = $x->run();
            return $comp > 10 ? $comp - 10 : $comp + 5;
        };

        $extendCheck = extend($checkNum);
        $res = $extendCheck(Writer::of(12))->run();
        $this->assertEquals([2, []], $res);
    }

    public function testExtract()
    {
        $this->assertEquals(extract(Writer::of(1)), 1);
    }

    public function testExtractCurried()
    {
        $extract = extract();
        $this->assertEquals($extract(Writer::of(1)), 1);
    }

    public function testCurry()
    {
        $add = curry(
            function ($a, $b, $c) {
                return $a + $b + $c;
            }
        );

        $add1 = $add(1);
        $add11 = $add(1, 1);

        $add12 = $add1(2);
        $add112 = $add11(2);

        $add111 = $add(1, 1, 1);
        $add122 = $add1(2, 2);
        $add123 = $add12(3);

        $this->assertEquals($add111, 3);
        $this->assertEquals($add122, 5);
        $this->assertEquals($add123, 6);
    }

    public function testCurryN()
    {
        $add = function (...$args) {
            return array_reduce($args, function ($prev, $item) {
                return $prev + $item;
            }, 0);
        };

        $add4Nums = curryN(4, $add);
        $add3NumsTo1 = $add4Nums(1);
        $add2NumsTo2 = $add3NumsTo1(1);
        $add1NumTo3 = $add2NumsTo2(1);

        $this->assertEquals($add1NumTo3(1), 4);
        $this->assertEquals($add2NumsTo2(1, 2), 5);
        $this->assertEquals($add3NumsTo1(1, 2, 3), 7);
        $this->assertEquals($add4Nums(1, 2, 3, 4), 10);
    }

    public function testConcatArrays()
    {
        $a = concat([1, 2], [3, 4]);
        $this->assertEquals([1, 2, 3, 4], $a);
    }

    public function testConcatStrings()
    {
        $a = concat('foo', 'bar');
        $this->assertEquals('foobar', $a);
    }

    public function testConcatObjects()
    {
        $f = function ($x) {
            return new class($x) {
                public $val = '';
                public function __construct($val)
                {
                    $this->val = $val;
                }

                public function concat($x)
                {
                    $this->val .= $x->val;
                    return $this;
                }
            };
        };
        $a = $f('foo');
        $b = $f('bar');

        $m = concat($a, $b);

        $this->assertEquals($f('foobar'), $m);
    }

    public function testConcatCurried()
    {
        $a = 'foo';
        $b = 'bar';
        $concat = concat();
        $prependFoo = concat($a);

        $this->assertEquals($concat($a, $b), 'foobar');
        $this->assertEquals($prependFoo('bar'), 'foobar');
    }

    public function testSemigroupConcatArrays()
    {
        $a = semigroupConcat([1, 2], [3, 4]);
        $this->assertEquals([1, 2, 3, 4], $a);
    }

    public function testSemigroupConcatStrings()
    {
        $a = semigroupConcat('foo', 'bar');
        $this->assertEquals('foobar', $a);
    }

    public function testSemigroupConcatObjects()
    {
        $f = function ($x) {
            return new class($x) {
                public $val = '';
                public function __construct($val)
                {
                    $this->val = $val;
                }

                public function concat($x)
                {
                    $this->val .= $x->val;
                    return $this;
                }
            };
        };
        $a = $f('foo');
        $b = $f('bar');

        $m = semigroupConcat($a, $b);

        $this->assertEquals($f('foobar'), $m);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSemigroupConcatNotAvailable()
    {
        $a = true;
        $b = false;
        $this->assertNull(semigroupConcat($a, $b));
    }

    public function testSemigroupconcatCurried()
    {
        $a = 'foo';
        $b = 'bar';
        $concat = semigroupConcat();
        $prependFoo = semigroupConcat($a);

        $this->assertEquals($concat($a, $b), 'foobar');
        $this->assertEquals($prependFoo('bar'), 'foobar');
    }

    public function testMEmptyArray()
    {
        $this->assertEquals([], mempty([]));
        $this->assertEquals([], mempty(['one', 'two']));
    }

    public function testMEmptyString()
    {
        $this->assertEquals('', mempty(''));
        $this->assertEquals('', mempty('foo'));
    }

    public function testMEmptyObjectEmptyMethod()
    {
        $foo = new class () {
            public function empty()
            {
                return 'test';
            }
        };

        $this->assertEquals('test', mempty($foo));
    }

    public function testMEmptyClassName()
    {
        $this->assertEquals(mempty(LinkedList::class), new Nil());
    }

    public function testMEmptyNullIfDoesntMakeSense()
    {
        $this->assertNull(mempty(true));
    }

    public function testMEmptyCurried()
    {
        $empty = mempty();
        $this->assertEquals($empty('foo'), '');
        $this->assertEquals($empty(['bar']), []);
    }

    public function testPropObjects()
    {
        $f = new class() {
            public $test = 'foo';
        };
        $g = new class() {
            public $test = 'bar';
        };

        $this->assertEquals(array_map(prop('test'), [$f, $g]), ['foo', 'bar']);
    }

    public function testPropArrays()
    {
        $f = [ "test" => "foo" ];
        $g = [ "test" => "bar" ];
        $this->assertEquals(array_map(prop('test'), [$f, $g]), ['foo', 'bar']);
    }

    public function testPropStaticProp()
    {
        $this->assertEquals(prop('x_', TestVarClass::class), 'foostatic');
    }

    public function testPropCurried()
    {
        $f = [ "test" => "foo" ];
        $g = [ "test" => "bar" ];
        $getTest = prop('test');
        $this->assertEquals($getTest($f), "foo");
        $this->assertEquals($getTest($g), "bar");
    }

    public function testPropUnknown()
    {
        $this->assertNull(prop('foo', 'string'));
    }

    public function testMapArrays()
    {
        $f = function ($x) {
            return $x + 1;
        };

        $this->assertEquals(map($f, [1, 2, 3]), [2, 3, 4]);
    }

    public function testMapCurried()
    {
        $plusOne = map(
            function ($x) {
                return $x + 1;
            }
        );
        $this->assertEquals($plusOne([1, 2, 3]), [2, 3, 4]);
    }

    public function testMapCurriedEmptyParams()
    {
        $a = map();
        $b = $a();
        $c = $b();
        $d = $c(function ($x) {
            return $x + 1;
        });
        $e = $d();
        $f = $e();
        $g = $d([2, 4, 6]);
        $this->assertEquals([3, 5, 7], $g);
    }

    public function testMapExample()
    {
        $getBox = function ($x) {
            return new class($x) {
                private $item = null;
                public function __construct($item)
                {
                    $this->item = $item;
                }
                public function map($f)
                {
                    return new static($f($this->item));
                }
            };
        };
        $add = function ($x) {
            return $x + 1;
        };

        $this->assertEquals(map($add, $getBox(1)), $getBox(2));
    }

    public function testFMapArrays()
    {
        $add1 = function ($x) {
            return $x + 1;
        };
        $this->assertEquals([2, 3, 4], fmap($add1, [1, 2, 3]));
    }

    public function testFMapCurried()
    {
        $plusOne = fmap(
            function ($x) {
                return $x + 1;
            }
        );
        $this->assertEquals($plusOne([1, 2, 3]), [2, 3, 4]);
    }

    public function testFMapCurriedEmptyParams()
    {
        $a = fmap();
        $b = $a();
        $c = $b();
        $d = $c(function ($x) {
            return $x + 1;
        });
        $e = $d();
        $f = $e();
        $g = $d([2, 4, 6]);
        $this->assertEquals([3, 5, 7], $g);
    }

    public function testFMapExample()
    {
        $getBox = function ($x) {
            return new class($x) {
                private $item = null;
                public function __construct($item)
                {
                    $this->item = $item;
                }
                public function map($f)
                {
                    return new static($f($this->item));
                }
            };
        };
        $add = function ($x) {
            return $x + 1;
        };

        $this->assertEquals(fmap($add, $getBox(1)), $getBox(2));
    }

    public function testAp()
    {
        $a = Maybe::of(function ($x) {
            return $x + 1;
        });
        $b = Maybe::of(1);
        $this->assertEquals(Maybe::of(2), ap($a, $b));
    }

    public function testApCurried()
    {
        $a = Maybe::of(function ($x) {
            return $x + 1;
        });
        $b = Maybe::of(1);
        $apPlusOne = ap($a);
        $this->assertEquals(Maybe::of(2), $apPlusOne($b));
    }

    public function testFilterArrays()
    {
        $f = function ($x) {
            return $x % 2 === 0;
        };

        $this->assertEquals(filter($f, [1, 2, 3, 4]), [2, 4]);
    }

    public function testFilterCurried()
    {
        $isOdd = filter(
            function ($x) {
                return $x % 2 === 1;
            }
        );

        $this->assertEquals($isOdd([1, 2, 3, 4]), [1, 3]);
    }

    public function testFilterWithObject()
    {
        $getBox = function ($x) {
            return new class($x) {
                private $items = [];
                public function __construct($items)
                {
                    $this->items = $items;
                }
                public function filter($f)
                {
                    return new static(array_values(array_filter($this->items, $f)));
                }
            };
        };
        $isEven = function ($x) {
            return $x % 2 === 0;
        };
        $box = $getBox([1, 2, 3]);
        $this->assertEquals(filter($isEven, $box), $getBox([2]));
    }

    public function testReduceArrays()
    {
        $sum = reduce(
            function ($x, $y) {
                return $x + $y;
            },
            0,
            [1, 2, 3]
        );
        $this->assertEquals($sum, 6);
    }

    public function testReduceCurried()
    {
        $sum = reduce(
            function ($x, $y) {
                return $x + $y;
            }
        );
        $sumWithInitial6 = $sum(6);
        $this->assertEquals($sumWithInitial6([1, 2, 3]), 12);
    }

    public function testReduceWithObjectWithReduceMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function reduce($f, $i)
                {
                    return array_reduce($this->items, $f, $i);
                }
            };
        };
        $isOdd = function ($x) {
            return $x % 2 === 1;
        };
        $oddSum = function ($sum, $x) use ($isOdd) {
            if ($isOdd($x)) {
                return $sum + $x;
            }
            return $sum;
        };
        $box = $getClass([1, 2, 3]);
        $this->assertEquals(reduce($oddSum, 0, $box), 4);
    }

    public function testReduceWithObjectWithFoldlMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function foldl($f, $i)
                {
                    return array_reduce($this->items, $f, $i);
                }
            };
        };
        $isOdd = function ($x) {
            return $x % 2 === 1;
        };
        $oddSum = function ($sum, $x) use ($isOdd) {
            if ($isOdd($x)) {
                return $sum + $x;
            }
            return $sum;
        };
        $box = $getClass([1, 2, 3]);
        $this->assertEquals(reduce($oddSum, 0, $box), 4);
    }

    public function testFoldlWithObjectWithFoldlMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function foldl($f, $i)
                {
                    return array_reduce($this->items, $f, $i);
                }
            };
        };
        $isOdd = function ($x) {
            return $x % 2 === 1;
        };
        $oddSum = function ($sum, $x) use ($isOdd) {
            if ($isOdd($x)) {
                return $sum + $x;
            }
            return $sum;
        };
        $box = $getClass([1, 2, 3]);
        $this->assertEquals(foldl($oddSum, 0, $box), 4);
    }

    public function testFoldlWithObjectWithReduceMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function reduce($f, $i)
                {
                    return array_reduce($this->items, $f, $i);
                }
            };
        };
        $isOdd = function ($x) {
            return $x % 2 === 1;
        };
        $oddSum = function ($sum, $x) use ($isOdd) {
            if ($isOdd($x)) {
                return $sum + $x;
            }
            return $sum;
        };
        $box = $getClass([1, 2, 3]);
        $this->assertEquals(foldl($oddSum, 0, $box), 4);
    }

    public function testLiftA()
    {
        $add1 = function ($x) {
            return $x + 1;
        };
        $this->assertEquals(liftA($add1, Maybe::of(2)), Maybe::of(3));
        $this->assertEquals(
            liftA($add1, Maybe::fromNullable(null)),
            Maybe::fromNullable(null)
        );
    }

    public function testLiftA2()
    {
        $add = function ($x, $y) {
            return $x + $y;
        };
        $liftedAdd = liftA2(curry($add));
        $this->assertEquals($liftedAdd(Maybe::of(2), Maybe::of(3)), Maybe::of(5));

        $this->assertEquals(
            $liftedAdd(Maybe::of(2), Maybe::fromNullable(null)),
            Maybe::fromNullable(null)
        );

        $this->assertEquals(
            $liftedAdd(Maybe::fromNullable(null), Maybe::fromNullable(2)),
            Maybe::fromNullable(null)
        );

        $this->assertEquals(
            $liftedAdd(Maybe::fromNullable(null), Maybe::fromNullable(null)),
            Maybe::fromNullable(null)
        );
    }

    public function testLiftA3()
    {
        $add = function ($x, $y, $z) {
            return $x + $y + $z;
        };
        $liftedAdd = liftA3(curry($add));
        $this->assertEquals(
            $liftedAdd(Maybe::of(1), Maybe::of(2), Maybe::of(3)),
            Maybe::of(6)
        );

        $this->assertEquals(
            $liftedAdd(Maybe::fromNullable(null), Maybe::of(1), Maybe::fromNullable(null)),
            Maybe::fromNullable(null)
        );
    }

    public function testListA4()
    {
        $add = function ($w, $x, $y, $z) {
            return $w + $x + $y + $z;
        };
        $liftedAdd = liftA4(curry($add));
        $this->assertEquals(
            $liftedAdd(Maybe::of(1), Maybe::of(2), Maybe::of(3), Maybe::of(4)),
            Maybe::of(10)
        );
    }

    public function testLiftA5()
    {
        $add = function ($v, $w, $x, $y, $z) {
            return $v + $w + $x + $y + $z;
        };
        $liftedAdd = liftA5(curry($add));
        $this->assertEquals(
            $liftedAdd(Maybe::of(1), Maybe::of(2), Maybe::of(3), Maybe::of(4), Maybe::of(5)),
            Maybe::of(15)
        );
    }

    public function testLiftACurried()
    {
        $add1 = function ($x) {
            return $x + 1;
        };
        $liftA = liftA();
        $this->assertEquals($liftA($add1, Maybe::of(2)), Maybe::of(3));
        $this->assertEquals(
            liftA($add1, Maybe::fromNullable(null)),
            Maybe::fromNullable(null)
        );
    }

    public function testTrace()
    {
        ini_set('xdebug.overload_var_dump', '0');
        ob_start();
        $a = trace('Hello!');
        $b = ob_get_contents();
        ob_end_clean();
        $this->assertEquals(trim($b), 'string(6) "Hello!"');
        $this->assertEquals($a, 'Hello!');
    }

    public function testTraceCurried()
    {
        $trace = trace();
        ini_set('xdebug.overload_var_dump', '0');
        ob_start();
        $a = $trace('Hello!');
        $b = ob_get_contents();
        ob_end_clean();
        $this->assertEquals(trim($b), 'string(6) "Hello!"');
        $this->assertEquals($a, 'Hello!');
    }

    public function testMJoinObjWithJoin()
    {
        $a = new class
        {
            public function join()
            {
                return 1;
            }
        };

        $this->assertEquals(mjoin($a), 1);
        $this->assertEquals(join($a), 1);
    }

    public function testMJoinObjWithMJoin()
    {
        $a = new class
        {
            public function mjoin()
            {
                return 1;
            }
        };

        $this->assertEquals(mjoin($a), 1);
        $this->assertEquals(join($a), 1);
    }

    public function testMJoinReturnsNullOnFailure()
    {
        $this->assertNull(mjoin(12));
        $this->assertNull(join(12));
    }

    public function testMJoinCurried()
    {
        $mjoin = mjoin();
        $join = join();
        $a = new class
        {
            public function mjoin()
            {
                return 1;
            }
        };
        $this->assertEquals($mjoin($a), 1);
        $this->assertEquals($join($a), 1);
    }

    public function testChain()
    {
        $a = new class
        {
            public function chain($f)
            {
                return $f(1);
            }
        };

        $this->assertEquals(chain(function ($x) {
            return $x + 1;
        }, $a), 2);
    }

    public function testChainObjectWithBindMethod()
    {
        $a = new class
        {
            public function bind($f)
            {
                return $f(1);
            }
        };

        $this->assertEquals(chain(function ($x) {
            return $x + 1;
        }, $a), 2);
    }

    public function testChainObjectWithFlatMapMethod()
    {
        $a = new class
        {
            public function flatMap($f)
            {
                return $f(1);
            }
        };

        $this->assertEquals(chain(function ($x) {
            return $x + 1;
        }, $a), 2);
    }

    public function testChainCurried()
    {
        $a = Either::of(1);
        $f = function ($x) {
            return Either::of($x + 1);
        };
        $chain = chain();
        $chainF = $chain($f);
        $this->assertEquals($chain($f, $a), new Right(2));
        $this->assertEquals($chainF($a), new Right(2));
    }

    public function testBind()
    {
        $a = Either::of(1);
        $f = function ($x) {
            return Either::of($x + 1);
        };
        $chain = bind();
        $chainF = $chain($f);
        $this->assertEquals($chain($f, $a), new Right(2));
        $this->assertEquals($chainF($a), new Right(2));
    }

    public function testFlatMap()
    {
        $a = Either::of(1);
        $f = function ($x) {
            return Either::of($x + 1);
        };
        $chain = flatMap();
        $chainF = $chain($f);
        $this->assertEquals($chain($f, $a), new Right(2));
        $this->assertEquals($chainF($a), new Right(2));
    }

    public function testIsTraversable()
    {
        $this->assertTrue(isTraversable([1]));
        $this->assertFalse(isTraversable(1));
    }

    public function testIsTraversableCurried()
    {
        $f = isTraversable();
        $this->assertTrue($f([1]));
        $this->assertFalse($f(1));
    }

    public function testContramapObjWithContramap()
    {
        $f = function ($s) {
            return '<html>' . $s . '</html>';
        };

        $Comp = new class ($f) {
            private $f = null;

            public function __construct(callable $f)
            {
                $this->f = $f;
            }

            public function contramap(callable $g)
            {
                $f = $this->f;
                return new static(function ($x) use ($f, $g) {
                    return $f($g($x));
                });
            }

            public function fold($s = null)
            {
                return call_user_func($this->f, $s);
            }
        };

        $a = contramap(function ($x) {
            return '<body><div>' . $x . '</div></body>';
        }, $Comp);
        $b = contramap(function ($x) {
            return $x["title"];
        }, $a);

        $this->assertEquals(
            $b->fold([ "title" => "Blue" ]),
            "<html><body><div>Blue</div></body></html>"
        );
    }

    public function testContramapObjectWithCmap()
    {
        $f = function ($s) {
            return '<html>' . $s . '</html>';
        };

        $Comp = new class ($f) {
            private $f = null;

            public function __construct(callable $f)
            {
                $this->f = $f;
            }

            public function cmap(callable $g)
            {
                $f = $this->f;
                return new static(function ($x) use ($f, $g) {
                    return $f($g($x));
                });
            }

            public function fold($s = null)
            {
                return call_user_func($this->f, $s);
            }
        };

        $a = contramap(function ($x) {
            return '<body><div>' . $x . '</div></body>';
        }, $Comp);
        $b = contramap(function ($x) {
            return $x["title"];
        }, $a);

        $this->assertEquals(
            $b->fold([ "title" => "Blue" ]),
            "<html><body><div>Blue</div></body></html>"
        );
    }

    public function testCmapObjWithContramap()
    {
        $f = function ($s) {
            return '<html>' . $s . '</html>';
        };

        $Comp = new class ($f) {
            private $f = null;

            public function __construct(callable $f)
            {
                $this->f = $f;
            }

            public function contramap(callable $g)
            {
                $f = $this->f;
                return new static(function ($x) use ($f, $g) {
                    return $f($g($x));
                });
            }

            public function fold($s = null)
            {
                return call_user_func($this->f, $s);
            }
        };

        $a = cmap(function ($x) {
            return '<body><div>' . $x . '</div></body>';
        }, $Comp);
        $b = cmap(function ($x) {
            return $x["title"];
        }, $a);

        $this->assertEquals(
            $b->fold([ "title" => "Blue" ]),
            "<html><body><div>Blue</div></body></html>"
        );
    }

    public function testCmapObjWithCmap()
    {
        $f = function ($s) {
            return '<html>' . $s . '</html>';
        };

        $Comp = new class ($f) {
            private $f = null;

            public function __construct(callable $f)
            {
                $this->f = $f;
            }

            public function cmap(callable $g)
            {
                $f = $this->f;
                return new static(function ($x) use ($f, $g) {
                    return $f($g($x));
                });
            }

            public function fold($s = null)
            {
                return call_user_func($this->f, $s);
            }
        };

        $a = cmap(function ($x) {
            return '<body><div>' . $x . '</div></body>';
        }, $Comp);
        $b = cmap(function ($x) {
            return $x["title"];
        }, $a);

        $this->assertEquals(
            $b->fold([ "title" => "Blue" ]),
            "<html><body><div>Blue</div></body></html>"
        );
    }

    public function testCmapCurried()
    {
        $f = function ($s) {
            return '<html>' . $s . '</html>';
        };

        $Comp = new class ($f) {
            private $f = null;

            public function __construct(callable $f)
            {
                $this->f = $f;
            }

            public function contramap(callable $g)
            {
                $f = $this->f;
                return new static(function ($x) use ($f, $g) {
                    return $f($g($x));
                });
            }

            public function fold($s = null)
            {
                return call_user_func($this->f, $s);
            }
        };
        $f = function ($x) {
            return '<body><div>' . $x . '</div></body>';
        };
        $g = function ($x) {
            return $x["title"];
        };

        $contramap = cmap();
        $contramapF = $contramap($f);
        $contramapG = $contramap($g);
        $a = $contramapF($Comp);
        $b = $contramapG($a);

        $this->assertEquals(
            $b->fold([ "title" => "Blue" ]),
            "<html><body><div>Blue</div></body></html>"
        );
    }

    public function testContramapCurried()
    {
        $f = function ($s) {
            return '<html>' . $s . '</html>';
        };

        $Comp = new class ($f) {
            private $f = null;

            public function __construct(callable $f)
            {
                $this->f = $f;
            }

            public function contramap(callable $g)
            {
                $f = $this->f;
                return new static(function ($x) use ($f, $g) {
                    return $f($g($x));
                });
            }

            public function fold($s = null)
            {
                return call_user_func($this->f, $s);
            }
        };

        $f = function ($x) {
            return '<body><div>' . $x . '</div></body>';
        };
        $g = function ($x) {
            return $x["title"];
        };

        $contramap = contramap();
        $contramapF = $contramap($f);
        $contramapG = $contramap($g);
        $a = $contramapF($Comp);
        $b = $contramapG($a);

        $this->assertEquals(
            $b->fold([ "title" => "Blue" ]),
            "<html><body><div>Blue</div></body></html>"
        );
    }

    public function testContramapReturnNull()
    {
        $this->assertNull(contramap(function ($x) {
            return $x + 1;
        }, true));
    }

    public function testCmapReturnNull()
    {
        $this->assertNull(cmap(function ($x) {
            return $x + 1;
        }, true));
    }

    public function testFoldl()
    {
        $this->assertEquals(
            foldl(concat(), '', ['a', 'b', 'c']),
            'abc'
        );
    }

    public function testFoldlCurried()
    {
        $foldl = foldl();
        $foldlConcat = $foldl(concat());
        $foldlConcatStr = $foldlConcat('');
        $this->assertEquals($foldl(concat(), '', ['a', 'b', 'c']), 'abc');
        $this->assertEquals($foldlConcat('', ['a', 'b', 'c']), 'abc');
        $this->assertEquals($foldlConcatStr(['a', 'b', 'c']), 'abc');
    }

    public function testFoldr()
    {
        $this->assertEquals(
            foldr(concat(), '', ['a', 'b', 'c']),
            'cba'
        );
    }

    public function testFoldrCurried()
    {
        $foldr = foldr();
        $foldrConcat = $foldr(concat());
        $foldrConcatStr = $foldrConcat('');
        $this->assertEquals($foldr(concat(), '', ['a', 'b', 'c']), 'cba');
        $this->assertEquals($foldrConcat('', ['a', 'b', 'c']), 'cba');
        $this->assertEquals($foldrConcatStr(['a', 'b', 'c']), 'cba');
    }

    public function testReduceRight()
    {
        $this->assertEquals(
            reduceRight(concat(), '', ['a', 'b', 'c']),
            'cba'
        );
    }

    public function testReduceRightCurried()
    {
        $foldr = reduceRight();
        $foldrConcat = $foldr(concat());
        $foldrConcatStr = $foldrConcat('');
        $this->assertEquals($foldr(concat(), '', ['a', 'b', 'c']), 'cba');
        $this->assertEquals($foldrConcat('', ['a', 'b', 'c']), 'cba');
        $this->assertEquals($foldrConcatStr(['a', 'b', 'c']), 'cba');
    }

    public function testFoldrWithObjectWithReduceRightMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function reduceRight($f, $i)
                {
                    return reduceRight($f, $i, $this->items);
                }
            };
        };
        $box = $getClass(['a', 'b', 'c']);
        $this->assertEquals(foldr(concat(), '', $box), 'cba');
    }

    public function testFoldrWithObjectWithFoldrMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function foldr($f, $i)
                {
                    return reduceRight($f, $i, $this->items);
                }
            };
        };
        $box = $getClass(['a', 'b', 'c']);
        $this->assertEquals(foldr(concat(), '', $box), 'cba');
    }

    public function testReduceRightWithObjectWithReduceRightMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function reduceRight($f, $i)
                {
                    return reduceRight($f, $i, $this->items);
                }
            };
        };
        $box = $getClass(['a', 'b', 'c']);
        $this->assertEquals(reduceRight(concat(), '', $box), 'cba');
    }

    public function testReduceRightWithObjectWithFoldrMethod()
    {
        $getClass = function ($x) {
            return new class($x) {
                private $items = [];

                public function __construct($items)
                {
                    $this->items = $items;
                }

                public function foldr($f, $i)
                {
                    return reduceRight($f, $i, $this->items);
                }
            };
        };
        $box = $getClass(['a', 'b', 'c']);
        $this->assertEquals(reduceRight(concat(), '', $box), 'cba');
    }

    public function testBimapRight()
    {
        $a = Either::of(1);
        $left = function ($x) {
            return $x + 1;
        };
        $right = function ($x) {
            return $x - 1;
        };
        $this->assertEquals(
            bimap($left, $right, $a),
            new Right(0)
        );
    }

    public function testBimapLeft()
    {
        $a = Either::fromNullable(1, null);
        $left = function ($x) {
            return $x + 1;
        };
        $right = function ($x) {
            return $x - 1;
        };
        $this->assertEquals(
            bimap($left, $right, $a),
            new Left(2)
        );
    }

    public function testBimapCurried()
    {
        $a = Either::of(1);
        $bimap = bimap();
        $f = function ($x) {
            return $x + 1;
        };
        $g = function ($x) {
            return $x - 1;
        };
        $bimapF = $bimap($f);
        $bimapFG = $bimap($f, $g);
        $this->assertEquals($bimap($f, $g, $a), new Right(0));
        $this->assertEquals($bimapF($g, $a), new Right(0));
        $this->assertEquals($bimapFG($a), new Right(0));
    }

    public function testMaybePropObject()
    {
        $a = new class () {
            public $foo = 'bar';
        };

        $this->assertEquals(maybeProp('foo', $a), new Just('bar'));
        $this->assertEquals(maybeProp('bar', $a), new Nothing());
    }

    public function testMaybePropArray()
    {
        $a = ['foo' => 'bar'];
        $this->assertEquals(maybeProp('foo', $a), new Just('bar'));
        $this->assertEquals(maybeProp('bar', $a), new Nothing());
    }

    public function testMaybePropStatic()
    {
        $this->assertEquals(maybeProp('x_', TestVarClass::class), new Just('foostatic'));
        $this->assertEquals(maybeProp('blue_', TestVarClass::class), new Nothing());
    }

    public function testMaybePropInvalid()
    {
        $this->assertEquals(maybeProp('foo', 'bar'), new Nothing());
    }

    public function testHead()
    {
        $this->assertEquals(head([]), null);
        $this->assertEquals(head([1]), 1);
        $this->assertEquals(head(['foo', 'bar']), 'foo');
    }

    public function testTail()
    {
        $this->assertEquals(tail([]), []);
        $this->assertEquals(tail([1]), []);
        $this->assertEquals(tail(['foo', 'bar']), ['bar']);
    }

    public function testComposeK()
    {
        $get = curry(function ($propName, $obj) {
            return Maybe::fromNullable($obj[$propName] ?? null);
        });

        $toUpper = curry(function ($x) {
            return strtoupper($x);
        });

        $getStateCode = composeK(
            compose(Maybe::of(), $toUpper),
            $get('state'),
            $get('address'),
            $get('user')
        );

        $this->assertEquals($getStateCode(['user' => ['address' => ['state' => 'ny']]]), new Just('NY'));
        $this->assertEquals($getStateCode([]), new Nothing());
        $this->assertEquals($getStateCode(['user' => null]), new Nothing());
        $this->assertEquals($getStateCode(['user' => ['address' => ['state' => null]]]), new Nothing());
    }

    public function testFoldMap()
    {
        $Sum = function ($x) {
            return new class ($x) {
                public $val = null;

                public function __construct($x)
                {
                    $this->val = $x;
                }

                public function concat($x)
                {
                    return new static($this->val + $x->val);
                }

                public function empty()
                {
                    return new static(0);
                }
            };
        };

        $this->assertEquals(foldMap($Sum, [1, 2, 3])->val, 6);
    }

    public function testFoldMapCurried()
    {
        $Sum = function ($x) {
            return new class ($x) {
                public $val = null;

                public function __construct($x)
                {
                    $this->val = $x;
                }

                public function concat($x)
                {
                    return new static($this->val + $x->val);
                }

                public function empty()
                {
                    return new static(0);
                }
            };
        };

        $foldMap = foldMap();
        $foldMapSum = foldMap($Sum);
        $foldMapSum_ = $foldMap($Sum);

        $this->assertEquals($foldMap($Sum, [1, 2, 3])->val, 6);
        $this->assertEquals($foldMapSum([1, 2, 3])->val, 6);
        $this->assertEquals($foldMapSum_([1, 2, 3])->val, 6);
    }

    public function testFold()
    {
        $Sum = function ($x) {
            return new class ($x) {
                public $val = null;

                public function __construct($x)
                {
                    $this->val = $x;
                }

                public function concat($x)
                {
                    return new static($this->val + $x->val);
                }

                public function empty()
                {
                    return new static(0);
                }
            };
        };

        $this->assertEquals(fold([$Sum(1), $Sum(3), $Sum(5)]), $Sum(9));
    }

    public function testCata()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $list = $Cons(1, $Cons(2, $Cons(5, $Nil())));
        $sum = function ($x) use ($Nil) {
            return $x == $Nil() ? 0 : $x->head + $x->tail;
        };

        $this->assertEquals(cata($sum, $list), 8);
    }

    public function testAna()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $arrToList = function ($xs) use ($Cons, $Nil) {
            return count($xs) === 0 ? $Nil() : $Cons(head($xs), tail($xs));
        };

        $this->assertEquals(ana($arrToList, [1, 2, 3, 4, 5]), $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil()))))));
    }

    public function testAnaCurried()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $arrToList = function ($xs) use ($Cons, $Nil) {
            return count($xs) === 0 ? $Nil() : $Cons(head($xs), tail($xs));
        };

        $ana = ana();
        $anaF = ana($arrToList);
        $anaF_ = $ana($arrToList);

        $this->assertEquals(
            $ana($arrToList, [1, 2, 3, 4, 5]),
            $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil())))))
        );
        $this->assertEquals($anaF([1, 2, 3, 4, 5]), $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil()))))));
        $this->assertEquals($anaF_([1, 2, 3, 4, 5]), $Cons(1, $Cons(2, $Cons(3, $Cons(4, $Cons(5, $Nil()))))));
    }

    public function testUnfold()
    {
        $f = function ($x) {
            return $x <= 0 ? null : [$x, $x - 1];
        };
        $this->assertEquals(unfold($f, 10), [10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    }

    public function testUnfoldCurried()
    {
        $f = function ($x) {
            return $x <= 0 ? null : [$x, $x - 1];
        };

        $ana = unfold();
        $anaF = unfold($f);
        $anaF_ = $ana($f);
        $this->assertEquals($ana($f, 10), [10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $this->assertEquals($anaF(10), [10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
        $this->assertEquals($anaF_(10), [10, 9, 8, 7, 6, 5, 4, 3, 2, 1]);
    }

    public function testHylo()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $sum = function ($x) use ($Nil) {
            return $x == $Nil() ? 0 : $x->head + $x->tail;
        };

        $arrToList = function ($xs) use ($Cons, $Nil) {
            return count($xs) === 0 ? $Nil() : $Cons(head($xs), tail($xs));
        };

        $this->assertEquals(hylo($sum, $arrToList, [1, 2, 3, 4, 5]), 15);
    }

    public function testHyloCurried()
    {
        $Cons = function ($head, $tail) {
            return new class ($head, $tail) {
                public $head = null;
                public $tail = [];

                public function __construct($head, $tail)
                {
                    $this->head = $head;
                    $this->tail = $tail;
                }

                public function map(callable $f)
                {
                    return new static($this->head, $f($this->tail));
                }
            };
        };

        $Nil = function () {
            return new class () {
                public function map(callable $f)
                {
                    return new static();
                }
            };
        };

        $sum = function ($x) use ($Nil) {
            return $x == $Nil() ? 0 : $x->head + $x->tail;
        };

        $arrToList = function ($xs) use ($Cons, $Nil) {
            return count($xs) === 0 ? $Nil() : $Cons(head($xs), tail($xs));
        };

        $hylo = hylo();
        $hyloSum = hylo($sum);
        $hyloSum_ = $hylo($sum);
        $hyloBoth = $hyloSum($arrToList);
        $hyloBoth_ = $hyloSum_($arrToList);
        $this->assertEquals($hyloBoth([1, 2, 3, 4, 5]), 15);
        $this->assertEquals($hyloBoth_([1, 2, 3, 4, 5]), 15);
    }

    public function testRefold()
    {
        $sum = function ($xs) {
            return reduce(function ($acc, $x) {
                return $acc + $x;
            }, 0, $xs);
        };

        $countDown = function ($limit) {
            return unfold(function ($n) {
                return $n <= 0 ? null : [$n, $n - 1];
            }, $limit);
        };

        $this->assertEquals(refold($sum, $countDown, 5), 15);
    }

    public function testRefoldCurried()
    {
        $sum = function ($xs) {
            return reduce(function ($acc, $x) {
                return $acc + $x;
            }, 0, $xs);
        };

        $countDown = function ($limit) {
            return unfold(function ($n) {
                return $n <= 0 ? null : [$n, $n - 1];
            }, $limit);
        };

        $hylo = refold();
        $hyloSum = refold($sum);
        $hyloSum_ = $hylo($sum);
        $hyloBoth = $hyloSum($countDown);
        $hyloBoth_ = $hyloSum_($countDown);
        $this->assertEquals($hyloBoth(5), 15);
        $this->assertEquals($hyloBoth_(5), 15);
    }
}
