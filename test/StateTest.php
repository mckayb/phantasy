<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\State\State;

class StateTest extends TestCase
{
    public function testStateOfNoInitialState()
    {
        $this->assertEquals(
            [2, null],
            State::of(2)->run(null)
        );
    }

    public function testStateOfWithInitialState()
    {
        $this->assertEquals(
            ['foo', 'bar'],
            State::of('foo')->run('bar')
        );
    }

    public function testStateMap()
    {
        $a = State::of(1)->map(function ($x) {
            return $x + 1;
        });
        $this->assertEquals(
            [2, null],
            $a->run(null)
        );
    }

    public function testStateAp()
    {
        $a = State::of(1);
        $b = State::of(function ($x) {
            return $x + 1;
        });

        $this->assertEquals(
            [2, null],
            $a->ap($b)->run(null)
        );
    }

    public function testStateChain()
    {
        $a = State::of(2)->chain(function ($x) {
            return State::of($x + 1);
        });

        $this->assertEquals(
            [3, null],
            $a->run(null)
        );
    }

    public function testStateChainAffectsStateAndVal()
    {
        $a = (new State(function ($s) {
            return [12, $s + 1];
        }))->chain(function ($x) {
            return new State(function ($s) use ($x) {
                return [$x - 1, $s + 1];
            });
        });

        $this->assertEquals(
            [11, 14],
            $a->run(12)
        );
    }

    public function testStateBind()
    {
        $a = State::of(2)->bind(function ($x) {
            return State::of($x + 1);
        });

        $this->assertEquals(
            [3, null],
            $a->run(null)
        );
    }

    public function testStateFlatMap()
    {
        $a = State::of(2)->flatMap(function ($x) {
            return State::of($x + 1);
        });

        $this->assertEquals(
            [3, null],
            $a->run(null)
        );
    }
}
