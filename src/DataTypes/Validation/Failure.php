<?php

namespace Phantasy\DataTypes\Validation;

use Phantasy\DataTypes\Either\Left;
use Phantasy\DataTypes\Maybe\Nothing;
use function Phantasy\Core\concat;

class Failure
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString()
    {
        return "Failure(" . var_export($this->value, true) . ")";
    }

    public function map(callable $f) : Failure
    {
        return $this;
    }

    public function ap($v) : Failure
    {
        return $this;
    }

    public function concat($v) : Failure
    {
        if ($v instanceof Success) {
            return $this;
        } else {
            return new Failure(concat($this->value, $v->value));
        }
    }

    public function fold(callable $f, callable $g)
    {
        return $f($this->value);
    }

    public function bimap(callable $f, callable $g) : Failure
    {
        return new Failure($f($this->value));
    }

    public function alt($v)
    {
        return $v;
    }

    public function reduce(callable $f, $acc)
    {
        return $acc;
    }

    // Aliases
    public function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    // Transformations
    public function toEither() : Left
    {
        return new Left($this->value);
    }

    public function toMaybe() : Nothing
    {
        return new Nothing();
    }
}
