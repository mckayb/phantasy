<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\{Either, Left};
use Phantasy\DataTypes\Validation\{Validation, Failure};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\identity;

final class Nothing extends Maybe
{
    use CurryNonPublicMethods;

    public function __toString() : string
    {
        return "Nothing()";
    }

    protected function equals(Maybe $m) : bool
    {
        return $this == $m;
    }

    protected function concat(Maybe $m) : Maybe
    {
        return $m;
    }

    protected function map(callable $f) : Maybe
    {
        return $this;
    }

    protected function ap(Maybe $maybeWithFunction) : Maybe
    {
        return $this;
    }

    protected function select(Maybe $maybeWithEither) : Maybe
    {
        return $this;
    }

    protected function chain(callable $f) : Maybe
    {
        return $this;
    }

    protected function extend(callable $f) : Maybe
    {
        return $this;
    }

    protected function alt(Maybe $m) : Maybe
    {
        return $m;
    }

    protected function reduce(callable $f, $acc)
    {
        return $acc;
    }

    protected function traverse(callable $of, callable $f)
    {
        return call_user_func($of, new static());
    }

    protected function sequence(callable $of)
    {
        return $this->traverse($of, identity());
    }


    protected function getOrElse($d)
    {
        return $d;
    }

    // Aliases
    protected function bind(callable $f) : Maybe
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : Maybe
    {
        return $this->chain($f);
    }

    protected function fold(callable $f, callable $g)
    {
        return $f();
    }

    protected function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    // Conversions
    protected function toEither($val) : Either
    {
        return new Left($val);
    }

    protected function toValidation($val) : Validation
    {
        return new Failure($val);
    }
}

function Nothing()
{
    return new Nothing();
}
