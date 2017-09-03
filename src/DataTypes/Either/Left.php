<?php

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\{Maybe, Nothing};
use Phantasy\DataTypes\Validation\{Validation, Failure};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\curry;

final class Left extends Either
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Left(" . var_export($this->value, true) . ")";
    }

    public function equals(Either $e) : bool
    {
        return $this == $e;
    }

    private function map(callable $f) : Either
    {
        return $this;
    }

    private function ap(Either $eitherWithFunction) : Either
    {
        return $this;
    }

    private function chain(callable $f) : Either
    {
        return $this;
    }

    private function fold(callable $f, callable $g)
    {
        return $f($this->value);
    }

    private function bimap(callable $f, callable $g) : Either
    {
        return new Left($f($this->value));
    }

    private function alt(Either $e) : Either
    {
        return $e;
    }

    private function reduce(callable $f, $acc)
    {
        return $acc;
    }

    // Aliases
    private function bind(callable $f) : Either
    {
        return $this->chain($f);
    }

    private function flatMap(callable $f) : Either
    {
        return $this->chain($f);
    }

    private function cata($f, $g)
    {
        return $this->fold($f, $g);
    }

    // Conversions
    private function toMaybe() : Maybe
    {
        return new Nothing();
    }

    private function toValidation() : Validation
    {
        return new Failure($this->value);
    }
}

function Left()
{
    return curry(function ($x) {
        return new Left($x);
    })(...func_get_args());
}
