<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\{Either, Left};
use Phantasy\DataTypes\Validation\{Validation, Failure};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\identity;

final class Nothing extends Maybe
{
    use CurryNonPublicMethods;

    public function __toString()
    {
        return "Nothing()";
    }

    private function equals(Maybe $m) : bool
    {
        return $this == $m;
    }

    private function concat(Maybe $m) : Maybe
    {
        return $m;
    }

    private function map(callable $f) : Maybe
    {
        return $this;
    }

    private function ap(Maybe $maybeWithFunction) : Maybe
    {
        return $this;
    }

    private function chain(callable $f) : Maybe
    {
        return $this;
    }

    private function extend(callable $f) : Maybe
    {
        return $this;
    }

    private function alt(Maybe $m) : Maybe
    {
        return $m;
    }

    private function reduce(callable $f, $acc)
    {
        return $acc;
    }

    private function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !method_exists($className, 'of')) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an \'of\' method).'
            );
        }

        return call_user_func([$className, 'of'], new static());
    }

    private function sequence(string $className)
    {
        return $this->traverse($className, identity());
    }


    private function getOrElse($d)
    {
        return $d;
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

    // Conversions
    private function toEither($val) : Either
    {
        return new Left($val);
    }

    private function toValidation($val) : Validation
    {
        return new Failure($val);
    }
}

function Nothing()
{
    return new Nothing();
}
