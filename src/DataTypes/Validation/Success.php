<?php

namespace Phantasy\DataTypes\Validation;

use Phantasy\DataTypes\Maybe\{Maybe, Just};
use Phantasy\DataTypes\Either\{Either, Right};

final class Success extends Validation
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Success(" . var_export($this->value, true) . ")";
    }

    public function map(callable $f) : Validation
    {
        return new Success($f($this->value));
    }

    public function ap(Validation $validationWithFunc) : Validation
    {
        $val = $this->value;
        return $validationWithFunc->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    public function concat(Validation $v) : Validation
    {
        return $v;
    }

    public function fold(callable $f, callable $g)
    {
        return $g($this->value);
    }

    public function bimap(callable $f, callable $g) : Validation
    {
        return new Success($g($this->value));
    }

    public function alt(Validation $v) : Validation
    {
        return $this;
    }

    public function reduce(callable $f, $acc)
    {
        return $f($acc, $this->value);
    }

    // Aliases
    public function cata(callable $f, callable $g)
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

function Success($x) : Validation
{
    return new Success($x);
}
