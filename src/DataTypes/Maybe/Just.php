<?php

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\{Either, Right};
use Phantasy\DataTypes\Validation\{Validation, Success};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\curry;

final class Just extends Maybe
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Just(" . var_export($this->value, true) . ")";
    }

    private function equals(Maybe $m) : bool
    {
        return $this == $m;
    }

    private function map(callable $f) : Maybe
    {
        return new Just($f($this->value));
    }

    private function ap(Maybe $maybeWithFunction) : Maybe
    {
        $val = $this->value;
        return $maybeWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    private function chain(callable $f) : Maybe
    {
        return $f($this->value);
    }

    private function alt(Maybe $m) : Maybe
    {
        return $this;
    }

    private function reduce(callable $f, $acc)
    {
        return $f($acc, $this->value);
    }

    private function getOrElse($d)
    {
        return $this->value;
    }

    // Aliases
    private function bind(callable $f) : Maybe
    {
        return $this->chain($f);
    }

    private function flatMap(callable $f) : Maybe
    {
        return $this->chain($f);
    }

    private function fold($d)
    {
        return $this->getOrElse($d);
    }

    // Transformations
    private function toEither($val) : Either
    {
        return new Right($this->value);
    }

    private function toValidation($val) : Validation
    {
        return new Success($this->value);
    }
}

function Just()
{
    return curry(function ($x) {
        return new Just($x);
    })(...func_get_args());
}
