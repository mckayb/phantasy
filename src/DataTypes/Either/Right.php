<?php

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\Just;

class Right
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function map($f)
    {
        return Either::of($f($this->value));
    }

    public function ap($eitherWithFunction)
    {
        $val = $this->value;
        return $eitherWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    public function chain($f)
    {
        return $f($this->value);
    }

    public function fold($f, $g)
    {
        return $g($this->value);
    }

    public function bimap($f, $g)
    {
        return new Right($g($this->value));
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
        return new Just($this->value);
    }
}
