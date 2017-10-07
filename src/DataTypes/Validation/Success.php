<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Validation;

use Phantasy\DataTypes\Maybe\{Maybe, Just};
use Phantasy\DataTypes\Either\{Either, Right};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{curry, identity, map};

final class Success extends Validation
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString() : string
    {
        return "Success(" . var_export($this->value, true) . ")";
    }

    protected function equals(Validation $v) : bool
    {
        return $this == $v;
    }

    protected function map(callable $f) : Validation
    {
        return new static($f($this->value));
    }

    protected function ap(Validation $validationWithFunc) : Validation
    {
        $val = $this->value;
        return $validationWithFunc->map(
            function ($fn) use ($val) {
                return $fn($val);
            }
        );
    }

    protected function concat(Validation $v) : Validation
    {
        return $v;
    }

    protected function extend(callable $f) : Validation
    {
        return new static($f($this));
    }

    protected function fold(callable $f, callable $g)
    {
        return $g($this->value);
    }

    protected function bimap(callable $f, callable $g) : Validation
    {
        return new static($g($this->value));
    }

    protected function alt(Validation $v) : Validation
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
            return new Success($x);
        }, $f($this->value));
    }

    protected function sequence(string $className)
    {
        return $this->traverse($className, identity());
    }

    // Aliases
    protected function cata(callable $f, callable $g)
    {
        return $this->fold($f, $g);
    }

    public function toEither() : Either
    {
        return new Right($this->value);
    }

    public function toMaybe() : Maybe
    {
        return new Just($this->value);
    }
}

function Success(...$args)
{
    return curry(function ($x) {
        return new Success($x);
    })(...$args);
}
