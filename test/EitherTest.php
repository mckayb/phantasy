<?php

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Either\{Either, Left, Right};
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};
use Phantasy\DataTypes\LinkedList\{Cons, Nil};
use function Phantasy\DataTypes\Either\{Left, Right};

class EitherTest extends TestCase
{
    public function testLeftFunc()
    {
        $this->assertEquals(Left(12), new Left(12));
    }

    public function testLeftFuncCurried()
    {
        $l = Left();
        $this->assertEquals($l(12), new Left(12));
    }

    public function testRightFunc()
    {
        $this->assertEquals(Right(12), new Right(12));
    }

    public function testRightFuncCurried()
    {
        $r = Right();
        $this->assertEquals($r(12), new Right(12));
    }

    public function testEitherOf()
    {
        $just123 = Either::of(123);
        $this->assertInstanceOf(Right::class, $just123);
    }

    public function testEitherOfCurried()
    {
        $of = Either::of();
        $this->assertEquals(Right(12), $of(12));
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

    public function testEitherFromNullableCurried()
    {
        $withDef = Either::fromNullable("left");
        $this->assertEquals(new Right("foo"), $withDef("foo"));
        $this->assertEquals(new Left("left"), $withDef(null));
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

    public function testEitherTryCatchCurried()
    {
        $tryCatchEither = Either::tryCatch();
        $a = $tryCatchEither(
            function () {
                return "foo bar";
            }
        );

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(new Right("foo bar"), $a);
        $this->assertEquals(Either::of("foo bar"), $a);
    }

    public function testEitherZero()
    {
        $this->assertEquals(Either::zero(), new Left(null));
    }

    public function testEitherPlusRightIdentity()
    {
        $x = Either::of(3);
        $this->assertEquals($x->alt(Either::zero()), $x);
    }

    public function testEitherPlusLeftIdentity()
    {
        $x = Either::of(3);
        $this->assertEquals(Either::zero()->alt($x), $x);
    }

    public function testEitherPlusAnnihilation()
    {
        $x = Either::of(3);
        $this->assertEquals(Either::zero()->map(function ($x) {
            return $x + 1;
        }), Either::zero());
    }

    public function testEitherAltCurried()
    {
        $alt = Either::of(3)->alt();
        $this->assertEquals($alt(Right(4)), Right(3));
        $this->assertEquals($alt(Left(2)), Right(3));

        $alt2 = Left(3)->alt();
        $this->assertEquals($alt2(Right(2)), Right(2));
        $this->assertEquals($alt2(Left(5)), Left(5));
    }

    public function testEitherAlternative()
    {
        $x = Either::of(3);
        $f = Either::of(function ($x) {
            return $x + 1;
        });
        $g = Either::of(function ($x) {
            return $x - 1;
        });

        $f1 = new Left(null);
        $g2 = new Left(null);

        $this->assertEquals($x->ap($f->alt($g)), $x->ap($f)->alt($x->ap($g)));
        $this->assertEquals($x->ap($f->alt($g2)), $x->ap($f)->alt($x->ap($g2)));
        $this->assertEquals($x->ap($f1->alt($g)), $x->ap($f1)->alt($x->ap($g)));
        $this->assertEquals($x->ap($f1->alt($g2)), $x->ap($f1)->alt($x->ap($g2)));
    }

    public function testRightEquals()
    {
        $this->assertTrue(Right(12)->equals(Right(12)));
        $this->assertFalse(Right(12)->equals(Right(13)));
        $this->assertFalse(Right(12)->equals(Left(12)));
    }

    public function testRightEqualsCurried()
    {
        $equals = Right(12)->equals;
        $equals_ = Right(12)->equals();
        $equals__ = $equals();
        $this->assertTrue($equals(Right(12)));
        $this->assertTrue($equals_(Right(12)));
        $this->assertTrue($equals__(Right(12)));
        $this->assertFalse($equals(Right(11)));
        $this->assertFalse($equals_(Right(11)));
        $this->assertFalse($equals__(Right(11)));
        $this->assertFalse($equals(Left(11)));
        $this->assertFalse($equals_(Left(11)));
        $this->assertFalse($equals__(Left(11)));
    }

    public function testRightConcat()
    {
        $this->assertEquals(Right(1)->concat(Right(2)), Right(1));
        $this->assertEquals(Right(1)->concat(Left(2)), Right(1));
    }

    public function testRightConcatCurried()
    {
        $a = Right(1);
        $concat = $a->concat;
        $concat_ = $a->concat();
        $concat__ = $concat();

        $this->assertEquals($concat(Right(2)), $a);
        $this->assertEquals($concat_(Right(2)), $a);
        $this->assertEquals($concat__(Right(2)), $a);
        $this->assertEquals($concat(Left(2)), $a);
        $this->assertEquals($concat_(Left(2)), $a);
        $this->assertEquals($concat__(Left(2)), $a);
    }

    public function testLeftConcat()
    {
        $this->assertEquals(Left(1)->concat(Right(2)), Right(2));
        $this->assertEquals(Left(1)->concat(Left(2)), Left(2));
    }

    public function testLeftConcatCurred()
    {
        $a = Left(1);
        $concat = $a->concat;
        $concat_ = $a->concat();
        $concat__ = $concat();

        $this->assertEquals($concat(Right(2)), Right(2));
        $this->assertEquals($concat_(Right(2)), Right(2));
        $this->assertEquals($concat__(Right(2)), Right(2));
        $this->assertEquals($concat(Left(2)), Left(2));
        $this->assertEquals($concat_(Left(2)), Left(2));
        $this->assertEquals($concat__(Left(2)), Left(2));
    }

    public function testRightMapCurried()
    {
        $a = Right(123);
        $map = $a->map();
        $this->assertEquals($map(function ($x) {
            return $x + 1;
        }), Right(124));
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

    public function testRightBimapCurried()
    {
        $left = function ($lv) {
            return $lv - 1;
        };

        $right = function ($rv) {
            return $rv + 1;
        };
        $a = Either::of(123)->bimap($left, $right);
        $b = Either::of(123)->bimap();
        $bWithLeft = $b($left);
        $bWithBoth = $bWithLeft($right);

        $c = Either::of(123)->bimap($left);
        $cWithBoth = $c($right);
        $this->assertEquals(Right(124), $a);
        $this->assertEquals(Right(124), $bWithBoth);
        $this->assertEquals(Right(124), $cWithBoth);
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

    public function testRightFoldCurried()
    {
        $left = function ($x) {
            return $x - 1;
        };
        $right = function ($x) {
            return $x + 1;
        };
        $a = Either::of(12)->fold($left, $right);
        $b = Either::of(12)->fold();
        $bWithLeft = $b($left);
        $bWithBoth = $bWithLeft($right);

        $this->assertEquals(13, $a);
        $this->assertEquals(13, $bWithBoth);
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

    public function testRightCataIsCurried()
    {
        $left = function ($x) {
            return $x - 1;
        };
        $right = function ($x) {
            return $x + 1;
        };
        $a = Either::of(12)->cata($left, $right);
        $b = Either::of(12)->cata();
        $bWithLeft = $b($left);
        $bWithBoth = $bWithLeft($right);

        $this->assertEquals(13, $a);
        $this->assertEquals(13, $bWithBoth);
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

    public function testRightAlt()
    {
        $a = Either::of(2);
        $b = Either::of(5);
        $c = new Left('test');

        $this->assertEquals($a->alt($b), $a);
        $this->assertEquals($a->alt($c), $a);
    }

    public function testRightReduce()
    {
        $this->assertEquals(Either::of(2)->reduce(function ($prev, $curr) {
            return $prev + $curr;
        }, 2), 4);
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

    public function testLeftEquals()
    {
        $this->assertTrue(Left(12)->equals(Left(12)));
        $this->assertFalse(Left(12)->equals(Left(13)));
        $this->assertFalse(Left(12)->equals(Right(12)));
    }

    public function testLeftEqualsCurried()
    {
        $equals = Left(12)->equals;
        $equals_ = Left(12)->equals();
        $equals__ = $equals();
        $this->assertTrue($equals(Left(12)));
        $this->assertTrue($equals_(Left(12)));
        $this->assertTrue($equals__(Left(12)));
        $this->assertFalse($equals(Right(12)));
        $this->assertFalse($equals_(Right(12)));
        $this->assertFalse($equals__(Right(12)));
        $this->assertFalse($equals(Left(13)));
        $this->assertFalse($equals_(Left(13)));
        $this->assertFalse($equals__(Left(13)));
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

    public function testLeftMapCurried()
    {
        $a = Left(12);
        $map = $a->map;
        $map_ = $a->map();
        $map__ = $map();

        $f = function ($x) {
            return $x - 1;
        };
        $this->assertEquals($map($f), $a);
        $this->assertEquals($map_($f), $a);
        $this->assertEquals($map__($f), $a);
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

    public function testLeftApCurried()
    {
        $a = Left(12);
        $f = Left(function ($x) {
            return $x + 1;
        });
        $ap = $a->ap;
        $ap_ = $a->ap();
        $ap__ = $ap();

        $this->assertEquals($ap($f), $a);
        $this->assertEquals($ap_($f), $a);
        $this->assertEquals($ap__($f), $a);
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

    public function testLeftChainCurried()
    {
        $a = Left(12);
        $f = function ($x) {
            return Left($x + 1);
        };

        $chain = $a->chain;
        $chain_ = $a->chain();
        $chain__ = $chain();
        $this->assertEquals($chain($f), $a);
        $this->assertEquals($chain_($f), $a);
        $this->assertEquals($chain__($f), $a);
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

    public function testLeftBindCurried()
    {
        $a = Left(12);
        $f = function ($x) {
            return Left($x + 1);
        };

        $chain = $a->bind;
        $chain_ = $a->bind();
        $chain__ = $chain();
        $this->assertEquals($chain($f), $a);
        $this->assertEquals($chain_($f), $a);
        $this->assertEquals($chain__($f), $a);
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

    public function testLeftFlatMapCurried()
    {
        $a = Left(12);
        $f = function ($x) {
            return Left($x + 1);
        };

        $chain = $a->flatMap;
        $chain_ = $a->flatMap();
        $chain__ = $chain();
        $this->assertEquals($chain($f), $a);
        $this->assertEquals($chain_($f), $a);
        $this->assertEquals($chain__($f), $a);
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

    public function testLeftFoldCurried()
    {
        $a = Left(12);
        $f = function ($x) {
            return $x + 1;
        };
        $g = function ($x) {
            return $x - 1;
        };
        $fold = $a->fold;
        $fold_ = $a->fold();
        $fold__ = $fold();

        $foldL = $fold($f);
        $foldL_ = $fold_($f);
        $foldL__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 13);
        $this->assertEquals($fold_($f, $g), 13);
        $this->assertEquals($fold__($f, $g), 13);
        $this->assertEquals($foldL($g), 13);
        $this->assertEquals($foldL_($g), 13);
        $this->assertEquals($foldL__($g), 13);
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

    public function testLeftCataCurried()
    {
        $a = Left(12);
        $f = function ($x) {
            return $x + 1;
        };
        $g = function ($x) {
            return $x - 1;
        };
        $fold = $a->cata;
        $fold_ = $a->cata();
        $fold__ = $fold();

        $foldL = $fold($f);
        $foldL_ = $fold_($f);
        $foldL__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 13);
        $this->assertEquals($fold_($f, $g), 13);
        $this->assertEquals($fold__($f, $g), 13);
        $this->assertEquals($foldL($g), 13);
        $this->assertEquals($foldL_($g), 13);
        $this->assertEquals($foldL__($g), 13);
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

    public function testLeftBimapCurried()
    {
        $a = Left(12);
        $f = function ($x) {
            return $x + 1;
        };
        $g = function ($x) {
            return $x - 1;
        };
        $bimap = $a->bimap;
        $bimap_ = $a->bimap();
        $bimap__ = $bimap();

        $bimapL = $bimap($f);
        $bimapL_ = $bimap_($f);
        $bimapL__ = $bimap__($f);

        $this->assertEquals($bimap($f, $g), Left(13));
        $this->assertEquals($bimap_($f, $g), Left(13));
        $this->assertEquals($bimap__($f, $g), Left(13));
        $this->assertEquals($bimapL($g), Left(13));
        $this->assertEquals($bimapL_($g), Left(13));
        $this->assertEquals($bimapL__($g), Left(13));
    }

    public function testLeftAlt()
    {
        $a = new Left('foo');
        $b = Either::of('bar');
        $c = new Left('baz');

        $this->assertEquals($a->alt($b), $b);
        $this->assertEquals($a->alt($c), $c);
    }

    public function testLeftAltCurried()
    {
        $a = Left('foo');
        $b = Right('bar');
        $c = Left('baz');

        $alt = $a->alt;
        $alt_ = $a->alt();
        $alt__ = $alt();

        $this->assertEquals($alt($b), $b);
        $this->assertEquals($alt_($b), $b);
        $this->assertEquals($alt__($b), $b);
        $this->assertEquals($alt($c), $c);
        $this->assertEquals($alt_($c), $c);
        $this->assertEquals($alt__($c), $c);
    }

    public function testLeftReduce()
    {
        $this->assertEquals((new Left('foo'))->reduce(function ($prev, $curr) {
            return $curr . $prev;
        }, 'bar'), 'bar');
    }

    public function testLeftReduceCurried()
    {
        $a = Left('foo');
        $f = function ($prev, $curr) {
            return $prev . $curr;
        };
        $reduce = $a->reduce;
        $reduce_ = $a->reduce();
        $reduce__ = $reduce();

        $reduceF = $reduce($f);
        $reduceF_ = $reduce_($f);
        $reduceF__ = $reduce__($f);

        $this->assertEquals($reduce($f, 'bar'), 'bar');
        $this->assertEquals($reduce_($f, 'bar'), 'bar');
        $this->assertEquals($reduce__($f, 'bar'), 'bar');
        $this->assertEquals($reduceF('bar'), 'bar');
        $this->assertEquals($reduceF_('bar'), 'bar');
        $this->assertEquals($reduceF__('bar'), 'bar');
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

    public function testSequence()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $this->assertEquals(
            $a->sequence(Either::class),
            new Right(new Cons(1, new Cons(2, new Nil())))
        );
    }

    public function testSequenceCurried()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $sequence = $a->sequence;
        $sequence_ = $a->sequence();
        $sequence__ = $sequence();

        $expected = new Right(new Cons(1, new Cons(2, new Nil())));
        $this->assertEquals($sequence(Either::class), $expected);
        $this->assertEquals($sequence_(Either::class), $expected);
        $this->assertEquals($sequence__(Either::class), $expected);
    }

    public function testTraverse()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $traverse = $a->traverse;
        $traverse_ = $a->traverse();
        $traverse__ = $traverse();

        $f = function ($x) {
            return $x->toMaybe()->map(function ($x) {
                return $x + 1;
            });
        };

        $expected = new Just(new Cons(2, new Cons(3, new Nil())));
        $this->assertEquals($traverse(Maybe::class, $f), $expected);
        $this->assertEquals($traverse_(Maybe::class, $f), $expected);
        $this->assertEquals($traverse__(Maybe::class, $f), $expected);
    }

    public function testTraverseCurried()
    {
        $a = new Cons(new Right(1), new Cons(new Right(2), new Nil()));
        $traverse = $a->traverse;
        $traverse_ = $a->traverse();
        $traverse__ = $traverse();

        $traverseMaybe = $traverse(Maybe::class);
        $traverseMaybe_ = $traverse_(Maybe::class);
        $traverseMaybe__ = $traverse__(Maybe::class);

        $f = function ($x) {
            return $x->toMaybe()->map(function ($x) {
                return $x + 1;
            });
        };

        $expected = new Just(new Cons(2, new Cons(3, new Nil())));
        $this->assertEquals($traverse(Maybe::class, $f), $expected);
        $this->assertEquals($traverse_(Maybe::class, $f), $expected);
        $this->assertEquals($traverse__(Maybe::class, $f), $expected);
        $this->assertEquals($traverseMaybe($f), $expected);
        $this->assertEquals($traverseMaybe_($f), $expected);
        $this->assertEquals($traverseMaybe__($f), $expected);
    }
}
