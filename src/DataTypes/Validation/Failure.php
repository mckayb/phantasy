<?php

namespace Phantasy\DataTypes\Validation;

use Phantasy\DataTypes\Either\{Either, Left};
use Phantasy\DataTypes\Maybe\{Maybe, Nothing};
use function Phantasy\Core\concat;

final class Failure extends Validation
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

    public function map(callable $f) : Validation
    {
        return $this;
    }

    public function ap(Validation $v) : Validation
    {
        return $this;
    }

    public function concat(Validation $v) : Validation
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

    public function bimap(callable $f, callable $g) : Validation
    {
        return new Failure($f($this->value));
    }

    public function alt(Validation $v) : Validation
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
    public function toEither() : Either
    {
        return new Left($this->value);
    }

    public function toMaybe() : Maybe
    {
        return new Nothing();
    }
}

function Failure($x) : Validation
{
    return new Failure($x);
}
