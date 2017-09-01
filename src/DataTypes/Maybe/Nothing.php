<?php

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\Left;
use Phantasy\DataTypes\Validation\Failure;

final class Nothing extends Maybe
{
    public function __toString()
    {
        return "Nothing()";
    }

    public function map(callable $f) : Maybe
    {
        return $this;
    }

    public function ap(Maybe $maybeWithFunction) : Maybe
    {
        return $this;
    }

    public function chain(callable $f) : Maybe
    {
        return $this;
    }

    public function alt(Maybe $m) : Maybe
    {
        return $m;
    }

    public function reduce(callable $f, $acc)
    {
        return $acc;
    }

    public function getOrElse($d)
    {
        return $d;
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

    // Conversions
    public function toEither($val) : Left
    {
        return new Left($val);
    }

    public function toValidation($val) : Failure
    {
        return new Failure($val);
    }
}

function Nothing()
{
    return new Nothing();
}
