<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Either\{Either, Left, Right};
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};
use function Phantasy\DataTypes\Maybe\{Just, Nothing};
use function Phantasy\DataTypes\Either\Right;
use function Phantasy\Core\identity;
use Phantasy\Test\Traits\FunctorTests;

class MaybeTest extends TestCase
{
    protected $testClasses = [Just::class, Nothing::class];
    use FunctorTests;

    public function testJustFunc()
    {
        $this->assertEquals(Just(12), new Just(12));
    }

    public function testJustCurried()
    {
        $just = Just();
        $this->assertEquals($just(12), new Just(12));
    }

    public function testNothingFunc()
    {
        $this->assertEquals(Nothing(), new Nothing());
    }

    public function testMaybeOf()
    {
        $just123 = Maybe::of(123);
        $this->assertInstanceOf(Just::class, $just123);
    }

    public function testMaybeOfCurried()
    {
        $of = Maybe::of();
        $this->assertEquals($of(12), new Just(12));
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

    public function testMaybeFromNullableCurried()
    {
        $fn = Maybe::fromNullable();
        $this->assertEquals($fn(null), Nothing());
        $this->assertEquals($fn(12), Just(12));
    }

    public function testMaybeFromFalse()
    {
        $a = Maybe::fromFalse('foo');
        $b = Maybe::fromFalse(false);

        $this->assertInstanceOf(Just::class, $a);
        $this->assertInstanceOf(Nothing::class, $b);
        $this->assertEquals(new Just('foo'), $a);
        $this->assertEquals(new Nothing(), $b);
    }

    public function testMaybeFromFalseCurried()
    {
        $fn = Maybe::fromFalse();
        $this->assertEquals($fn(false), Nothing());
        $this->assertEquals($fn(12), Just(12));
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

    public function testMaybeTryCatchCurried()
    {
        $tryCatch = Maybe::tryCatch();
        $this->assertEquals($tryCatch(function () {
            return 1;
        }), Just(1));
    }

    public function testMaybeEmpty()
    {
        $this->assertEquals(Maybe::empty(), new Nothing());
    }

    public function testMaybeMonoidRightIdentity()
    {
        $this->assertEquals(Just(1)->concat(Maybe::empty()), Just(1));
        $this->assertEquals(Nothing()->concat(Maybe::empty()), Nothing());
    }

    public function testMaybeMonoidLeftIdentity()
    {
        $this->assertEquals(Maybe::empty()->concat(Just(1)), Just(1));
        $this->assertEquals(Maybe::empty()->concat(Nothing()), Nothing());
    }

    public function testMaybeZero()
    {
        $this->assertEquals(Maybe::zero(), new Nothing());
    }

    public function testMaybePlusRightIdentity()
    {
        $x = Maybe::of(3);
        $this->assertEquals($x->alt(Maybe::zero()), $x);
    }

    public function testMaybePlusLeftIdentity()
    {
        $x = Maybe::of(3);
        $this->assertEquals(Maybe::zero()->alt($x), $x);
    }

    public function testMaybePlusAnnihilation()
    {
        $x = Maybe::of(3);
        $this->assertEquals(Maybe::zero()->map(function ($x) {
            return $x + 1;
        }), Maybe::zero());
    }

    public function testMaybeAlternative()
    {
        $x = Maybe::of(3);
        $f = Maybe::of(function ($x) {
            return $x + 1;
        });
        $g = Maybe::of(function ($x) {
            return $x - 1;
        });

        $f1 = new Nothing();
        $g2 = new Nothing();

        $this->assertEquals($x->ap($f->alt($g)), $x->ap($f)->alt($x->ap($g)));
        $this->assertEquals($x->ap($f->alt($g2)), $x->ap($f)->alt($x->ap($g2)));
        $this->assertEquals($x->ap($f1->alt($g)), $x->ap($f1)->alt($x->ap($g)));
        $this->assertEquals($x->ap($f1->alt($g2)), $x->ap($f1)->alt($x->ap($g2)));
    }

    public function testJustEquals()
    {
        $this->assertTrue(Just(2)->equals(Just(2)));
        $this->assertFalse(Just(2)->equals(Nothing()));
        $this->assertFalse(Just(2)->equals(Just(3)));
    }

    public function testJustEqualsCurried()
    {
        $a = Just(2);
        $equals = $a->equals;
        $equals_ = $a->equals();
        $equals__ = $equals();
        $this->assertTrue($equals(Just(2)));
        $this->assertTrue($equals_(Just(2)));
        $this->assertTrue($equals__(Just(2)));

        $this->assertFalse($equals(Just(3)));
        $this->assertFalse($equals_(Just(3)));
        $this->assertFalse($equals__(Just(3)));
    }

    public function testNothingEquals()
    {
        $this->assertTrue(Nothing()->equals(Nothing()));
        $this->assertFalse(Nothing()->equals(Just('foo')));
    }

    public function testNothingEqualsCurried()
    {
        $a = Nothing();
        $equals = $a->equals;
        $equals_ = $a->equals();
        $equals__ = $equals();
        $this->assertTrue($equals(Nothing()));
        $this->assertTrue($equals_(Nothing()));
        $this->assertTrue($equals__(Nothing()));

        $this->assertFalse($equals(Just(3)));
        $this->assertFalse($equals_(Just(3)));
        $this->assertFalse($equals__(Just(3)));
    }
    
    public function testJustConcat()
    {
        $this->assertEquals(
            Just([1])->concat(Just([2])),
            Just([1, 2])
        );

        $this->assertEquals(
            Just(1)->concat(Nothing()),
            Just(1)
        );
    }

    public function testJustConcatCurried()
    {
        $concat = Just([1])->concat();
        $this->assertEquals(
            $concat(Just([2])),
            Just([1, 2])
        );

        $this->assertEquals(
            $concat(Nothing()),
            Just([1])
        );
    }

    public function testJustConcatAssociative()
    {
        $a = Just([1]);
        $b = Just([2]);
        $c = Just([3]);

        $this->assertEquals(
            $a->concat($b)->concat($c),
            $a->concat($b->concat($c))
        );
    }

    public function testNothingConcat()
    {
        $this->assertEquals(
            Nothing()->concat(Just(1)),
            Just(1)
        );

        $this->assertEquals(
            Nothing()->concat(Nothing()),
            Nothing()
        );
    }

    public function testNothingConcatCurried()
    {
        $concat = Nothing()->concat();
        $this->assertEquals(
            $concat(Just(1)),
            Just(1)
        );

        $this->assertEquals(
            $concat(Nothing()),
            Nothing()
        );
    }

    public function testNothingConcatAssociative()
    {
        $a = Nothing(1);
        $b = Nothing(2);
        $c = Nothing(3);

        $this->assertEquals(
            $a->concat($b)->concat($c),
            $a->concat($b->concat($c))
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testJustTraverseInvalidClass()
    {
        Just(1)->traverse('FOO', function ($x) {
            return $x . 'bar';
        });
    }

    public function testJustTraverse()
    {
        $this->assertEquals(
            Just(1)->traverse(Either::class, function ($x) {
                return Right($x + 1);
            }),
            Right(Just(2))
        );
    }

    public function testJustTraverseCurried()
    {
        $a = Just(1);
        $trav = $a->traverse;
        $trav_ = $a->traverse();
        $trav__ = $trav();

        $f = function ($x) {
            return Right($x + 1);
        };

        $expected = Right(Just(2));

        $this->assertEquals($trav(Either::class, $f), $expected);
        $this->assertEquals($trav_(Either::class, $f), $expected);
        $this->assertEquals($trav__(Either::class, $f), $expected);
    }

    public function testJustTraverseIdentity()
    {
        $a = Just(Right(1));

        $this->assertEquals(
            $a->traverse(Either::class, Either::of()),
            Either::of($a)
        );
    }

    public function testJustTraverseNaturality()
    {
        $u = Just(Right(1));
        $t = function (Either $m) : Validation {
            return $m->toValidation();
        };
        $F = Either::class;
        $G = Validation::class;
        $this->assertEquals(
            $t($u->traverse($F, identity())),
            $u->traverse($G, $t)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testJustSequenceInvalidClass()
    {
        Just(1)->sequence('FOO');
    }

    public function testJustSequence()
    {
        $this->assertEquals(
            Just(Right(1))->sequence(Either::class),
            Right(Just(1))
        );
    }

    public function testJustSequenceCurried()
    {
        $a = Just(Right(1));
        $seq = $a->sequence;
        $seq_ = $a->sequence();
        $seq__ = $seq();

        $expected = Right(Just(1));
        $this->assertEquals($seq(Either::class), $expected);
        $this->assertEquals($seq_(Either::class), $expected);
        $this->assertEquals($seq__(Either::class), $expected);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNothingTraverseInvalidClass()
    {
        Nothing()->traverse('FOO', function ($x) {
            return $x . 'bar';
        });
    }

    public function testNothingTraverse()
    {
        $this->assertEquals(
            Nothing()->traverse(Either::class, function ($x) {
                return Right($x + 1);
            }),
            Right(Nothing())
        );
    }

    public function testNothingTraverseCurried()
    {
        $a = Nothing();
        $trav = $a->traverse;
        $trav_ = $a->traverse();
        $trav__ = $trav();

        $f = function ($x) {
            return Right($x + 1);
        };

        $expected = Right(Nothing());

        $this->assertEquals($trav(Either::class, $f), $expected);
        $this->assertEquals($trav_(Either::class, $f), $expected);
        $this->assertEquals($trav__(Either::class, $f), $expected);
    }

    public function testNothingTraverseIdentity()
    {
        $a = Nothing();

        $this->assertEquals(
            $a->traverse(Either::class, Either::of()),
            Either::of($a)
        );
    }

    public function testNothingTraverseNaturality()
    {
        $u = Nothing();
        $t = function ($m) {
            return $m->toValidation();
        };
        $F = Either::class;
        $G = Validation::class;

        $this->assertEquals(
            $t($u->traverse($F, identity())),
            $u->traverse($G, $t)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNothingSequenceInvalidClass()
    {
        Nothing()->sequence('FOO');
    }

    public function testNothingSequence()
    {
        $this->assertEquals(
            Nothing()->sequence(Either::class),
            Right(Nothing())
        );
    }

    public function testNothingSequenceCurried()
    {
        $a = Nothing();
        $seq = $a->sequence;
        $seq_ = $a->sequence();
        $seq__ = $seq();

        $expected = Right(Nothing());
        $this->assertEquals($seq(Either::class), $expected);
        $this->assertEquals($seq_(Either::class), $expected);
        $this->assertEquals($seq__(Either::class), $expected);
    }

    public function testJustAltCurried()
    {
        $x = Maybe::of(3);
        $alt = $x->alt;
        $alt_ = $x->alt();
        $alt__ = $alt();

        $this->assertEquals($alt(Nothing()), $x);
        $this->assertEquals($alt_(Just(2)), $x);
        $this->assertEquals($alt__(Just(2)), $x);
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

    public function testJustMapCurried()
    {
        $a = Maybe::of(123);
        $map = $a->map;
        $map_ = $a->map();
        $map__ = $map();

        $f = function ($x) {
            return $x + 1;
        };
        $expected = Just(124);

        $this->assertEquals($map($f), $expected);
        $this->assertEquals($map_($f), $expected);
        $this->assertEquals($map__($f), $expected);
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

    public function testJustChainCurried()
    {
        $a = Maybe::of(1);
        $f = function ($x) {
            return Maybe::of($x + 1);
        };

        $chain = $a->chain;
        $chain_ = $a->chain();
        $chain__ = $chain();
        $expected = Maybe::of(2);
        $this->assertEquals($chain($f), $expected);
        $this->assertEquals($chain_($f), $expected);
        $this->assertEquals($chain__($f), $expected);
    }

    public function testJustExtend()
    {
        $this->assertEquals(
            Just(1)->extend(function ($x) {
                return $x->map(function ($y) {
                    return $y + 1;
                })->getOrElse(null);
            }),
            Just(2)
        );
    }

    public function testJustExtendCurried()
    {
        $a = Just(1);
        $extend = $a->extend;
        $extend_ = $a->extend();
        $extend__ = $extend();

        $f = function ($x) {
            return $x->map(function ($y) {
                return $y + 1;
            })->getOrElse(null);
        };
        $expected = Just(2);

        $this->assertEquals($extend($f), $expected);
        $this->assertEquals($extend_($f), $expected);
        $this->assertEquals($extend__($f), $expected);
    }

    public function testJustExtendLaws()
    {
        $f = function ($x) {
            return $x->map(function ($y) {
                return $y % 2;
            })->getOrElse(null);
        };

        $g = function ($x) {
            return $x->map(function ($y) {
                return $y / 5;
            })->getOrElse(null);
        };

        $a = Just(1);

        $this->assertEquals(
            $a->extend($g)->extend($f),
            $a->extend(function ($w) use ($f, $g) {
                return $f($w->extend($g));
            })
        );
    }

    public function testNothingExtend()
    {
        $this->assertEquals(
            Nothing()->extend(function ($x) {
                return $x->map(function ($y) {
                    return $y + 1;
                })->getOrElse(null);
            }),
            Nothing()
        );
    }

    public function testNothingExtendCurried()
    {
        $a = Nothing();
        $extend = $a->extend;
        $extend_ = $a->extend();
        $extend__ = $extend();

        $f = function ($x) {
            return $x->map(function ($y) {
                return $y + 1;
            })->getOrElse(null);
        };
        $expected = Nothing();

        $this->assertEquals($extend($f), $expected);
        $this->assertEquals($extend_($f), $expected);
        $this->assertEquals($extend__($f), $expected);
    }

    public function testNothingExtendLaws()
    {
        $f = function ($x) {
            return $x->map(function ($y) {
                return $y % 2;
            })->getOrElse(null);
        };

        $g = function ($x) {
            return $x->map(function ($y) {
                return $y / 5;
            })->getOrElse(null);
        };

        $this->assertEquals(
            Nothing()->extend($g)->extend($f),
            Nothing()->extend(function ($w) use ($f, $g) {
                return $f($w->extend($g));
            })
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

    public function testJustBindCurried()
    {
        $a = Maybe::of(1);
        $f = function ($x) {
            return Maybe::of($x + 1);
        };

        $chain = $a->bind;
        $chain_ = $a->bind();
        $chain__ = $chain();
        $expected = Maybe::of(2);
        $this->assertEquals($chain($f), $expected);
        $this->assertEquals($chain_($f), $expected);
        $this->assertEquals($chain__($f), $expected);
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

    public function testJustFlatMapCurried()
    {
        $a = Maybe::of(1);
        $f = function ($x) {
            return Maybe::of($x + 1);
        };

        $chain = $a->flatMap;
        $chain_ = $a->flatMap();
        $chain__ = $chain();
        $expected = Maybe::of(2);
        $this->assertEquals($chain($f), $expected);
        $this->assertEquals($chain_($f), $expected);
        $this->assertEquals($chain__($f), $expected);
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

    public function testJustApCurried()
    {
        $a = Maybe::of(123);
        $fm = Maybe::of(function ($x) {
            return $x + 1;
        });

        $ap = $a->ap;
        $ap_ = $a->ap();
        $ap__ = $ap();
        $expected = Just(124);

        $this->assertEquals($ap($fm), $expected);
        $this->assertEquals($ap_($fm), $expected);
        $this->assertEquals($ap__($fm), $expected);
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

    public function testJustGetOrElseCurried()
    {
        $a = Maybe::of(2);
        $g = $a->getOrElse;
        $g_ = $a->getOrElse();
        $g__ = $g();

        $this->assertEquals($g(12), 2);
        $this->assertEquals($g_(12), 2);
        $this->assertEquals($g__(12), 2);
    }

    public function testJustFold()
    {
        $a = Maybe::of(3)->fold(function () {
            return 15;
        }, identity());
        $this->assertEquals(3, $a);
    }

    public function testJustFoldCurried()
    {
        $f = function () {
            return 15;
        };
        $g = function ($x) {
            return $x + 1;
        };

        $a = Maybe::of(3);
        $fold = $a->fold;
        $fold_ = $a->fold();
        $fold__ = $fold();

        $foldF = $fold($f);
        $foldF_ = $fold_($f);
        $foldF__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 4);
        $this->assertEquals($fold_($f, $g), 4);
        $this->assertEquals($fold__($f, $g), 4);
        $this->assertEquals($foldF($g), 4);
        $this->assertEquals($foldF_($g), 4);
        $this->assertEquals($foldF__($g), 4);
    }

    public function testJustCata()
    {
        $a = Maybe::of(3)->cata(function () {
            return 15;
        }, identity());
        $this->assertEquals(3, $a);
    }

    public function testJustCataCurried()
    {
        $f = function () {
            return 15;
        };
        $g = function ($x) {
            return $x + 1;
        };

        $a = Maybe::of(3);
        $fold = $a->cata;
        $fold_ = $a->cata();
        $fold__ = $fold();

        $foldF = $fold($f);
        $foldF_ = $fold_($f);
        $foldF__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 4);
        $this->assertEquals($fold_($f, $g), 4);
        $this->assertEquals($fold__($f, $g), 4);
        $this->assertEquals($foldF($g), 4);
        $this->assertEquals($foldF_($g), 4);
        $this->assertEquals($foldF__($g), 4);
    }

    public function testJustAlt()
    {
        $a = Maybe::of(3);
        $b = Maybe::of(5);
        $c = new Nothing();

        $this->assertEquals($a->alt($b), $a);
        $this->assertEquals($a->alt($c), $a);
    }

    public function testJustReduce()
    {
        $a = Maybe::of(3)->reduce(function ($prev, $curr) {
            return $prev + $curr;
        }, 2);
        $this->assertEquals($a, 5);
    }

    public function testJustReduceCurried()
    {
        $a = Maybe::of(3);
        $reduce = $a->reduce;
        $reduce_ = $a->reduce();
        $reduce__ = $reduce();

        $f = function ($prev, $curr) {
            return $prev + $curr;
        };

        $reduceF = $a->reduce($f);
        $reduceF_ = $reduce($f);
        $reduceF__ = $reduce_($f);
        $reduceF___ = $reduce__($f);

        $initial = 5;
        $expected = 8;

        $this->assertEquals($reduce($f, $initial), $expected);
        $this->assertEquals($reduce_($f, $initial), $expected);
        $this->assertEquals($reduce__($f, $initial), $expected);
        $this->assertEquals($reduceF($initial), $expected);
        $this->assertEquals($reduceF_($initial), $expected);
        $this->assertEquals($reduceF__($initial), $expected);
        $this->assertEquals($reduceF___($initial), $expected);
    }

    public function testJustToString()
    {
        $a = Maybe::of([1, 2]);
        $b = Maybe::of('foo');
        $c = Maybe::of(12);

        $expectedA = "Just(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Just('foo')";
        $expectedC = "Just(12)";
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

    public function testNothingToString()
    {
        $a = new Nothing();
        $expectedA = "Nothing()";
        ob_start();
        echo $a;
        $actualA = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($expectedA, $actualA);
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

    public function testNothingMapCurried()
    {
        $a = Nothing();
        $map = $a->map;
        $map_ = $a->map();
        $map__ = $map();

        $f = function ($x) {
            return $x + 1;
        };
        $expected = Nothing();

        $this->assertEquals($map($f), $expected);
        $this->assertEquals($map_($f), $expected);
        $this->assertEquals($map__($f), $expected);
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

    public function testNothingApCurried()
    {
        $a = Nothing();
        $b = Nothing();

        $ap = $a->ap;
        $ap_ = $a->ap();
        $ap__ = $ap();

        $expected = Nothing();

        $this->assertEquals($ap($b), $expected);
        $this->assertEquals($ap_($b), $expected);
        $this->assertEquals($ap__($b), $expected);
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

    public function testNothingChainCurried()
    {
        $a = Nothing();
        $f = function ($x) {
            return Maybe::of($x + 1);
        };

        $chain = $a->chain;
        $chain_ = $a->chain();
        $chain__ = $chain();

        $expected = Nothing();

        $this->assertEquals($chain($f), $expected);
        $this->assertEquals($chain_($f), $expected);
        $this->assertEquals($chain__($f), $expected);
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

    public function testNothingBindCurried()
    {
        $a = Nothing();
        $f = function ($x) {
            return Maybe::of($x + 1);
        };

        $chain = $a->bind;
        $chain_ = $a->bind();
        $chain__ = $chain();

        $expected = Nothing();

        $this->assertEquals($chain($f), $expected);
        $this->assertEquals($chain_($f), $expected);
        $this->assertEquals($chain__($f), $expected);
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

    public function testNothingFlatMapCurried()
    {
        $a = Nothing();
        $f = function ($x) {
            return Maybe::of($x + 1);
        };

        $chain = $a->flatMap;
        $chain_ = $a->flatMap();
        $chain__ = $chain();

        $expected = Nothing();

        $this->assertEquals($chain($f), $expected);
        $this->assertEquals($chain_($f), $expected);
        $this->assertEquals($chain__($f), $expected);
    }

    public function testNothingToEither()
    {
        $a = Maybe::fromNullable(null)
        ->toEither('Failure');

        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left('Failure'), $a);
    }

    public function testNothingToEitherCurried()
    {
        $te = Maybe::fromNullable(null)->toEither();
        $this->assertEquals($te('Failure'), new Left('Failure'));
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

    public function testNothingGetOrElseCurried()
    {
        $goe = Maybe::fromNullable(null)->getOrElse();
        $this->assertEquals($goe('Failure'), 'Failure');
    }

    public function testNothingFold()
    {
        $a = Maybe::fromNullable(null)->fold(function () {
            return 10;
        }, function ($x) {
            return $x + 1;
        });
        $this->assertEquals($a, 10);
    }

    public function testNothingFoldCurried()
    {
        $f = function () {
            return 15;
        };
        $g = function ($x) {
            return $x + 1;
        };

        $a = Maybe::fromNullable(null);
        $fold = $a->fold;
        $fold_ = $a->fold();
        $fold__ = $fold();

        $foldF = $fold($f);
        $foldF_ = $fold_($f);
        $foldF__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 15);
        $this->assertEquals($fold_($f, $g), 15);
        $this->assertEquals($fold__($f, $g), 15);
        $this->assertEquals($foldF($g), 15);
        $this->assertEquals($foldF_($g), 15);
        $this->assertEquals($foldF__($g), 15);
    }

    public function testNothingCata()
    {
        $a = Maybe::fromNullable(null)->cata(function () {
            return 10;
        }, function ($x) {
            return $x + 1;
        });
        $this->assertEquals($a, 10);
    }

    public function testNothingCataCurried()
    {
        $f = function () {
            return 15;
        };
        $g = function ($x) {
            return $x + 1;
        };

        $a = Maybe::fromNullable(null);
        $fold = $a->cata;
        $fold_ = $a->cata();
        $fold__ = $fold();

        $foldF = $fold($f);
        $foldF_ = $fold_($f);
        $foldF__ = $fold__($f);

        $this->assertEquals($fold($f, $g), 15);
        $this->assertEquals($fold_($f, $g), 15);
        $this->assertEquals($fold__($f, $g), 15);
        $this->assertEquals($foldF($g), 15);
        $this->assertEquals($foldF_($g), 15);
        $this->assertEquals($foldF__($g), 15);
    }

    public function testNothingAlt()
    {
        $a = new Nothing();
        $b = Maybe::of(3);
        $c = new Nothing();

        $this->assertEquals($a->alt($b), $b);
        $this->assertEquals($a->alt($c), $c);
    }

    public function testNothingAltCurried()
    {
        $a = new Nothing();
        $b = Maybe::of(3);

        $alt = $a->alt;
        $alt_ = $a->alt();
        $alt__ = $alt();

        $this->assertEquals($alt($b), $b);
        $this->assertEquals($alt_($b), $b);
        $this->assertEquals($alt__($b), $b);
    }

    public function testNothingReduce()
    {
        $a = (new Nothing())->reduce(function ($prev, $curr) {
            return $prev + $curr;
        }, 2);
        $this->assertEquals($a, 2);
    }

    public function testNothingReduceCurried()
    {
        $a = Nothing();

        $reduce = $a->reduce;
        $reduce_ = $a->reduce();
        $reduce__ = $reduce();

        $expected = 1;
        $f = function ($prev, $curr) {
            return $prev + $curr;
        };

        $this->assertEquals($reduce($f, 1), $expected);
        $this->assertEquals($reduce_($f, 1), $expected);
        $this->assertEquals($reduce_($f, 1), $expected);
        $this->assertEquals(($reduce($f))(1), $expected);
        $this->assertEquals(($reduce_($f))(1), $expected);
        $this->assertEquals(($reduce__($f))(1), $expected);
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
        $either = $a->toEither(0);

        $this->assertInstanceOf(Right::class, $either);
        $this->assertEquals(Either::of(85), $either);
        $this->assertEquals(new Right(85), $either);
    }

    public function testJustToEitherCurried()
    {
        $a = Maybe::of(85);
        $te = $a->toEither;
        $te_ = $a->toEither();
        $te__ = $te();

        $expected = new Right(85);

        $this->assertEquals($te(0), $expected);
        $this->assertEquals($te_(0), $expected);
        $this->assertEquals($te__(0), $expected);
    }

    public function testJustToValidation()
    {
        $a = Maybe::of(85);
        $vali = $a->toValidation(80);
        $this->assertInstanceOf(Success::class, $vali);
        $this->assertEquals(Validation::of(85), $vali);
        $this->assertEquals(new Success(85), $vali);
    }

    public function testJustToValidationCurried()
    {
        $a = Maybe::of(85);
        $te = $a->toValidation;
        $te_ = $a->toValidation();
        $te__ = $te();

        $expected = new Success(85);

        $this->assertEquals($te(0), $expected);
        $this->assertEquals($te_(0), $expected);
        $this->assertEquals($te__(0), $expected);
    }

    public function testNothingToValidation()
    {
        $a = Maybe::fromNullable(null);
        $vali = $a->toValidation('foobar');
        $this->assertInstanceOf(Failure::class, $vali);
        $this->assertEquals(new Failure('foobar'), $vali);
    }

    public function testNothingToValidationCurried()
    {
        $a = new Nothing();
        $te = $a->toValidation;
        $te_ = $a->toValidation();
        $te__ = $te();

        $expected = new Failure(0);

        $this->assertEquals($te(0), $expected);
        $this->assertEquals($te_(0), $expected);
        $this->assertEquals($te__(0), $expected);
    }
}
