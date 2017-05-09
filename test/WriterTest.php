<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Writer\Writer;
use function Phantasy\Core\concat;

class WriterTest extends TestCase
{
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
}
