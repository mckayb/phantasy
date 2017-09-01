<?php

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\Right;
use Phantasy\DataTypes\Validation\Success;

final class Just extends Maybe
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Just(" . var_export($this->value, true) . ")";
    }

    public function map(callable $f)
    {
        return new Just($f($this->value));
    }

    public function ap($maybeWithFunction)
    {
        $val = $this->value;
        return $maybeWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    public function chain(callable $f)
    {
        return $f($this->value);
    }

    public function alt($maybe)
    {
        return $this;
    }

    public function reduce(callable $f, $acc)
    {
        return $f($acc, $this->value);
    }

    public function getOrElse($d)
    {
        return $this->value;
    }

    // Aliases
    public function bind(callable $f)
    {
        return $this->chain($f);
    }

    public function flatMap(callable $f)
    {
        return $this->chain($f);
    }

    public function fold($d)
    {
        return $this->getOrElse($d);
    }

    // Transformations
    public function toEither($val) : Right
    {
        return new Right($this->value);
    }

    public function toValidation($val) : Success
    {
        return new Success($this->value);
    }
}

function Just($x)
{
    return new Just($x);
}
