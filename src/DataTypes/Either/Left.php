<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\{Maybe, Nothing};
use Phantasy\DataTypes\Validation\{Validation, Failure};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{curry, identity};

final class Left extends Either
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString() : string
    {
        return "Left(" . var_export($this->value, true) . ")";
    }

    protected function equals(Either $e) : bool
    {
        return $this == $e;
    }

    protected function concat(Either $e) : Either
    {
        return $e;
    }

    protected function map(callable $f) : Either
    {
        return $this;
    }

    protected function ap(Either $eitherWithFunction) : Either
    {
        return $this;
    }

    protected function chain(callable $f) : Either
    {
        return $this;
    }

    protected function extend(callable $f) : Either
    {
        return $this;
    }

    protected function fold(callable $f, callable $g)
    {
        return $f($this->value);
    }

    protected function bimap(callable $f, callable $g) : Either
    {
        return new static($f($this->value));
    }

    protected function alt(Either $e) : Either
    {
        return $e;
    }

    protected function reduce(callable $f, $acc)
    {
        return $acc;
    }

    protected function traverse(callable $of, callable $f)
    {
        return call_user_func($of, new static($this->value));
    }

    protected function sequence(callable $of)
    {
        return $this->traverse($of, identity());
    }

    // Aliases
    protected function bind(callable $f) : Either
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : Either
    {
        return $this->chain($f);
    }

    protected function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    // Conversions
    public function toMaybe() : Maybe
    {
        return new Nothing();
    }

    public function toValidation() : Validation
    {
        return new Failure($this->value);
    }
}

function Left(...$args)
{
    return curry(function ($x) {
        return new Left($x);
    })(...$args);
}
