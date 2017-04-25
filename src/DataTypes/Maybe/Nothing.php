<?php

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\Left;

class Nothing
{
    public function __toString()
    {
        return "Nothing()";
    }

    public function map($f)
    {
        return $this;
    }

    public function ap($maybeWithFunction)
    {
        return $this;
    }

    public function chain($f)
    {
        return $this;
    }

    public function getOrElse($d)
    {
        return $d;
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

    // Conversions
    public function toEither($val)
    {
        return new Left($val);
    }
}
