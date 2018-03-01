<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\Test\Traits\LawAssertions;
use Phantasy\DataTypes\Maybe\MaybeT;
use Phantasy\DataTypes\Either\Either;
use function Phantasy\DataTypes\Maybe\{MaybeT, Just, Nothing};
use function Phantasy\DataTypes\Either\Right;

class MaybeTTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $a = MaybeT::of(Either::class);
        $b = function () {
            return MaybeT(function () {
                return Right(Nothing());
            });
        };
        $this->assertFunctorLaws($a);
        $this->assertFunctorLaws($b);
        $this->assertApplyLaws($a);
        $this->assertApplyLaws($b);
        // $this->assertApplicativeLaws(MaybeT::class), $a);
        // $this->assertApplicativeLaws(MaybeT::class, $b);
        $this->assertChainLaws($a);
        $this->assertChainLaws($b);
        // $this->assertMonadLaws(MaybeT::class), $a);
        // $this->assertMonadLaws(MaybeT::class, $b);
    }

    public function testOf()
    {
        $a = MaybeT::of(Either::class, 1)->run();
        $this->assertEquals(
            $a,
            Right(Just(1))
        );
    }

    public function testMapJust()
    {
        $a = MaybeT::of(Either::class, 1)->map(function ($x) {
            return $x + 1;
        })->run();
        $this->assertEquals(
            $a,
            Right(Just(2))
        );
    }

    public function testMapNothing()
    {
        $a = MaybeT(function () {
            return Either::of(Nothing());
        })->map(function ($x) {
            return $x + 1;
        })->run();

        $this->assertEquals(
            $a,
            Right(Nothing())
        );
    }

    public function testAp()
    {
        $a = MaybeT::of(Either::class, 1)->ap(MaybeT::of(Either::class, function ($x) {
            return $x + 1;
        }));

        $this->assertEquals(
            $a->run(),
            Right(Just(2))
        );
    }

    public function testApNothing()
    {
        $a = MaybeT(function () {
            return Either::of(Nothing());
        })->ap(MaybeT::of(Either::class, function ($x) {
            return $x + 1;
        }));

        $b = MaybeT::of(Either::class, 1)->ap(MaybeT(function () {
            return Either::of(Nothing());
        }));

        $this->assertEquals(
            $a->run(),
            Right(Nothing())
        );

        $this->assertEquals(
            $b->run(),
            Right(Nothing())
        );
    }

    public function testChain()
    {
        $a = MaybeT::of(Either::class, 124)->chain(function ($x) {
            return MaybeT::of(Either::class, $x / 2);
        });

        $this->assertEquals(
            $a->run(),
            Right(Just(62))
        );
    }

    public function testChainNothing()
    {
        $a = MaybeT::of(Either::class, 124)->chain(function ($x) {
            return MaybeT(function () {
                return Either::of(Nothing());
            });
        });

        $this->assertEquals(
            $a->run(),
            Right(Nothing())
        );
    }

    public function testBind()
    {
        $a = MaybeT::of(Either::class, 124)->bind(function ($x) {
            return MaybeT::of(Either::class, $x / 2);
        });

        $this->assertEquals(
            $a->run(),
            Right(Just(62))
        );
    }

    public function testBindNothing()
    {
        $a = MaybeT::of(Either::class, 124)->bind(function ($x) {
            return MaybeT(function () {
                return Either::of(Nothing());
            });
        });

        $this->assertEquals(
            $a->run(),
            Right(Nothing())
        );
    }

    public function testFlatMap()
    {
        $a = MaybeT::of(Either::class, 124)->flatMap(function ($x) {
            return MaybeT::of(Either::class, $x / 2);
        });

        $this->assertEquals(
            $a->run(),
            Right(Just(62))
        );
    }

    public function testFlatMapNothing()
    {
        $a = MaybeT::of(Either::class, 124)->flatMap(function ($x) {
            return MaybeT(function () {
                return Either::of(Nothing());
            });
        });

        $this->assertEquals(
            $a->run(),
            Right(Nothing())
        );
    }
}
