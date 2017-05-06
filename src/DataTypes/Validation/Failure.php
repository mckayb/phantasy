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

    public function map($f)
    {
        return $this;
    }

    public function ap($v)
    {
        return $this;
    }

    public function concat($v)
    {
        if ($v instanceof Success) {
            return $this;
        } else {
            return new Failure(concat($this->value, $v->value));
        }
    }

    public function fold($f, $g)
    {
        return $f($this->value);
    }

    public function bimap($f, $g)
    {
        return new Failure($f($this->value));
    }

    public function alt($v)
    {
        return $v;
    }

    public function reduce($f, $acc)
    {
        return $acc;
    }

    // Aliases
    public function cata($f, $g)
    {
        return $this->fold($f, $g);
    }

    // Transformations
    public function toEither()
    {
        return new Left($this->value);
    }

    public function toMaybe()
    {
        return new Nothing();
    }
}
