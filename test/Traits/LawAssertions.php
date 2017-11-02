<?php

namespace Phantasy\Test\Traits;

use function Phantasy\Core\{compose, id, concat};
use Eris\Generator;

trait LawAssertions
{
    use Eris\TestTrait;

    public function assertSetoidLaws($of)
    {
        $this->forAll(Generator\nat(), Generator\nat(), Generator\nat())
            ->then(function ($a, $b, $c) {
                $this->assertTrue($of($a)->equals($of($a)));
                $this->assertEquals($of($a)->equals($of($b)), $of($b)->equals($of($a)));
                if ($of($a)->equals($of($b)) && $of($b)->equals($of($c))) {
                    $this->assertTrue($of($a)->equals($of($c)));
                }
            });
    }

    public function assertOrdLaws($a)
    {

    }

    public function assertSemigroupoidLaws($a)
    {

    }

    public function assertCategoryLaws($a)
    {

    }

    public function assertSemigroupLaws($a)
    {

    }

    public function assertMonoidLaws($a)
    {

    }

    public function assertGroupLaws($a)
    {

    }

    public function assertFunctorLaws($a)
    {
    }

    public function assertContravariantLaws($a)
    {

    }

    public function assertApplyLaws($a)
    {

    }

    public function assertApplicativeLaws($a)
    {

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
