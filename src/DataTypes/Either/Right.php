<?php

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\Just;
use Phantasy\DataTypes\Validation\Success;

final class Right extends Either
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Right(" . var_export($this->value, true) . ")";
    }

    public function map(callable $f) : Either
    {
        return new Right($f($this->value));
    }

    public function ap(Either $eitherWithFunction)
    {
        $val = $this->value;
        return $eitherWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    public function chain(callable $f) : Either
    {
        return $f($this->value);
    }

    public function fold($f, $g)
    {
        return $g($this->value);
    }

    public function bimap($f, $g) : Either
    {
        return new Right($g($this->value));
    }

    public function alt(Either $e) : Either
    {
        return $this;
    }

    public function reduce($f, $acc)
    {
        return $f($acc, $this->value);
    }

    // Aliases
    public function bind(callable $f) : Either
    {
        return $this->chain($f);
    }

    public function flatMap(callable $f) : Either
    {
        return $this->chain($f);
    }

    public function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    // Conversions
    public function toMaybe()
    {
        return new Just($this->value);
    }

    public function toValidation()
    {
        return new Success($this->value);
    }
}

function Right($x)
{
    return new Right($x);
}
