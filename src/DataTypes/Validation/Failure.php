<?php

namespace Phantasy\DataTypes\Validation;

use Phantasy\Traits\CurryNonPublicMethods;
use Phantasy\DataTypes\Either\{Either, Left};
use Phantasy\DataTypes\Maybe\{Maybe, Nothing};
use function Phantasy\Core\{concat, curry};

final class Failure extends Validation
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Failure(" . var_export($this->value, true) . ")";
    }

    private function equals(Validation $v) : bool
    {
        return $this === $v;
    }

    private function map(callable $f) : Validation
    {
        return $this;
    }

    private function ap(Validation $v) : Validation
    {
        return $this;
    }

    private function concat(Validation $v) : Validation
    {
        if ($v instanceof Success) {
            return $this;
        } else {
            return new Failure(concat($this->value, $v->value));
        }
    }

    private function fold(callable $f, callable $g)
    {
        return $f($this->value);
    }

    private function bimap(callable $f, callable $g) : Validation
    {
        return new Failure($f($this->value));
    }

    private function alt(Validation $v) : Validation
    {
        return $v;
    }

    private function reduce(callable $f, $acc)
    {
        return $acc;
    }

    // Aliases
    private function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    // Transformations
    public function toEither() : Either
    {
        return new Left($this->value);
    }

    public function toMaybe() : Maybe
    {
        return new Nothing();
    }
}

function Failure()
{
    return curry(function ($x) {
        return new Failure($x);
    })(...func_get_args());
}
