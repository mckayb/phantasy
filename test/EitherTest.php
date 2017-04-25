<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Either\{Either, Left, Right};
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};

class EitherTest extends TestCase
{
    public function testEitherOf()
    {
        $just123 = Either::of(123);
        $this->assertInstanceOf(Right::class, $just123);
    }

    public function testEitherFromNullable()
    {
        $left = Either::fromNullable(null, null);
        $right = Either::fromNullable(null, "foo");

        $this->assertInstanceOf(Right::class, $right);
        $this->assertInstanceOf(Left::class, $left);
        $this->assertEquals(new Right("foo"), $right);
        $this->assertEquals(Either::of("foo"), $right);
    }

    public function testEitherTryCatchRight()
    {
        $a = Either::tryCatch(
            function () {
                return "foo bar";
            }
        );

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(new Right("foo bar"), $a);
        $this->assertEquals(Either::of("foo bar"), $a);
    }

    public function testEitherTryCatchLeft()
    {
        $a = Either::tryCatch(
            function () {
                throw new \Exception('baz');
            }
        );

        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left(new \Exception('baz')), $a);
    }

    public function testRightMapIdentity()
    {
        $a = Either::of(123)
        ->map(
            function ($x) {
                return $x;
            }
        );
        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of(123), $a);
        $this->assertEquals(new Right(123), $a);
    }

    public function testRightMapComposition()
    {
        $f = function ($x) {
            return $x . "foo";
        };

        $g = function ($x) {
            return $x . "bar";
        };

        $a = Either::of("baz")->map(
            function ($x) use ($f, $g) {
                return $f($g($x));
            }
        );
        $b = Either::of("baz")->map($g)->map($f);

        $this->assertEquals($a, $b);
        $this->assertEquals(Either::of("bazbarfoo"), $a);
        $this->assertEquals(new Right("bazbarfoo"), $a);
    }

    public function testRightMapChaining()
    {
        $a = Either::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->map(
            function ($x) {
                return $x / 2;
            }
        );

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of(62), $a);
        $this->assertEquals(new Right(62), $a);
    }

    public function testRightBimap()
    {
        $a = Either::of(123)
        ->bimap(
            function ($lv) {
                return $rv - 1;
            },
            function ($rv) {
                return $rv + 1;
            }
        );
        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of(124), $a);
        $this->assertEquals(new Right(124), $a);
    }

    public function testRightFoldOnlyAppliesRightFunction()
    {
        $a = Either::of(12)->fold(
            function ($x) {
                return $x - 1;
            },
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertEquals(13, $a);
    }

    public function testRightCataOnlyAppliesRightFunction()
    {
        $a = Either::of(12)->cata(
            function ($x) {
                return $x - 1;
            },
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertEquals(13, $a);
    }

    public function testRightChain()
    {
        $a = Either::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->chain(
            function ($x) {
                return Either::of($x / 2);
            }
        );

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of(62), $a);
        $this->assertEquals(new Right(62), $a);
    }

    public function testRightBind()
    {
        $a = Either::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->bind(
            function ($x) {
                return Either::of($x / 2);
            }
        );

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of(62), $a);
        $this->assertEquals(new Right(62), $a);
    }

    public function testRightFlatMap()
    {
        $a = Either::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->flatMap(
            function ($x) {
                return Either::of($x / 2);
            }
        );

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of(62), $a);
        $this->assertEquals(new Right(62), $a);
    }

    public function testRightAp()
    {
        $a = Either::of(123)
        ->ap(
            Either::of(
                function ($x) {
                    return $x + 1;
                }
            )
        );
        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of(124), $a);
        $this->assertEquals(new Right(124), $a);
    }

    public function testRightApComposition()
    {
        $f = function ($x) {
            return $x . "bar";
        };
        $g = function ($x) {
            return $x . "baz";
        };
        $a = Either::of("foo");
        $v = Either::of($g);
        $u = Either::of($f);

        $x = $a->ap($v)->ap($u);
        $y = $a->map(
            function ($x) use ($f, $g) {
                return $f($g($x));
            }
        );

        $this->assertEquals($x, $y);
    }

    public function testRightToMaybe()
    {
        $f = function ($x) {
            return $x + 1;
        };

        $a = Either::of($f);
        $m = $a->toMaybe();

        $this->assertInstanceOf(Just::class, $m);
        $this->assertEquals(new Just($f), $m);
    }

    public function testRightToString()
    {
        $a = Either::of([1, 2]);
        $b = Either::of('foo');
        $c = Either::of(12);

        $expectedA = "Right(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Right('foo')";
        $expectedC = "Right(12)";
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

    public function testLeftToString()
    {
        $a = new Left([1, 2]);
        $b = new Left('foo');
        $c = new Left(12);

        $expectedA = "Left(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Left('foo')";
        $expectedC = "Left(12)";
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

    public function testLeftMap()
    {
        $a = Either::fromNullable(null, null);

        $b = $a->map(
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertInstanceOf(Left::class, $b);
        $this->assertEquals(new Left(null), $b);
    }

    public function testLeftAp()
    {
        $a = Either::fromNullable(null, null);
        $e = Either::of(
            function ($x) {
                return $x + 1;
            }
        );

        $b = $a->ap($e);

        $this->assertInstanceOf(Left::class, $b);
        $this->assertEquals(new Left(null), $b);
    }

    public function testLeftChain()
    {
        $a = Either::fromNullable(null, null)
        ->chain(
            function ($x) {
                return Either::of($x + 1);
            }
        );

        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left(null), $a);
    }

    public function testLeftBind()
    {
        $a = Either::fromNullable(null, null)
        ->bind(
            function ($x) {
                return Either::of($x + 1);
            }
        );

        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left(null), $a);
    }

    public function testLeftFlatMap()
    {
        $a = Either::fromNullable(null, null)
        ->flatMap(
            function ($x) {
                return Either::of($x + 1);
            }
        );

        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left(null), $a);
    }

    public function testLeftFoldOnlyUsesFirstFunction()
    {
        $a = Either::fromNullable(null, null)
        ->fold(
            function ($x) {
                return "Error";
            },
            function ($x) {
                return $x . "foo";
            }
        );

        $this->assertEquals("Error", $a);
    }

    public function testLeftCataOnlyUsesFirstFunction()
    {
        $a = Either::fromNullable(null, null)
        ->cata(
            function ($x) {
                return "Error";
            },
            function ($x) {
                return $x . "foo";
            }
        );

        $this->assertEquals("Error", $a);
    }

    public function testLeftBimap()
    {
        $a = (new Left(123))->bimap(
            function ($lv) {
                return $lv - 1;
            },
            function ($rv) {
                return $rv + 1;
            }
        );
        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left(122), $a);
    }

    public function testLeftToMaybe()
    {
        $a = Either::fromNullable(null, null)
        ->toMaybe();

        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }

    public function testMonadLeftIdentity()
    {
        $f = function ($x) {
            return Either::of($x + 2);
        };
        $a = Either::of(12)->chain($f);
        $b = $f(12);

        $this->assertEquals($a, $b);
    }

    public function testMonadRightIdentity()
    {
        $m = Either::of(12);

        $this->assertEquals(
            $m,
            $m->chain(
                function ($x) {
                    return Either::of($x);
                }
            )
        );
    }

    public function testRightToValidation()
    {
        $m = Either::of(12);
        $vali = $m->toValidation();
        $this->assertInstanceOf(Success::class, $vali);
        $this->assertEquals(new Success(12), $vali);
        $this->assertEquals(Validation::of(12), $vali);
    }

    public function testLeftToValidation()
    {
        $e = Either::fromNullable('foobar', null);
        $vali = $e->toValidation();
        $this->assertInstanceOf(Failure::class, $vali);
        $this->assertEquals(new Failure('foobar'), $vali);
    }
}
