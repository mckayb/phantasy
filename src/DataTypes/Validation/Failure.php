<?php

namespace PHPFP\DataTypes\Validation;

use PHPFP\DataTypes\Either\Left;
use PHPFP\DataTypes\Maybe\Nothing;
use function PHPFP\Core\semigroupConcat;

class Failure
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function map($f)
    {
        return $this;
    }

    public function ap($v)
    {
        return $v instanceof Success
            ? $this
            : new Failure(semigroupConcat($this->value, $v->value));
    }

    public function concat($v) {
        return $this->ap($v);
    }

    public function fold($f, $g)
    {
        return $f($this->value);
    }

    public function bimap($f, $g)
    {
        return new Failure($f($this->value));
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
