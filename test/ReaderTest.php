<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Reader\Reader;
use function Phantasy\Core\concat;
use function Phantasy\DataTypes\Reader\Reader;
use Phantasy\Test\Traits\LawAssertions;

class ReaderTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $this->assertFunctorLaws(Reader::of());
        $this->assertApplyLaws(Reader::of());
        $this->assertApplicativeLaws(Reader::class, Reader::of());
    }

    public function testReaderFunc()
    {
        $a = function ($x) {
            return $x + 1;
        };
        $this->assertEquals(Reader($a), new Reader($a));
    }

    public function testReaderOf()
    {
        $r = Reader::of('Hello');
        $this->assertEquals($r->run('Test'), 'Hello');
    }

    public function testReaderOfIdentity()
    {
        $v = Reader::of('val');
        $a = Reader::of(function ($x) {
            return $x;
        });
        $this->assertEquals($v->ap($a), $v);
    }

    public function testReaderHomomorphism()
    {
        $x = 'foo';
        $f = function ($x) {
            return concat($x, 'bar');
        };
        $this->assertEquals(Reader::of($x)->ap(Reader::of($f)), Reader::of($f($x)));
    }

    public function testReaderInterchange()
    {
        $y = 'foo';
        $f = function ($x) {
            return concat($x, 'bar');
        };
        $u = Reader::of($f);
        $this->assertEquals(
            Reader::of($y)->ap($u),
            $u->ap(Reader::of(function ($f) use ($y) {
                return $f($y);
            }))
        );
    }

    public function testReaderMap()
    {
        $r = Reader::of('Hello')
            ->map(function ($x) {
                return concat($x, '!');
            });
        $this->assertEquals($r->run('Test'), 'Hello!');
    }

    public function testReaderAp()
    {
        $a = Reader::of('Hello');
        $b = Reader::of(function ($x) {
            return concat($x, ' World!');
        });

        $f = $a->ap($b);
        $this->assertEquals('Hello World!', $f->run('Test'));
    }

    public function testReaderChain()
    {
        $r = Reader::of('Hello')
            ->chain(function ($x) {
                return new Reader(function ($s) use ($x) {
                    return $s['ENVIRONMENT'] === 'production'
                        ? concat($x, ' Valued Customer!')
                        : concat($x, ' Devs!');
                });
            });

        $this->assertEquals($r->run([ 'ENVIRONMENT' => 'production' ]), 'Hello Valued Customer!');
        $this->assertEquals($r->run([ 'ENVIRONMENT' => 'development' ]), 'Hello Devs!');
    }

    public function testReaderChainAssociativity()
    {
        $m = Reader::of('hello');
        $f = function ($x) {
            return Reader::of(function ($s) use ($x) {
                return concat($x, 'foo');
            });
        };
        $g = function ($x) {
            return Reader::of(function ($s) use ($x) {
                return concat($x, 'bar');
            });
        };
        $this->assertEquals($m->chain($f)->chain($g), $m->chain(function ($x) use ($f, $g) {
            return $f($x)->chain($g);
        }));
    }

    public function testReaderBind()
    {
        $r = Reader::of('Hello')
            ->bind(function ($x) {
                return new Reader(function ($s) use ($x) {
                    return $s['ENVIRONMENT'] === 'production'
                        ? concat($x, ' Valued Customer!')
                        : concat($x, ' Devs!');
                });
            });

            $this->assertEquals($r->run([ 'ENVIRONMENT' => 'production' ]), 'Hello Valued Customer!');
            $this->assertEquals($r->run([ 'ENVIRONMENT' => 'development' ]), 'Hello Devs!');
    }

    public function testReaderFlatMap()
    {
        $r = Reader::of('Hello')
            ->flatMap(function ($x) {
                return new Reader(function ($s) use ($x) {
                    return $s['ENVIRONMENT'] === 'production'
                        ? concat($x, ' Valued Customer!')
                        : concat($x, ' Devs!');
                });
            });
        $this->assertEquals($r->run([ 'ENVIRONMENT' => 'production' ]), 'Hello Valued Customer!');
        $this->assertEquals($r->run([ 'ENVIRONMENT' => 'development' ]), 'Hello Devs!');
    }

    public function testReaderExtend()
    {
        $state = 1;
        $a = Reader::of('The number is ')
            ->chain(function ($x) {
                return new Reader(function (int $s) use ($x) : string {
                    return $s < 1
                        ? concat($x, 'less than 1, ')
                        : concat($x, 'greater than or equal to 1, ');
                });
            })
            ->extend(function (Reader $s) use ($state) : string {
                return $s->run($state - 1) . 'Joe.';
            })
            ->run($state);

        $this->assertEquals($a, 'The number is less than 1, Joe.');

        $b = Reader::of('The name is ')
            ->chain(function ($x) {
                return Reader(function ($s) use ($x) {
                    return concat($x, $s['name']);
                });
            })
            ->extend(function (Reader $r) {
                return $r->run([ 'name' => 'Jim' ]);
            })
            ->run([ 'name' => 'Joe' ]);
        $this->assertEquals($b, 'The name is Jim');
    }

    public function testReaderExtendCurried()
    {
        $state = ['name' => 'John'];

        $f = function (Reader $r) use ($state) : string {
            return $r->run(['name' => 'Joe']);
        };

        $g = function (Reader $r) use ($state) : string {
            return $r->run(['name' => 'Jim']);
        };

        $w = Reader::of('The name is ')
            ->extend($f)
            ->chain(function ($x) {
                return Reader(function ($s) use ($x) {
                    return concat($x, $s['name']);
                });
            })
            ->extend($g);
        $this->assertEquals($w->run($state), 'The name is Jim');
    }

    public function testReaderExtendLaws()
    {
        $state = ['num' => 10];

        $f = function (Reader $r) use ($state) : string {
            $val = $r->run($state);
            return $val . ' foo';
        };

        $g = function (Reader $r) use ($state) : string {
            $val = $r->run($state);
            return $val . ' bar';
        };

        $w = Reader::of('The number is ')
            ->chain(function ($x) {
                return Reader(function ($s) use ($x) {
                    return $s['num'] < 1
                        ? concat($x, 'less than 1.')
                        : concat($x, 'greater than or equal to 1');
                });
            });

        $this->assertEquals(
            $w->extend($g)->extend($f)->run($state),
            $w->extend(function ($w_) use ($f, $g) {
                return $f($w_->extend($g));
            })->run($state)
        );
    }

    public function testReaderExtract()
    {
        $a = Reader::of('Foo Bar')->chain(function (string $x) : Reader {
            return Reader(function (string $s) use ($x) : string {
                return concat($x, concat(' ', $s));
            });
        })->extend(function (Reader $x) : string {
            return $x->run('Baz');
        })->chain(function (string $x) : Reader {
            return Reader(function (string $s) use ($x) : string {
                return concat($x, $s);
            });
        })->extract('');
        $this->assertEquals($a, 'Foo Bar Baz');
    }
}
