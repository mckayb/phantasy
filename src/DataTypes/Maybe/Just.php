<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\{Either, Right};
use Phantasy\DataTypes\Validation\{Validation, Success};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{curry, concat, map, identity};

final class Just extends Maybe
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString() : string
    {
        return "Just(" . var_export($this->value, true) . ")";
    }

    protected function equals(Maybe $m) : bool
    {
        return $this == $m;
    }

    protected function concat(Maybe $m) : Maybe
    {
        return $m instanceof Just
            ? new static(concat($this->value, $m->getOrElse(null)))
            : $this;
    }

    protected function map(callable $f) : Maybe
    {
        return new static($f($this->value));
    }

    protected function ap(Maybe $maybeWithFunction) : Maybe
    {
        $val = $this->value;
        return $maybeWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    protected function chain(callable $f) : Maybe
    {
        return $f($this->value);
    }

    protected function extend(callable $f) : Maybe
    {
        return new static($f($this));
    }

    protected function alt(Maybe $m) : Maybe
    {
        return $this;
    }

    protected function reduce(callable $f, $acc)
    {
        return $f($acc, $this->value);
    }

    protected function traverse(callable $of, callable $f)
    {
        return map(function ($x) {
            return new static($x);
        }, $f($this->value));
    }

    protected function sequence(callable $of)
    {
        return $this->traverse($of, identity());
    }

    protected function getOrElse($d)
    {
        return $this->value;
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
        return $g($this->value);
    }

    protected function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    // Transformations
    protected function toEither($val) : Either
    {
        return new Right($this->value);
    }

    protected function toValidation($val) : Validation
    {
        return new Success($this->value);
    }
}

function Just(...$args)
{
    return curry(function ($x) {
        return new Just($x);
    })(...$args);
}
