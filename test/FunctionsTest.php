<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Maybe\Maybe;
use Phantasy\DataTypes\Either\{Either, Left, Right};
use function Phantasy\Core\{
  identity,
  compose,
  curry,
  curryN,
  prop,
  map,
  fmap,
  ap,
  mempty,
  filter,
  reduce,
  chain,
  mjoin,
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
  bimap
};

class FunctionsTest extends TestCase
{
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

    public function testSemigroupConcatNotAvailable()
    {
        $a = true;
        $b = false;
        $this->assertNull(semigroupConcat($a, $b));
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

    public function testMEmptyNullIfDoesntMakeSense()
    {
        $this->assertNull(mempty(true));
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

    public function testTrace()
    {
        ini_set('xdebug.overload_var_dump', 0);
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
        ini_set('xdebug.overload_var_dump', 0);
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
    }

    public function testMJoinReturnsNullOnFailure()
    {
        $this->assertNull(mjoin(12));
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

    public function testIsTraversable()
    {
        $this->assertTrue(isTraversable([1]));
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

    public function testFoldr()
    {
        $this->assertEquals(
            foldr(concat(), '', ['a', 'b', 'c']),
            'cba'
        );
    }

    public function testReduceRight()
    {
        $this->assertEquals(
            reduceRight(concat(), '', ['a', 'b', 'c']),
            'cba'
        );
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
}
