<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\{Maybe, Just};
use Phantasy\DataTypes\Validation\{Validation, Success};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{curry, map, identity};

final class Right extends Either
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString() : string
    {
        return "Right(" . var_export($this->value, true) . ")";
    }

    protected function equals(Either $e) : bool
    {
        return $this == $e;
    }

    protected function concat(Either $e) : Either
    {
        return $this;
    }

    protected function map(callable $f) : Either
    {
        return new static($f($this->value));
    }

    protected function ap(Either $eitherWithFunction) : Either
    {
        $val = $this->value;
        return $eitherWithFunction->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    protected function chain(callable $f) : Either
    {
        return $f($this->value);
    }

    protected function extend(callable $f) : Either
    {
        return new static($f($this));
    }

    protected function fold(callable $f, callable $g)
    {
        return $g($this->value);
    }

    protected function bimap(callable $f, callable $g) : Either
    {
        return new static($g($this->value));
    }

    protected function alt(Either $e) : Either
    {
        return $this;
    }

    protected function reduce(callable $f, $acc)
    {
        return $f($acc, $this->value);
    }

    protected function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an \'of\' method).'
            );
        }

        return map(function ($x) {
            return new Right($x);
        }, $f($this->value));
    }

    protected function sequence(string $className)
    {
        return $this->traverse($className, identity());
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
        return new Just($this->value);
    }

    public function toValidation() : Validation
    {
        return new Success($this->value);
    }
}

function Right(...$args)
{
    return curry(function ($x) {
        return new Right($x);
    })(...$args);
}
