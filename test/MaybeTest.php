<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Either\{Either, Left, Right};

class MaybeTest extends TestCase
{
    public function testMaybeOf()
    {
        $just123 = Maybe::of(123);
        $this->assertInstanceOf(Just::class, $just123);
    }

    public function testMaybeFromNullable()
    {
        $just = Maybe::fromNullable("foo");
        $nothing = Maybe::fromNullable(null);

        $this->assertInstanceOf(Just::class, $just);
        $this->assertInstanceOf(Nothing::class, $nothing);
        $this->assertEquals(new Just("foo"), $just);
        $this->assertEquals(Maybe::of("foo"), $just);
    }

    public function testMaybeTryCatchRight()
    {
        $a = Maybe::tryCatch(
            function () {
                return "foo bar";
            }
        );

        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(new Just("foo bar"), $a);
        $this->assertEquals(Maybe::of("foo bar"), $a);
    }

    public function testMaybeTryCatchLeft()
    {
        $a = Maybe::tryCatch(
            function () {
                throw new \Exception('baz');
            }
        );

        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }

    public function testJustMapIdentity()
    {
        $a = Maybe::of(123)
        ->map(
            function ($x) {
                return $x;
            }
        );
        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of(123), $a);
        $this->assertEquals(new Just(123), $a);
    }

    public function testJustMapComposition()
    {
        $f = function ($x) {
            return $x . "foo";
        };

        $g = function ($x) {
            return $x . "bar";
        };

        $a = Maybe::of("baz")->map(
            function ($x) use ($f, $g) {
                return $f($g($x));
            }
        );
        $b = Maybe::of("baz")->map($g)->map($f);

        $this->assertEquals($a, $b);
        $this->assertEquals(Maybe::of("bazbarfoo"), $a);
        $this->assertEquals(new Just("bazbarfoo"), $a);
    }

    public function testJustMapChaining()
    {
        $a = Maybe::of(123)
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

        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of(62), $a);
        $this->assertEquals(new Just(62), $a);
    }

    public function testJustMapImmutability()
    {
        $a = Maybe::of(123);
        $b = $a->map(
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertInstanceOf(Just::class, $a);
        $this->assertInstanceOf(Just::class, $b);
        $this->assertEquals(Maybe::of(123), $a);
        $this->assertEquals(Maybe::of(124), $b);
        $this->assertEquals(new Just(123), $a);
        $this->assertEquals(new Just(124), $b);
    }

    public function testJustChain()
    {
        $a = Maybe::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->chain(
            function ($x) {
                return Maybe::of($x / 2);
            }
        );

        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of(62), $a);
        $this->assertEquals(new Just(62), $a);
    }

    public function testJustChainImmutability()
    {
        $a = Maybe::of(2);
        $b = $a->chain(
            function ($x) {
                return Maybe::of($x / 2);
            }
        );

        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of(2), $a);
        $this->assertEquals(new Just(2), $a);
        $this->assertInstanceOf(Just::class, $b);
        $this->assertEquals(Maybe::of(1), $b);
        $this->assertEquals(new Just(1), $b);
    }

    public function testJustChainAssociativity()
    {
        $f = function ($x) {
            return Maybe::of($x + 1);
        };

        $g = function ($x) {
            return Maybe::of($x + 2);
        };

        $a = Maybe::of(2);
        $this->assertEquals(
            $a->chain($f)->chain($g),
            $a->chain(
                function ($x) use ($f, $g) {
                    return $f($x)->chain($g);
                }
            )
        );
    }

    public function testJustBind()
    {
        $a = Maybe::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->bind(
            function ($x) {
                return Maybe::of($x / 2);
            }
        );

        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of(62), $a);
        $this->assertEquals(new Just(62), $a);
    }

    public function testJustFlatMap()
    {
        $a = Maybe::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->flatMap(
            function ($x) {
                return Maybe::of($x / 2);
            }
        );

        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of(62), $a);
        $this->assertEquals(new Just(62), $a);
    }

    public function testJustAp()
    {
        $a = Maybe::of(123)
        ->ap(
            Maybe::of(
                function ($x) {
                    return $x + 1;
                }
            )
        );
        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of(124), $a);
        $this->assertEquals(new Just(124), $a);
    }

    public function testJustApComposition()
    {
        $f = function ($x) {
            return $x . "bar";
        };
        $g = function ($x) {
            return $x . "baz";
        };
        $a = Maybe::of("foo");
        $v = Maybe::of($g);
        $u = Maybe::of($f);

        $x = $a->ap($v)->ap($u);
        $y = $a->map(
            function ($x) use ($f, $g) {
                return $f($g($x));
            }
        );

        $this->assertEquals($x, $y);
    }

    public function testJustApIdentity()
    {
        $f = function ($x) {
            return $x + 1;
        };

        $a = Maybe::of($f);
        $b = Maybe::of(2);

        $this->assertEquals(
            $b->ap(
                Maybe::of(
                    function ($x) {
                        return $x;
                    }
                )
            ),
            $b
        );
    }

    public function testJustApHomomorphism()
    {
        $f = function ($x) {
            return $x + 1;
        };

        $a = Maybe::of($f);
        $b = Maybe::of(2);

        $this->assertEquals(Maybe::of(2)->ap(Maybe::of($f)), Maybe::of($f(2)));
    }

    public function testJustApInterchange()
    {
        $f = function ($x) {
            return $x + 1;
        };

        $a = Maybe::of($f);
        $this->assertEquals(
            Maybe::of(2)->ap($a),
            $a->ap(
                Maybe::of(
                    function ($g) {
                        return $g(2);
                    }
                )
            )
        );
    }

    public function testJustGetOrElse()
    {
        $a = Maybe::of(3)->map(
            function ($x) {
                return $x * 4;
            }
        )->getOrElse(1);
        $this->assertEquals(12, $a);
    }

    public function testJustFold()
    {
        $a = Maybe::of(3)->fold(15);
        $this->assertEquals(3, $a);
    }

    public function testNothingMap()
    {
        $a = Maybe::fromNullable(null)
        ->map(
            function ($x) {
                return $x . 'foo';
            }
        );
        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }

    public function testNothingAp()
    {
        $a = Maybe::of(
            function ($x) {
                return $x + 1;
            }
        );
        $b = Maybe::fromNullable(null)
        ->ap($a);

        $this->assertInstanceOf(Nothing::class, $b);
        $this->assertEquals(new Nothing(), $b);
    }

    public function testNothingChain()
    {
        $a = Maybe::fromNullable(null)
        ->chain(
            function ($x) {
                return Maybe::of($x + 1);
            }
        );

        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }

    public function testNothingBind()
    {
        $a = Maybe::fromNullable(null)
        ->bind(
            function ($x) {
                return Maybe::of($x + 1);
            }
        );

        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }

    public function testNothingFlatMap()
    {
        $a = Maybe::fromNullable(null)
        ->flatMap(
            function ($x) {
                return Maybe::of($x + 1);
            }
        );

        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }

    public function testNothingToEither()
    {
        $a = Maybe::fromNullable(null)
        ->toEither('Failure');

        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left('Failure'), $a);
    }

    public function testNothingGetOrElse()
    {
        $a = Maybe::fromNullable(null)->map(
            function ($x) {
                return 'bar' . $x;
            }
        )->getOrElse('foo');
        $this->assertEquals('foo', $a);
    }

    public function testNothingFold()
    {
        $a = Maybe::fromNullable(null)->fold(10);
        $this->assertEquals($a, 10);
    }

    public function testMonadLeftIdentity()
    {
        $f = function ($x) {
            return Maybe::of($x + 2);
        };
        $a = Maybe::of(12)->chain($f);
        $b = $f(12);

        $this->assertEquals($a, $b);
    }

    public function testMonadRightIdentity()
    {
        $m = Maybe::of(12);

        $this->assertEquals(
            $m,
            $m->chain(
                function ($x) {
                    return Maybe::of($x);
                }
            )
        );
    }

    public function testJustToEither()
    {
        $a = Maybe::of(85);
        $either = $a->toEither();

        $this->assertInstanceOf(Right::class, $either);
        $this->assertEquals(Either::of(85), $either);
        $this->assertEquals(new Right(85), $either);
    }
}
