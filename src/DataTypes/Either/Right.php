<?php

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\{Maybe, Just};
use Phantasy\DataTypes\Validation\{Validation, Success};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\curry;

final class Right extends Either
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Right(" . var_export($this->value, true) . ")";
    }

    private function equals(Either $e) : bool
    {
        return $this == $e;
    }

    private function concat(Either $e) : Either
    {
        return $this;
    }

    private function map(callable $f) : Either
    {
        return new static($f($this->value));
    }

    private function ap(Either $eitherWithFunction)
    {
        $val = $this->value;
        return $eitherWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    private function chain(callable $f) : Either
    {
        return $f($this->value);
    }

    private function fold($f, $g)
    {
        return $g($this->value);
    }

    private function bimap($f, $g) : Either
    {
        return new static($g($this->value));
    }

    private function alt(Either $e) : Either
    {
        return $this;
    }

    private function reduce($f, $acc)
    {
        return $f($acc, $this->value);
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

    private function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    // Conversions
    public function toMaybe() : Maybe
    {
        return new Just($this->value);
    }

    public function toValidation() : Validation
    {
        return new Success($this->value);
    }
}

function Right()
{
    return curry(function ($x) {
        return new Right($x);
    })(...func_get_args());
}
