<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\Core\{
  identity,
  compose,
  curry,
  curryN,
  prop,
  map,
  fmap,
  ap,
  filter,
  reduce,
  trace,
  liftA,
  liftA2,
  liftA3,
  liftA4,
  liftA5,
  semigroupConcat,
  Type,
  SumType
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
   * @expectedException Exception
   */
    public function testSemigroupConcatNotAvailable()
    {
        $a = true;
        $b = false;
        semigroupConcat($a, $b);
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

    public function testReduceWithObject()
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

    public function testLiftA()
    {
        $add1 = function ($x) {
            return $x + 1;
        };
        $this->assertEquals(liftA($add1, Maybe::of(2)), Maybe::of(3));
    }

    public function testLiftA2()
    {
        $add = function ($x, $y) {
            return $x + $y;
        };
        $liftedAdd = liftA2(curry($add));
        $this->assertEquals($liftedAdd(Maybe::of(2), Maybe::of(3)), Maybe::of(5));
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

    public function testOptionSumType()
    {
        $option = SumType('Option', [
            'Some' => ['x'],
            'None' => []
        ]);
        ob_start();
        echo $option;
        $d = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($d, 'Option');

        $a = $option->Some(1);
        $b = $option->None();

        $option->map = function ($f) {
            return $this->cata([
                'Some' => function ($x) use ($f) {
                    return $this->Some($f($x));
                },
                'None' => function () {
                    return $this->None();
                }
            ]);
        };

        $c = $a->map(function ($x) {
            return $x + 1;
        });
        $d = $b->map(function ($x) {
            return $x + 1;
        });
        $this->assertEquals($option->Some(2), $c);
        $this->assertEquals($option->None(), $d);
    }

    public function testMultipleValueSumType()
    {
        $foo = SumType('Foo', [
            'A' => ['a', 'b'],
            'B' => ['c', 'd']
        ]);

        $a = $foo->A('foo', 'bar');
        $b = $foo->B('foo', 'baz');

        ob_start();
        echo $a;
        $d = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($d, 'Foo.A(foo, bar)');

        $foo->map = function ($f) {
            return $this->cata([
                'A' => function ($a, $b) use ($f) {
                    return $this->A($f($a), $f($b));
                },
                'B' => function ($c, $d) use ($f) {
                    return $this->B($f($c), $d);
                }
            ]);
        };

        $c = $a->map(function ($x) {
            return $x . 'test';
        });
        $d = $b->map(function ($x) {
            return $x . 'tester';
        });

        $this->assertEquals($foo->A('footest', 'bartest'), $c);
        $this->assertEquals($foo->B('footester', 'baz'), $d);
    }

    public function testNewType()
    {
        ini_set('xdebug.overload_var_dump', 0);
        $Point3D = Type('Point3D', ['x', 'y', 'z']);

        $a = $Point3D(1, 2, 3);
        $this->assertEquals($a->x, 1);
        $this->assertEquals($a->y, 2);
        $this->assertEquals($a->z, 3);

        $Point3D->scale = function ($n) {
            return $this->Point3D($n * $this->x, $n * $this->y, $n * $this->z);
        };

        $b = $a->scale(2);
        $this->assertEquals($b->x, 2);
        $this->assertEquals($b->y, 4);
        $this->assertEquals($b->z, 6);

        ob_start();
        echo $a;
        $d = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($d, 'Point3D(1, 2, 3)');
    }

    public function testNewTypeWithNotDefinedFunction()
    {
        $Foo = Type('Foo', ['x', 'y']);
        $a = $Foo(1, 2);

        $this->assertNull($a->test());
    }

    /**
     * @expectedException Exception
     */
    public function testNewTypeWithWrongNumberOfArguments()
    {
        $Foo = Type('Foo', ['x', 'y']);
        $a = $Foo(12);
    }

    /**
     * @expectedException Exception
     */
    public function testSumTypeCataWithoutAllTheCases()
    {
        $Foo = SumType('Foo', [
            'A' => ['x', 'y'],
            'B' => []
        ]);

        $Foo->test = function () {
            return $this->cata([]);
        };

        $a = $Foo->A(1, 2);
        $a->test();
    }

    /**
     * @expectedException Exception
     */
    public function testSumTypeCataWithoutTheProperCases()
    {
        $Foo = SumType('Foo', [
            'A' => ['x', 'y'],
            'B' => []
        ]);

        $Foo->test = function () {
            return $this->cata([
                'C' => function () {
                    return 'foo';
                },
                'D' => function () {
                    return 'bar';
                }
            ]);
        };

        $a = $Foo->A(1, 2);
        $a->test();
    }

    public function testSumTypeCataWithUnderscoreCase()
    {
        $Foo = SumType('Foo', [
            'A' => ['x', 'y'],
            'B' => []
        ]);

        $Foo->test = function () {
            return $this->cata([
                'C' => function () {
                    return 'foo';
                },
                '_' => function () {
                    return 'bar';
                }
            ]);
        };

        $a = $Foo->A(1, 2);
        $b = $Foo->B();
        $this->assertEquals($a->test(), 'bar');
        $this->assertEquals($b->test(), 'bar');
    }

    public function testSumTypeWithUndefinedMethod()
    {
        $Foo = SumType('Foo', [
            'A' => [],
            'B' => []
        ]);

        $this->assertNull($Foo->test());

        $a = $Foo->A();
        $b = $Foo->B();

        $this->assertNull($a->test());
        $this->assertNull($b->test());
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
}
