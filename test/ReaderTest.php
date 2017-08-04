<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Reader\Reader;
use function Phantasy\Core\concat;
use function Phantasy\DataTypes\Reader\Reader;

class ReaderTest extends TestCase
{
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
}
