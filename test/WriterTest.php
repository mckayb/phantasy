<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Writer\Writer;
use function Phantasy\Core\concat;
use function Phantasy\DataTypes\Writer\Writer;
use function Phantasy\DataTypes\Either\Right;
use function Phantasy\DataTypes\Maybe\Just;

class WriterTest extends TestCase
{
    public function testWriterFunc()
    {
        $a = function () {
            return ['foo', 'bar'];
        };

        $this->assertEquals(Writer($a), new Writer($a));
    }

    public function testWriterOf()
    {
        $this->assertEquals(Writer::of(12)->run(), [12, []]);
    }

    public function testWriterMap()
    {
        $this->assertEquals(Writer::of(12)->map(function ($x) {
            return $x + 1;
        })->run(), [13, []]);
    }

    public function testWriterAp()
    {
        $this->assertEquals(
            Writer::of('foo', '')
                ->ap(Writer::of(function ($x) {
                    return concat($x, 'bar');
                }, ''))
                ->run(),
            ['foobar', '']
        );
    }

    public function testWriterChain()
    {
        $this->assertEquals(
            Writer::of('foo')
                ->chain(function ($x) {
                    return new Writer(function () use ($x) {
                        return [concat($x, 'bar'), ['Bar Stuff']];
                    });
                })
                ->run(),
            ['foobar', ['Bar Stuff']]
        );
    }

    public function testWriterBind()
    {
        $this->assertEquals(
            Writer::of('foo')
                ->bind(function ($x) {
                    return Writer::of(concat($x, 'bar'));
                })
                ->run(),
            ['foobar', []]
        );
    }

    public function testWriterFlatMap()
    {
        $this->assertEquals(
            Writer::of('foo')
                ->flatMap(function ($x) {
                    return Writer::of(concat($x, 'bar'));
                })
                ->run(),
            ['foobar', []]
        );
    }

    public function testWriterChainRec()
    {
        $a = Writer::chainRec(function ($next, $done, $v) {
            return Writer(function () use ($next, $done, $v) {
                return [$v >= 10000 ? $done($v) : $next($v + 1), [$v]];
            });
        }, 0);

        list($val, $log) = $a->run();
        $this->assertEquals(range(0, 10000), $log);
        $this->assertEquals($val, 10000);
    }

    public function testWriterChainRecCurried()
    {
        $writerChainRec = Writer::chainRec(function ($next, $done, $v) {
            return Writer(function () use ($next, $done, $v) {
                return [$v >= 100 ? $done($v) : $next($v + 1), [$v]];
            });
        });

        $a = $writerChainRec(0, []);
        list($val, $log) = $a->run();
        $this->assertEquals(range(0, 100), $log);
        $this->assertEquals($val, 100);
    }

    public function testWriterChainRecEquivalence()
    {
        $p = function ($x) {
            return $x >= 50;
        };

        $d = function ($x) {
            return Writer::of($x);
        };

        $n = function ($x) {
            return Writer::of($x + 1);
        };
        $i = 0;

        $first = Writer::chainRec(function ($next, $done, $v) use ($p, $d, $n) {
            return $p($v) ? $d($v)->map($done) : $n($v)->map($next);
        }, $i);

        $second = function ($v) use (&$second, $p, $d, $n) {
            return $p($v) ? $d($v) : $n($v)->chain($second);
        };
        $this->assertEquals($first, $second($i));
        $this->assertEquals($first->run(), $second($i)->run());
    }

    public function testWriterExtend()
    {
        $sum = function ($x) {
            return new class ($x) {
                public $x;
                public function __construct($x)
                {
                    $this->x = $x;
                }

                public function concat($a)
                {
                    return new static($this->x + $a->x);
                }

                public static function empty()
                {
                    return new static(0);
                }
            };
        };

        $exampleUser = [
            'name' => 'Jerry',
            'isHungry' => false
        ];

        $adventurer = function ($user, $monoid) {
            return Writer(function () use ($user, $monoid) {
                return [$user, $monoid];
            });
        };

        $slayDragon = function ($user) use ($sum, $adventurer) {
            return $adventurer($user, $sum(100));
        };

        $runFromDragon = function ($user) use ($sum, $adventurer) {
            return $adventurer($user, $sum(50));
        };

        $eat = function ($user) use ($adventurer, $sum) {
            return $user['isHungry']
                ? $adventurer(array_merge($user, ['isHungry' => false]), $sum(-100))
                : $adventurer($user, $sum(0));
        };

        $areWeHungry = function ($adv) {
            list($user, $hunger) = $adv;
            return $hunger->x > 200
                ? array_merge($user, ['isHungry' => true])
                : $user;
        };

        $battle1 = $slayDragon($exampleUser)->extend($areWeHungry)->chain($eat);
        $this->assertEquals([
            ['name' => 'Jerry', 'isHungry' => false],
            $sum(100)
        ], $battle1->run());

        $battle2 = $battle1->chain($slayDragon)->extend($areWeHungry)->chain($eat);
        $this->assertEquals([
            ['name' => 'Jerry', 'isHungry' => false],
            $sum(200)
        ], $battle2->run());

        $battle3 = $battle2->chain($runFromDragon)->extend($areWeHungry)->chain($eat);
        $this->assertEquals([
            ['name' => 'Jerry', 'isHungry' => false],
            $sum(150)
        ], $battle3->run());

        $battle4 = $battle3->chain($slayDragon)->extend($areWeHungry);
        $this->assertEquals([
            ['name' => 'Jerry', 'isHungry' => true],
            $sum(250)
        ], $battle4->run());
    }

    public function testWriterExtract()
    {
        $this->assertEquals(
            Writer::of(1)->extract(),
            1
        );
    }
}
