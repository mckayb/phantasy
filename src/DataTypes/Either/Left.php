<?php

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\Nothing;

class Left
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

    public function map($f)
    {
        return $this;
    }

    public function ap($eitherWithFunction)
    {
        return $this;
    }

    public function chain($f)
    {
        return $this;
    }

    public function fold($f, $g)
    {
        return $f($this->value);
    }

    public function bimap($f, $g)
    {
        return new Left($f($this->value));
    }

    // Aliases
    public function bind($f)
    {
        return $this->chain($f);
    }

    public function flatMap($f)
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
}
