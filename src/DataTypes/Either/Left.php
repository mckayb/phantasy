<?php

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\Nothing;
use Phantasy\DataTypes\Validation\Failure;

final class Left extends Either
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Left(" . var_export($this->value, true) . ")";
    }

    public function map(callable $f) : Either
    {
        return $this;
    }

    public function ap(Either $eitherWithFunction) : Either
    {
        return $this;
    }

    public function chain(callable $f) : Either
    {
        return $this;
    }

    public function fold(callable $f, callable $g)
    {
        return $f($this->value);
    }

    public function bimap(callable $f, callable $g) : Either
    {
        return new Left($f($this->value));
    }

    public function alt(Either $e) : Either
    {
        return $e;
    }

    public function reduce(callable $f, $acc)
    {
        return $acc;
    }

    // Aliases
    public function bind(callable $f) : Left
    {
        return $this->chain($f);
    }

    public function flatMap(callable $f) : Left
    {
        return $this->chain($f);
    }

    public function cata($f, $g)
    {
        return $this->fold($f, $g);
    }

    // Conversions
    public function toMaybe()
    {
        return new Nothing();
    }

    public function toValidation()
    {
        return new Failure($this->value);
    }
}

function Left($x)
{
    return new Left($x);
}
