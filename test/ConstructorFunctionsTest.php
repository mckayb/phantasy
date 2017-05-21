<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Writer\Writer;
use Phantasy\DataTypes\Reader\Reader;
use Phantasy\DataTypes\Maybe\{Nothing, Just};
use Phantasy\DataTypes\Either\{Left, Right};
use Phantasy\DataTypes\Validation\{Failure, Success};
use Phantasy\DataTypes\LinkedList\{Cons, Nil};

class ConstructorFunctionsTest extends TestCase
{
    public function testWriter()
    {
        $f = function () {
            return 'test';
        };
        $this->assertEquals(Writer($f), new Writer($f));
    }

    public function testReader()
    {
        $f = function ($x) {
            return $x;
        };
        $this->assertEquals(Reader($f), new Reader($f));
    }

    public function testJust()
    {
        $x = 12;
        $this->assertEquals(Just($x), new Just($x));
    }

    public function testNothing()
    {
        $this->assertEquals(Nothing(), new Nothing());
    }

    public function testLeft()
    {
        $x = 'foo bar';
        $this->assertEquals(Left($x), new Left($x));
    }

    public function testRight()
    {
        $this->assertEquals(Right(true), new Right(true));
    }

    public function testFailure()
    {
        $this->assertEquals(Failure([1, 2, 3]), new Failure([1, 2, 3]));
    }

    public function testSuccess()
    {
        $this->assertEquals(Success(120), new Success(120));
    }

    public function testCons()
    {
        $this->assertEquals(Cons(2, Nil()), new Cons(2, new Nil()));
    }

    public function testNil()
    {
        $this->assertEquals(Nil(), new Nil());
    }
}
