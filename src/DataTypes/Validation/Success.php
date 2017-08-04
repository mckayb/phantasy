<?php

namespace Phantasy\DataTypes\Validation;

use Phantasy\DataTypes\Maybe\Just;
use Phantasy\DataTypes\Either\Right;

class Success
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

    public function map(callable $f) : Success
    {
        return new Success($f($this->value));
    }

    public function ap($validationWithFunc)
    {
        $val = $this->value;
        return $validationWithFunc->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    public function concat($validation)
    {
        return $validation;
    }

    public function fold(callable $f, callable $g)
    {
        return $g($this->value);
    }

    public function bimap(callable $f, callable $g) : Success
    {
        return new Success($g($this->value));
    }

    public function alt($v) : Success
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

    public function toEither() : Right
    {
        return new Right($this->value);
    }

    public function toMaybe() : Just
    {
        return new Just($this->value);
    }
}

function Success($x)
{
    return new Success($x);
}
