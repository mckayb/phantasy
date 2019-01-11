<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\Test\Traits\LawAssertions;
use Phantasy\Traits\CurryNonPublicMethods;
use Phantasy\DataTypes\Maybe\MaybeT;
use Phantasy\DataTypes\Either\Either;
use Phantasy\DataTypes\Reader\ReaderT;
use Phantasy\DataTypes\Reader\Reader;
use function Phantasy\DataTypes\Reader\{Reader, ReaderT};
use function Phantasy\DataTypes\Maybe\{MaybeT, Just, Nothing};
use function Phantasy\DataTypes\Either\Right;

class ReaderTTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $a = ReaderT::of(Either::class);

        $c = new class {
            use CurryNonPublicMethods;

            protected static function of($x)
            {
                return ReaderT::of(Either::class, $x);
            }
        };

        $this->assertFunctorLaws($a);
        $this->assertApplyLaws($a);
        $this->assertApplicativeLaws(get_class($c), $a);
        $this->assertChainLaws($a);
        $this->assertMonadLaws(get_class($c), $a);
    }

    public function testConstructorCallableReturnType()
    {
        $a = (new ReaderT(function ($s): Either {
            return Right($s);
        }))->run(1);

        $this->assertEquals($a, Right(1));
    }

    /**
     * @expectedException \Exception
     */
    public function testConstructorInvalidReturnTypeThrowsException()
    {
        $a = (new ReaderT(function ($x): int {
            return 1;
        }));
    }

    /**
     * @expectedException \Exception
     */
    public function testConstructorNoReturnTypeThrowsException()
    {
        $a = (new ReaderT(function ($x) {
            return 1;
        }));
    }

    public function testConstructorWorksWithArray()
    {
        $a = (new ReaderT(function ($x): array {
            return [1];
        }))->run(['foo']);
        $this->assertEquals($a, [1]);
    }

    public function testOf()
    {
        $a = ReaderT::of(Either::class, 1)->run([]);
        $this->assertEquals(
            $a,
            Right(1)
        );
    }

    public function testMap()
    {
        $a = ReaderT::of(Either::class, 1)->map(function ($x) {
            return $x + 1;
        })->run(['blue']);

        $this->assertEquals(
            $a,
            Right(2)
        );
    }

    public function testAp()
    {
        $a = ReaderT::of(Either::class, 1)->ap(ReaderT::of(Either::class, function ($x) {
            return $x + 1;
        }))->run(['blue']);
        $this->assertEquals($a, Right(2));
    }

    public function testChain()
    {
        $a = ReaderT::of(Either::class, 1)->chain(function ($x) {
            return ReaderT::of(Either::class, $x + 1);
        })->run(['blue']);

        $this->assertEquals($a, Right(2));
    }

    public function testFlatMap()
    {
        $a = ReaderT::of(Either::class, 1)->flatMap(function ($x) {
            return ReaderT::of(Either::class, $x + 1);
        })->run(['blue']);

        $this->assertEquals($a, Right(2));
    }

    public function testBind()
    {
        $a = ReaderT::of(Either::class, 1)->bind(function ($x) {
            return ReaderT::of(Either::class, $x + 1);
        })->run(['blue']);

        $this->assertEquals($a, Right(2));
    }
}
