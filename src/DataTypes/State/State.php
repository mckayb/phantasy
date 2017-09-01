<?php

namespace Phantasy\DataTypes\State;

final class State
{
    private $func = null;

    public function __construct(callable $f)
    {
        $this->func = $f;
    }

    public static function of($x) : State
    {
        return new State(function ($s) use ($x) {
            return [$x, $s];
        });
    }

    public function run($s)
    {
        return call_user_func($this->func, $s);
    }

    public function map(callable $f) : State
    {
        return new State(function ($s) use ($f) {
            list ($x, $s2) = $this->run($s);
            return [$f($x), $s2];
        });
    }

    public function ap(State $m) : State
    {
        return new State(function ($s) use ($m) {
            list ($x1, $s1) = $this->run($s);
            list ($x2, $s2) = $m->run($s1);
            return [$x2($x1), $s1];
        });
    }

    public function chain(callable $f) : State
    {
        return new State(function ($s) use ($f) {
            list ($x, $s2) = $this->run($s);
            return $f($x)->run($s2);
        });
    }

    public function bind(callable $f) : State
    {
        return $this->chain($f);
    }

    public function flatMap(callable $f) : State
    {
        return $this->chain($f);
    }
}

function State(callable $f) : State
{
    return new State($f);
}
