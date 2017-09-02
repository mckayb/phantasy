<?php

namespace Phantasy\DataTypes\Validation;

use Phantasy\DataTypes\Maybe\{Maybe, Just};
use Phantasy\DataTypes\Either\{Either, Right};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\curry;

final class Success extends Validation
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Success(" . var_export($this->value, true) . ")";
    }

    private function equals(Validation $v) : bool
    {
        return $this == $v;
    }

    private function map(callable $f) : Validation
    {
        return new Success($f($this->value));
    }

    private function ap(Validation $validationWithFunc) : Validation
    {
        $val = $this->value;
        return $validationWithFunc->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    private function concat(Validation $v) : Validation
    {
        return $v;
    }

    private function fold(callable $f, callable $g)
    {
        return $g($this->value);
    }

    private function bimap(callable $f, callable $g) : Validation
    {
        return new Success($g($this->value));
    }

    private function alt(Validation $v) : Validation
    {
        return $this;
    }

    private function reduce(callable $f, $acc)
    {
        return $f($acc, $this->value);
    }

    // Aliases
    private function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    public function toEither() : Either
    {
        return new Right($this->value);
    }

    public function toMaybe() : Maybe
    {
        return new Just($this->value);
    }
}

function Success()
{
    return curry(function ($x) {
        return new Success($x);
    })(...func_get_args());
}
