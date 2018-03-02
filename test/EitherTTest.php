<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\Test\Traits\LawAssertions;
use Phantasy\Traits\CurryNonPublicMethods;
use Phantasy\DataTypes\Either\{EitherT, Either};
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\DataTypes\Either\{EitherT, Right};
use function Phantasy\DataTypes\Maybe\{Just, Nothing};

class EitherTTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $a = EitherT::of(Maybe::class);
        $b = function () {
            return EitherT(function () {
                return Nothing();
            });
        };

        $c = new class {
            use CurryNonPublicMethods;

            protected static function of($x)
            {
                return EitherT::of(Maybe::class, $x);
            }
        };

        $this->assertFunctorLaws($a);
        $this->assertFunctorLaws($b);
        $this->assertApplyLaws($a);
        $this->assertApplyLaws($b);
        $this->assertApplicativeLaws(get_class($c), $a);
        $this->assertApplicativeLaws(get_class($c), $b);
        $this->assertChainLaws($a);
        $this->assertChainLaws($b);
        $this->assertMonadLaws(get_class($c), $a);
        $this->assertMonadLaws(get_class($c), $b);
    }

    public function testOf()
    {
        $a = EitherT::of(Maybe::class, 1)->run();
        $this->assertEquals(
            $a,
            Just(Right(1))
        );
    }

    public function testMapJust()
    {
        $a = EitherT::of(Maybe::class, 1)->map(function ($x) {
            return $x + 1;
        })->run();
        $this->assertEquals(
            $a,
            Just(Right(2))
        );
    }

    public function testMapNothing()
    {
        $a = EitherT(function () {
            return Nothing();
        })->map(function ($x) {
            return $x + 1;
        })->run();

        $this->assertEquals(
            $a,
            Nothing()
        );
    }

    public function testAp()
    {
        $a = EitherT::of(Maybe::class, 1)->ap(EitherT::of(Maybe::class, function ($x) {
            return $x + 1;
        }));

        $this->assertEquals(
            $a->run(),
            Just(Right(2))
        );
    }

    public function testApNothing()
    {
        $a = EitherT(function () {
            return Nothing();
        })->ap(EitherT::of(Maybe::class, function ($x) {
            return $x + 1;
        }));

        $b = EitherT::of(Maybe::class, 1)->ap(EitherT(function () {
            return Nothing();
        }));

        $this->assertEquals(
            $a->run(),
            Nothing()
        );

        $this->assertEquals(
            $b->run(),
            Nothing()
        );
    }

    public function testChain()
    {
        $a = EitherT::of(Maybe::class, 124)->chain(function ($x) {
            return EitherT::of(Maybe::class, $x / 2);
        });

        $this->assertEquals(
            $a->run(),
            Just(Right(62))
        );
    }

    public function testChainNothing()
    {
        $a = EitherT::of(Maybe::class, 124)->chain(function ($x) {
            return EitherT(function () {
                return Nothing();
            });
        });

        $this->assertEquals(
            $a->run(),
            Nothing()
        );
    }

    public function testBind()
    {
        $a = EitherT::of(Maybe::class, 124)->bind(function ($x) {
            return EitherT::of(Maybe::class, $x / 2);
        });

        $this->assertEquals(
            $a->run(),
            Just(Right(62))
        );
    }

    public function testBindNothing()
    {
        $a = EitherT::of(Maybe::class, 124)->bind(function ($x) {
            return EitherT(function () {
                return Nothing();
            });
        });

        $this->assertEquals(
            $a->run(),
            Nothing()
        );
    }

    public function testFlatMap()
    {
        $a = EitherT::of(Maybe::class, 124)->flatMap(function ($x) {
            return EitherT::of(Maybe::class, $x / 2);
        });

        $this->assertEquals(
            $a->run(),
            Just(Right(62))
        );
    }

    public function testFlatMapNothing()
    {
        $a = EitherT::of(Maybe::class, 124)->flatMap(function ($x) {
            return EitherT(function () {
                return Nothing();
            });
        });

        $this->assertEquals(
            $a->run(),
            Nothing()
        );
    }
}
