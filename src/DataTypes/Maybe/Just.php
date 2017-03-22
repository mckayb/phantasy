<?php

namespace PHPFP\DataTypes\Maybe;

use PHPFP\DataTypes\Either\Right;

class Just
{
    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function map($f)
    {
        return Maybe::of($f($this->value));
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

    public function chain($f)
    {
        return $f($this->value);
    }

    public function getOrElse($d)
    {
        return $this->value;
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

    public function fold($d)
    {
        return $this->getOrElse($d);
    }

    // Transformations
    public function toEither()
    {
        return new Right($this->value);
    }
}