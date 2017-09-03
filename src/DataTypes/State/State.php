<?php

namespace Phantasy\DataTypes\State;

use function Phantasy\Core\curry;
use Phantasy\Traits\CurryNonPublicMethods;

final class State
{
    use CurryNonPublicMethods;

    private $func = null;

    public function __construct(callable $f)
    {
        $this->func = $f;
    }

    private static function of($x) : State
    {
        return new static(function ($s) use ($x) {
            return [$x, $s];
        });
    }

    private function run($s)
    {
        return call_user_func($this->func, $s);
    }

    private function map(callable $f) : State
    {
        return new static(function ($s) use ($f) {
            list ($x, $s2) = $this->run($s);
            return [$f($x), $s2];
        });
    }

    private function ap(State $m) : State
    {
        return new static(function ($s) use ($m) {
            list ($x1, $s1) = $this->run($s);
            list ($x2, $s2) = $m->run($s1);
            return [$x2($x1), $s1];
        });
    }

    private function chain(callable $f) : State
    {
        return new static(function ($s) use ($f) {
            list ($x, $s2) = $this->run($s);
            return $f($x)->run($s2);
        });
    }

    private function bind(callable $f) : State
    {
        return $this->chain($f);
    }

    private function flatMap(callable $f) : State
    {
        return $this->chain($f);
    }
}

function State()
{
    return curry(function (callable $f) {
        return new State($f);
    })(...func_get_args());
}
