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

    public function __toString()
    {
        return "Just(" . var_export($this->value, true) . ")";
    }

    private function equals(Maybe $m) : bool
    {
        return $this == $m;
    }

    private function concat(Maybe $m) : Maybe
    {
        return $m instanceof Just
            ? new static(concat($this->value, $m->getOrElse(null)))
            : $this;
    }

    private function map(callable $f) : Maybe
    {
        return new static($f($this->value));
    }

    private function ap(Maybe $maybeWithFunction) : Maybe
    {
        $val = $this->value;
        return $maybeWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    private function chain(callable $f) : Maybe
    {
        return $f($this->value);
    }

    private function extend(callable $f) : Maybe
    {
        return new static($f($this));
    }

    private function alt(Maybe $m) : Maybe
    {
        return $this;
    }

    private function reduce(callable $f, $acc)
    {
        return $f($acc, $this->value);
    }

    private function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an \'of\' method).'
            );
        }

        return map(function ($x) {
            return new static($x);
        }, $f($this->value));
    }

    private function sequence(string $className)
    {
        return $this->traverse($className, identity());
    }

    private function getOrElse($d)
    {
        return $this->value;
    }

    // Aliases
    private function bind(callable $f) : Maybe
    {
        return $this->chain($f);
    }

    private function flatMap(callable $f) : Maybe
    {
        return $this->chain($f);
    }

    private function fold($d)
    {
        return $this->getOrElse($d);
    }

    // Transformations
    private function toEither($val) : Either
    {
        return new Right($this->value);
    }

    private function toValidation($val) : Validation
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
