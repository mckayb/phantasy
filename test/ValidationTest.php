<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Either\{Either, Left, Right};
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};
use function Phantasy\DataTypes\Validation\{Success, Failure};

class ValidationTest extends TestCase
{
    public function testSuccessFunc()
    {
        $this->assertEquals(Success(12), new Success(12));
    }

    public function testSuccessCurried()
    {
        $succ = Success();
        $this->assertEquals($succ(12), Success(12));
    }

    public function testFailureFunc()
    {
        $this->assertEquals(Failure('foo'), new Failure('foo'));
    }

    public function testFailureCurried()
    {
        $failure = Failure();
        $this->assertEquals($failure('foobar'), Failure('foobar'));
    }

    public function testValidationOf()
    {
        $f = function ($a, $b) {
            return [$a, $b];
        };
        $x = Validation::of($f);

        $this->assertInstanceOf(Success::class, $x);
        $this->assertEquals(new Success($f), $x);
    }

    public function testValidationOfCurried()
    {
        $of = Validation::of();
        $this->assertEquals($of(12), Success(12));
    }

    public function testValidationFromNullableFailure()
    {
        $a = Validation::fromNullable(null, null);
        $this->assertEquals(new Failure(null), $a);
    }

    public function testValidationFromNullableSuccess()
    {
        $this->assertEquals(new Success('foo'), Validation::fromNullable(null, 'foo'));
    }

    public function testValidationFromNullableCurried()
    {
        $fromNull = Validation::fromNullable();
        $defVal = $fromNull('Failure Val');
        $this->assertEquals($defVal(null), Failure('Failure Val'));
        $this->assertEquals($defVal('foo'), Success('foo'));
    }

    public function testValidationTryCatchSuccess()
    {
        $a = Validation::tryCatch(
            function () {
                return "foo bar";
            }
        );
        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(new Success("foo bar"), $a);
        $this->assertEquals(Validation::of("foo bar"), $a);
    }

    public function testValidationTryCatchFailure()
    {
        $a = Validation::tryCatch(
            function () {
                throw new \Exception('baz');
            }
        );
        $this->assertInstanceOf(Failure::class, $a);
        $this->assertEquals(new Failure(new \Exception('baz')), $a);
    }

    public function testValidationTryCatchCurried()
    {
        $tryCatch = Validation::tryCatch();
        $f = function () {
            return 'foo';
        };
        $this->assertEquals($tryCatch($f), Success('foo'));
    }

    public function testValidationZero()
    {
        $this->assertEquals(Validation::zero(), new Failure([]));
    }

    public function testValidationPlusRightIdentity()
    {
        $x = Validation::of(3);
        $this->assertEquals($x->alt(Validation::zero()), $x);
    }

    public function testValidationPlusLeftIdentity()
    {
        $x = Validation::of(3);
        $this->assertEquals(Validation::zero()->alt($x), $x);
    }

    public function testValidationPlusAnnihilation()
    {
        $x = Validation::of(3);
        $this->assertEquals(Validation::zero()->map(function ($x) {
            return $x + 1;
        }), Validation::zero());
    }

    public function testValidationAlternative()
    {
        $x = Validation::of(3);
        $f = Validation::of(function ($x) {
            return $x + 1;
        });
        $g = Validation::of(function ($x) {
            return $x - 1;
        });

        $f1 = new Failure([]);
        $g2 = new Failure([]);

        $this->assertEquals($x->ap($f->alt($g)), $x->ap($f)->alt($x->ap($g)));
        $this->assertEquals($x->ap($f->alt($g2)), $x->ap($f)->alt($x->ap($g2)));
        $this->assertEquals($x->ap($f1->alt($g)), $x->ap($f1)->alt($x->ap($g)));
        $this->assertEquals($x->ap($f1->alt($g2)), $x->ap($f1)->alt($x->ap($g2)));
    }

    public function testSuccessMapIdentity()
    {
        $a = Validation::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of(124), $a);
        $this->assertEquals(new Success(124), $a);
    }

    public function testSuccessMapComposition()
    {
        $f = function ($x) {
            return $x . "foo";
        };

        $g = function ($x) {
            return $x . "bar";
        };

        $a = Validation::of("baz")->map(
            function ($x) use ($f, $g) {
                return $f($g($x));
            }
        );
        $b = Validation::of("baz")->map($g)->map($f);

        $this->assertEquals($a, $b);
        $this->assertEquals(Validation::of("bazbarfoo"), $a);
        $this->assertEquals(new Success("bazbarfoo"), $a);
    }

    public function testSuccessMapChaining()
    {
        $a = Validation::of(123)
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

        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of(62), $a);
        $this->assertEquals(new Success(62), $a);
    }

    public function testSuccessMapImmutability()
    {
        $a = Validation::of(123);
        $b = $a->map(
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertInstanceOf(Success::class, $a);
        $this->assertInstanceOf(Success::class, $b);
        $this->assertEquals(Validation::of(123), $a);
        $this->assertEquals(Validation::of(124), $b);
        $this->assertEquals(new Success(123), $a);
        $this->assertEquals(new Success(124), $b);
    }

    public function testSuccessMapCurried()
    {
        $a = Success(123);
        $map = $a->map;
        $map_ = $a->map();
        $map__ = $map();
        $f = function ($x) {
            return $x + 1;
        };
        $expected = Success(124);
        $this->assertEquals($map($f), $expected);
        $this->assertEquals($map_($f), $expected);
        $this->assertEquals($map__($f), $expected);
    }

    public function testSuccessFoldOnlyAppliesRightFunction()
    {
        $a = Validation::of(123)
        ->fold(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );

        $this->assertEquals($a, 'Success');
    }

    public function testSuccessFoldCurried()
    {
        $left = function ($x) {
            return $x - 1;
        };

        $right = function ($x) {
            return $x + 1;
        };

        $a = Success(123);
        $fold = $a->fold;
        $fold_ = $a->fold();
        $fold__ = $fold();

        $fold1 = $fold($left);
        $fold1_ = $fold_($left);
        $fold1__ = $fold__($left);

        $this->assertEquals($fold($left, $right), 124);
        $this->assertEquals($fold_($left, $right), 124);
        $this->assertEquals($fold__($left, $right), 124);
        $this->assertEquals($fold1($right), 124);
        $this->assertEquals($fold1_($right), 124);
        $this->assertEquals($fold1__($right), 124);
    }

    public function testSuccessCataOnlyAppliesRightFunction()
    {
        $a = Validation::of(123)
        ->cata(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );

        $this->assertEquals($a, 'Success');
    }

    public function testSuccessCataCurried()
    {
        $left = function ($x) {
            return $x - 1;
        };

        $right = function ($x) {
            return $x + 1;
        };

        $a = Success(123);
        $fold = $a->cata;
        $fold_ = $a->cata();
        $fold__ = $fold();

        $fold1 = $fold($left);
        $fold1_ = $fold_($left);
        $fold1__ = $fold__($left);

        $this->assertEquals($fold($left, $right), 124);
        $this->assertEquals($fold_($left, $right), 124);
        $this->assertEquals($fold__($left, $right), 124);
        $this->assertEquals($fold1($right), 124);
        $this->assertEquals($fold1_($right), 124);
        $this->assertEquals($fold1__($right), 124);
    }

    public function testSuccessAp()
    {
        $a = Validation::of("foo")
        ->ap(
            Validation::of(
                function ($x) {
                    return $x . "bar";
                }
            )
        );
        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of("foobar"), $a);
        $this->assertEquals(new Success("foobar"), $a);
    }

    public function testSuccessApFailure()
    {
        $a = new Failure("foo");
        $b = Validation::of("bar");

        $this->assertEquals($b->ap($a), $a);
    }

    public function testSuccessApCurried()
    {
        $a = Failure('foo');
        $b = Validation::of(function ($x) {
            return $x . 'bar';
        });
        $ap = $b->ap;
        $ap_ = $b->ap();
        $ap__ = $ap();

        $this->assertEquals($ap($a), $a);
        $this->assertEquals($ap_($a), $a);
        $this->assertEquals($ap__($a), $a);
    }

    public function testSuccessBimap()
    {
        $a = Validation::of("foo")
        ->bimap(
            function ($lv) {
                return $lv . "baz";
            },
            function ($rv) {
                return $rv . "bar";
            }
        );

        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of("foobar"), $a);
        $this->assertEquals(new Success("foobar"), $a);
    }

    public function testSuccessBimapCurried()
    {
        $a = Validation::of('foo');
        $left = function ($x) {
            return $x . 'bar';
        };
        $right = function ($x) {
            return $x . 'baz';
        };

        $bimap = $a->bimap;
        $bimap_ = $a->bimap();
        $bimap__ = $bimap();

        $bimapL = $bimap($left);
        $bimapL_ = $bimap_($left);
        $bimapL__ = $bimap__($left);

        $expected = Success('foobaz');
        $this->assertEquals($bimap($left, $right), $expected);
        $this->assertEquals($bimap_($left, $right), $expected);
        $this->assertEquals($bimap__($left, $right), $expected);
        $this->assertEquals($bimapL($right), $expected);
        $this->assertEquals($bimapL_($right), $expected);
        $this->assertEquals($bimapL__($right), $expected);
    }

    public function testSuccessAlt()
    {
        $a = Validation::of(2);
        $b = Validation::of(5);
        $c = new Failure(['test']);

        $this->assertEquals($a->alt($b), $a);
        $this->assertEquals($a->alt($c), $a);
    }

    public function testSuccessAltCurried()
    {
        $a = Validation::of(2);
        $b = Validation::of(5);
        $alt = $a->alt;
        $alt_ = $a->alt();
        $alt__ = $alt();

        $this->assertEquals($alt($b), $a);
        $this->assertEquals($alt_($b), $a);
        $this->assertEquals($alt__($b), $a);
    }

    public function testSuccessReduce()
    {
        $this->assertEquals(Validation::of(2)->reduce(function ($prev, $curr) {
            return $prev + $curr;
        }, 2), 4);
    }

    public function testSuccessReduceCurried()
    {
        $a = Validation::of(2);

        $f = function ($prev, $curr) {
            return $prev + $curr;
        };

        $reduce = $a->reduce;
        $reduce_ = $a->reduce();
        $reduce__ = $reduce();

        $reduceF = $reduce($f);
        $reduceF_ = $reduce_($f);
        $reduceF__ = $reduce__($f);

        $expected = 5;
        $this->assertEquals($reduce($f, 3), $expected);
        $this->assertEquals($reduce_($f, 3), $expected);
        $this->assertEquals($reduce__($f, 3), $expected);
        $this->assertEquals($reduceF(3), $expected);
        $this->assertEquals($reduceF_(3), $expected);
        $this->assertEquals($reduceF__(3), $expected);
    }

    public function testSuccessToEither()
    {
        $a = Validation::of("foo")->toEither();

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of("foo"), $a);
        $this->assertEquals(new Right("foo"), $a);
    }

    public function testSuccessToMaybe()
    {
        $a = Validation::of("foo")->toMaybe();
        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of("foo"), $a);
        $this->assertEquals(new Just("foo"), $a);
    }

    public function testSuccessToString()
    {
        $a = Validation::of([1, 2]);
        $b = Validation::of('foo');
        $c = Validation::of(12);

        $expectedA = "Success(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Success('foo')";
        $expectedC = "Success(12)";
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

    public function testFailureToString()
    {
        $a = new Failure([1, 2]);
        $b = new Failure('foo');

        $expectedA = "Failure(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Failure('foo')";
        ob_start();
        echo $a;
        $actualA = ob_get_contents();
        ob_end_clean();

        ob_start();
        echo $b;
        $actualB = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expectedA, $actualA);
        $this->assertEquals($expectedB, $actualB);
    }

    public function testFailureMap()
    {
        $a = new Failure("foo");
        $b = $a->map(
            function ($x) {
                return $x . "bar";
            }
        );

        $this->assertEquals($a, $b);
        $this->assertInstanceOf(Failure::class, $b);
    }

    public function testFailureMapCurried()
    {
        $a = Failure('foo');
        $map = $a->map;
        $map_ = $a->map();
        $map__ = $map();

        $f = function ($x) {
            return $x . 'bar';
        };
        $this->assertEquals($map($f), $a);
        $this->assertEquals($map_($f), $a);
        $this->assertEquals($map__($f), $a);
    }

    public function testFailureAp()
    {
        $a = new Failure("foo");
        $b = $a->ap(Validation::of(function ($x) {
            return $x . "bar";
        }));

        $this->assertInstanceOf(Failure::class, $b);
        $this->assertEquals($a, $b);
    }

    public function testFailureApCurried()
    {
        $a = Failure('foo');
        $b = Validation::of(function ($x) {
            return $x . 'bar';
        });

        $ap = $a->ap;
        $ap_ = $a->ap();
        $ap__ = $ap();
        $this->assertEquals($ap($b), $a);
        $this->assertEquals($ap_($b), $a);
        $this->assertEquals($ap__($b), $a);
    }

    public function testFailureConcatSuccess()
    {
        $a = new Failure("foo");
        $b = Validation::of("test");
        $c = $a->concat($b);

        $this->assertInstanceOf(Failure::class, $c);
        $this->assertEquals(new Failure("foo"), $c);
    }

    public function testFailureConcatFailure()
    {
        $a = new Failure("foo");
        $b = new Failure("bar");

        $this->assertEquals(new Failure("foobar"), $a->concat($b));
    }

    public function testFailureConcatCurried()
    {
        $a = Failure('foo');
        $b = Failure('bar');

        $concat = $a->concat;
        $concat_ = $a->concat();
        $concat__ = $concat();

        $expected = Failure('foobar');
        $this->assertEquals($concat($b), $expected);
        $this->assertEquals($concat_($b), $expected);
        $this->assertEquals($concat__($b), $expected);
    }

    public function testFailureFold()
    {
        $a = (new Failure("foo"))->fold(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );
        $this->assertEquals($a, 'Error');
    }

    public function testFailureFoldCurried()
    {
        $f = function ($x) {
            return 'Error';
        };
        $g = function ($x) {
            return 'Success';
        };
        $a = Failure('foo');
        $fold = $a->fold;
        $fold_ = $a->fold();
        $fold__ = $fold();

        $foldL = $fold($f);
        $foldL_ = $fold_($f);
        $foldL__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 'Error');
        $this->assertEquals($fold_($f, $g), 'Error');
        $this->assertEquals($fold__($f, $g), 'Error');
        $this->assertEquals($foldL($g), 'Error');
        $this->assertEquals($foldL_($g), 'Error');
        $this->assertEquals($foldL__($g), 'Error');
    }

    public function testFailureCata()
    {
        $a = (new Failure("foo"))
        ->cata(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );
        $this->assertEquals($a, 'Error');
    }

    public function testFailureCataCurried()
    {
        $f = function ($x) {
            return 'Error';
        };
        $g = function ($x) {
            return 'Success';
        };
        $a = Failure('foo');
        $fold = $a->cata;
        $fold_ = $a->cata();
        $fold__ = $fold();

        $foldL = $fold($f);
        $foldL_ = $fold_($f);
        $foldL__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 'Error');
        $this->assertEquals($fold_($f, $g), 'Error');
        $this->assertEquals($fold__($f, $g), 'Error');
        $this->assertEquals($foldL($g), 'Error');
        $this->assertEquals($foldL_($g), 'Error');
        $this->assertEquals($foldL__($g), 'Error');
    }

    public function testFailureBimap()
    {
        $a = (new Failure("foo"))
        ->bimap(
            function ($x) {
                return $x . "baz";
            },
            function ($x) {
                return $x . "bar";
            }
        );

        $this->assertInstanceOf(Failure::class, $a);
        $this->assertEquals(new Failure("foobaz"), $a);
    }

    public function testFailureBimapCurried()
    {
        $f = function ($x) {
            return 'Error';
        };
        $g = function ($x) {
            return 'Success';
        };
        $a = Failure('foo');
        $bimap = $a->bimap;
        $bimap_ = $a->bimap();
        $bimap__ = $bimap();

        $bimapL = $bimap($f);
        $bimapL_ = $bimap_($f);
        $bimapL__ = $bimap__($f);

        $this->assertEquals($bimap($f, $g), Failure('Error'));
        $this->assertEquals($bimap_($f, $g), Failure('Error'));
        $this->assertEquals($bimap__($f, $g), Failure('Error'));
        $this->assertEquals($bimapL($g), Failure('Error'));
        $this->assertEquals($bimapL_($g), Failure('Error'));
        $this->assertEquals($bimapL__($g), Failure('Error'));
    }

    public function testFailureAlt()
    {
        $a = new Failure([]);
        $b = Validation::of(12);
        $c = new Failure(['foo']);

        $this->assertEquals($a->alt($b), $b);
        $this->assertEquals($a->alt($c), $c);
    }

    public function testFailureAltCurried()
    {
        $a = Failure('foo');
        $b = Success('bar');
        $c = Failure('baz');

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

    public function testFailureReduce()
    {
        $this->assertEquals((new Failure('foo'))->reduce(function ($prev, $curr) {
            return $prev . $curr;
        }, 'bar'), 'bar');
    }

    public function testFailureReduceCurried()
    {
        $a = Failure('foo');

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

    public function testFailureToEither()
    {
        $a = (new Failure("foo"))->toEither();
        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left("foo"), $a);
    }

    public function testFailureToMaybe()
    {
        $a = (new Failure("foo"))->toMaybe();
        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }

    public function testSuccessEquals()
    {
        $this->assertTrue(Success(12)->equals(Success(12)));
        $this->assertFalse(Success(12)->equals(Success(13)));
        $this->assertFalse(Success(12)->equals(Failure(12)));
    }

    public function testSuccessEqualsCurried()
    {
        $a = Success(12);
        $equals = $a->equals;
        $equals_ = $a->equals();
        $equals__ = $equals();

        $this->assertTrue($equals(Success(12)));
        $this->assertTrue($equals_(Success(12)));
        $this->assertTrue($equals__(Success(12)));
        $this->assertFalse($equals(Failure(12)));
        $this->assertFalse($equals_(Failure(12)));
        $this->assertFalse($equals__(Failure(12)));
    }

    public function testFailureEquals()
    {
        $this->assertTrue(Failure(12)->equals(Failure(12)));
        $this->assertFalse(Failure(12)->equals(Success(12)));
        $this->assertFalse(Failure(12)->equals(Failure(13)));
    }

    public function testFailureEqualsCurried()
    {
        $a = Failure(12);
        $equals = $a->equals;
        $equals_ = $a->equals();
        $equals__ = $equals();

        $this->assertFalse($equals(Success(12)));
        $this->assertFalse($equals_(Success(12)));
        $this->assertFalse($equals__(Success(12)));
        $this->assertTrue($equals(Failure(12)));
        $this->assertTrue($equals_(Failure(12)));
        $this->assertTrue($equals__(Failure(12)));
    }
}
