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

    public function map($f)
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

    public function fold($f, $g)
    {
        return $g($this->value);
    }

    public function bimap($f, $g)
    {
        return new Success($g($this->value));
    }

    // Aliases
    public function cata($f, $g)
    {
        return $this->fold($f, $g);
    }

    public function toEither()
    {
        return new Right($this->value);
    }

    public function toMaybe()
    {
        return new Just($this->value);
    }
}
