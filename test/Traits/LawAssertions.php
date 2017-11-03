<?php

namespace Phantasy\Test\Traits;

use function Phantasy\Core\{curry, compose, id, concat};
use Eris\Generator;

trait LawAssertions
{
    use \Eris\TestTrait;

    public function assertSetoidLaws(callable $of)
    {
        $this->forAll(Generator\int(), Generator\int(), Generator\int())
            ->then(function ($a, $b, $c) use ($of) {
                // reflexivity
                $this->assertTrue($of($a)->equals($of($a)));

                // symmetry
                $this->assertEquals($of($a)->equals($of($b)), $of($b)->equals($of($a)));

                // transitivity
                if ($of($a)->equals($of($b)) && $of($b)->equals($of($c))) {
                    $this->assertTrue($of($a)->equals($of($c)));
                }
            });
    }

    public function assertOrdLaws(callable $of)
    {
        $this->forAll(Generator\int(), Generator\int(), Generator\int())
            ->then(function ($a, $b, $c) use ($of) {
                // totality
                $this->assertTrue($of($a)->lte($of($b)) || $of($b)->lte($of($a)));

                // antisymmetry
                if ($of($a)->lte($of($b)) && $of($b)->lte($of($a))) {
                    $this->assertTrue($of($a)->equals($of($b)));
                }

                // transitivity
                if ($of($a)->lte($of($b)) && $of($b)->lte($of($c))) {
                    $this->assertTrue($of($a)->lte($of($c)));
                }
            });
    }

    public function assertSemigroupoidLaws(callable $of)
    {
        $this->forAll(Generator\int(), Generator\int(), Generator\int())
            ->then(function ($a, $b, $c) use ($of) {
                // associativity
                $this->assertEquals(
                    $of($a)->compose($of($b))->compose($of($c)),
                    $of($a)->compose($of($b)->compose($of($c)))
                );
            });
    }

    public function assertCategoryLaws(string $clss, callable $of)
    {
        $this->forAll(Generator\int())
            ->then(function ($a) use ($clss, $of) {
                // right identity
                $this->assertEquals(
                    $of($a)->compose($clss::id()),
                    $of($a)
                );

                // left identity
                $this->assertEquals(
                    $clss::id()->compose($of($a)),
                    $of($a)
                );
            });
    }

    public function assertSemigroupLaws(callable $of)
    {
        $this->forAll(Generator\int(), Generator\int(), Generator\int())
            ->then(function ($a, $b, $c) use ($of) {
                // associativity
                $this->assertEquals(
                    $of($a)->concat($of($b))->concat($of($c)),
                    $of($a)->concat($of($b)->concat($of($c)))
                );
            });
    }

    public function assertMonoidLaws(string $clss, callable $of)
    {
        $this->forAll(Generator\int())
            ->then(function ($a) use ($clss, $of) {
                // right identity
                $this->assertEquals(
                    $of($a)->concat($clss::empty()),
                    $of($a)
                );

                // left identity
                $this->assertEquals(
                    $clss::empty()->concat($of($a)),
                    $of($a)
                );
            });
    }

    public function assertGroupLaws(string $clss, callable $of)
    {
        $this->forAll(Generator\int())
            ->then(function ($a) use ($clss, $of) {
                // right inverse
                $this->assertEquals(
                    $of($a)->concat($of($a)->invert()),
                    $clss::empty()
                );

                // left inverse
                $this->assertEquals(
                    $of($a)->invert()->concat($of($a)),
                    $clss::empty()
                );
            });
    }

    public function assertFunctorLaws(callable $of)
    {
        $this->forAll(Generator\string())
            ->then(function ($a) use ($of) {
                $f = concat('foo');
                $g = concat('bar');

                // identity
                $this->assertEquals($of($a)->map(id()), $of($a));

                // composition
                $this->assertEquals(
                    $of($a)->map($g)->map($f),
                    $of($a)->map(compose($f, $g))
                );
            });
    }

    public function assertContravariantLaws(callable $of)
    {
        $this->forAll(Generator\string())
            ->then(function ($a) use ($of) {
                $f = concat('foo');
                $g = concat('bar');

                // identity
                $this->assertEquals($of($a)->contramap(id()), $of($a));

                // composition
                $this->assertEquals(
                    $of($a)->contramap(compose($f, $g)),
                    $of($a)->contramap($f)->contramap($g)
                );
            });
    }

    public function assertApplyLaws(callable $of)
    {
        $this->forAll(Generator\int())
            ->then(function ($a) use ($of) {
                $f = function ($x) {
                    return $x + 4;
                };

                $g = function ($y) {
                    return $y / 2;
                };

                $compose = curry(function ($f, $g, $x) {
                    return $f($g($x));
                });

                // composition
                $this->assertEquals(
                    $of($a)->ap($of($g)->ap($of($f)->map($compose))),
                    $of($a)->ap($of($g))->ap($of($f))
                );
            });
    }

    public function assertApplicativeLaws(string $clss, callable $of)
    {
        $this->forAll(Generator\int())
            ->then(function ($a) use ($of, $clss) {
                $f = function ($x) {
                    return $x + 4;
                };

                // identity
                $this->assertEquals(
                    $of($a)->ap($clss::of(id())),
                    $of($a)
                );

                // homomorphism
                $this->assertEquals(
                    $clss::of($a)->ap($clss::of($f)),
                    $clss::of($f($a))
                );

                // interchange
                // TODO: FIX ME
                // $this->assertEquals(
                    // $clss::of($a)->ap($of($f)),
                    // $of($f)->ap($clss::of(function ($f) use ($of, $a) {
                        // return $f($of($a));
                    // }))
                // );
            });
    }

    public function assertAltLaws($a)
    {

    }

    public function assertPlusLaws($a)
    {

    }

    public function assertAlternativeLaws($a)
    {

    }

    public function assertFoldableLaws($a)
    {

    }

    public function assertTraversableLaws($a)
    {

    }

    public function assertChainLaws($a)
    {

    }

    public function assertChainRecLaws($a)
    {

    }

    public function assertMonadLaws($a)
    {

    }

    public function assertExtendLaws($a)
    {

    }

    public function assertComonadLaws($a)
    {

    }

    public function assertBifunctorLaws($a)
    {

    }

    public function assertProfunctorLaws($a)
    {

    }
}
