<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\Test\Traits\LawAssertions;
use Phantasy\DataTypes\IO\IO;
use function Phantasy\DataTypes\IO\IO;
use function Phantasy\Core\curry;

class IOTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $this->assertFunctorLaws(IO::of());
        $this->assertApplyLaws(IO::of());
        $this->assertApplicativeLaws(IO::class, IO::of());
    }

    public function testIOFunc()
    {
        $f = function () {
            echo 'Foo bar';
        };

        $this->assertEquals(
            IO($f),
            new IO($f)
        );
    }

    public function testIOFuncCurried()
    {
        $f = function () {
            echo 'Foo bar';
        };

        $io = IO();

        $this->assertEquals(
            $io($f),
            new IO($f)
        );
    }

    public function testIOOf()
    {
        $this->assertEquals(IO::of(1), IO(function () {
            return 1;
        }));
        $this->assertEquals(IO::of(1)->run(), IO(function () {
            return 1;
        })->run());

        $this->assertEquals(IO::of(1)->run(), 1);
    }

    public function testIOOfCurried()
    {
        $of = IO::of();
        $this->assertEquals($of(1), IO(function () {
            return 1;
        }));
    }

    public function testIOMap()
    {
        $a = IO(function () {
            return file_get_contents(dirname(__FILE__) . '/Fixtures/config.json');
        })->map(function ($x) {
            return json_decode($x, true);
        })->run();

        $this->assertEquals($a, ["DB_HOST" => "foo"]);
    }

    public function testIOMapCurried()
    {
        $f = function ($x) {
            return json_decode($x, true);
        };

        $a = IO(function () {
            return file_get_contents(dirname(__FILE__) . '/Fixtures/config.json');
        });
        $map = $a->map;
        $map_ = $a->map();
        $map__ = $map();

        $this->assertEquals($map($f)->run(), ["DB_HOST" => "foo"]);
        $this->assertEquals($map_($f)->run(), ["DB_HOST" => "foo"]);
        $this->assertEquals($map__($f)->run(), ["DB_HOST" => "foo"]);
    }

    public function testIOAp()
    {
        $a = IO::of(1)->ap(IO::of(2)->ap(IO::of(curry(function ($a, $b) {
            return $a + $b;
        }))));

        $this->assertEquals($a, IO::of(3));
        $this->assertEquals($a->run(), 3);
    }

    public function testIOApCurried()
    {
        $a = IO::of(1);

        $ap = $a->ap;
        $ap_ = $a->ap();
        $ap__ = $ap();

        $f = function ($x) {
            return $x + 1;
        };

        $this->assertEquals($ap(IO::of($f)), IO::of(2));
        $this->assertEquals($ap_(IO::of($f)), IO::of(2));
        $this->assertEquals($ap__(IO::of($f)), IO::of(2));
    }

    public function testIOChain()
    {
        $a = IO::of('foobar')->chain(function ($x) {
            return IO(function () use ($x) {
                echo $x;
                return $x;
            });
        });

        ob_start();
        $res = $a->run();
        $contents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($res, 'foobar');
        $this->assertEquals($contents, 'foobar');
    }

    public function testIOChainCurried()
    {
        $a = IO::of('foobar');
        $chain = $a->chain;
        $chain_ = $a->chain();
        $chain__ = $chain();
        $f = function ($x) {
            return IO(function () use ($x) {
                echo $x;
                return $x;
            });
        };

        ob_start();
        $res = $chain($f)->run();
        $res_ = $chain_($f)->run();
        $res__ = $chain__($f)->run();
        $contents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($res, 'foobar');
        $this->assertEquals($res_, 'foobar');
        $this->assertEquals($res__, 'foobar');
        $this->assertEquals($contents, 'foobarfoobarfoobar');
    }

    public function testIOBind()
    {
        $a = IO::of('foobar')->bind(function ($x) {
            return IO(function () use ($x) {
                echo $x;
                return $x;
            });
        });

        ob_start();
        $res = $a->run();
        $contents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($res, 'foobar');
        $this->assertEquals($contents, 'foobar');
    }

    public function testIOBindCurried()
    {
        $a = IO::of('foobar');
        $chain = $a->bind;
        $chain_ = $a->bind();
        $chain__ = $chain();
        $f = function ($x) {
            return IO(function () use ($x) {
                echo $x;
                return $x;
            });
        };

        ob_start();
        $res = $chain($f)->run();
        $res_ = $chain_($f)->run();
        $res__ = $chain__($f)->run();
        $contents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($res, 'foobar');
        $this->assertEquals($res_, 'foobar');
        $this->assertEquals($res__, 'foobar');
        $this->assertEquals($contents, 'foobarfoobarfoobar');
    }

    public function testIOFlatMap()
    {
        $a = IO::of('foobar')->flatMap(function ($x) {
            return IO(function () use ($x) {
                echo $x;
                return $x;
            });
        });

        ob_start();
        $res = $a->run();
        $contents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($res, 'foobar');
        $this->assertEquals($contents, 'foobar');
    }

    public function testIOFlatMapCurried()
    {
        $a = IO::of('foobar');
        $chain = $a->flatMap;
        $chain_ = $a->flatMap();
        $chain__ = $chain();
        $f = function ($x) {
            return IO(function () use ($x) {
                echo $x;
                return $x;
            });
        };

        ob_start();
        $res = $chain($f)->run();
        $res_ = $chain_($f)->run();
        $res__ = $chain__($f)->run();
        $contents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($res, 'foobar');
        $this->assertEquals($res_, 'foobar');
        $this->assertEquals($res__, 'foobar');
        $this->assertEquals($contents, 'foobarfoobarfoobar');
    }
}
