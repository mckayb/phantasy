<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Validation;

use Phantasy\Traits\CurryNonPublicMethods;
use Phantasy\DataTypes\Either\{Either, Left};
use Phantasy\DataTypes\Maybe\{Maybe, Nothing};
use function Phantasy\Core\{concat, curry, identity};

final class Failure extends Validation
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($val)
    {
        $this->value = $val;
    }

    public function __toString() : string
    {
        return "Failure(" . var_export($this->value, true) . ")";
    }

    protected function equals(Validation $v) : bool
    {
        return $this == $v;
    }

    protected function map(callable $f) : Validation
    {
        return $this;
    }

    protected function ap(Validation $v) : Validation
    {
        return $this;
    }

    protected function concat(Validation $v) : Validation
    {
        if ($v instanceof Success) {
            return $this;
        } else {
            return new static(concat($this->value, $v->value));
        }
    }

    protected function fold(callable $f, callable $g)
    {
        return $f($this->value);
    }

    protected function bimap(callable $f, callable $g) : Validation
    {
        return new static($f($this->value));
    }

    protected function alt(Validation $v) : Validation
    {
        return $v;
    }

    protected function reduce(callable $f, $acc)
    {
        return $acc;
    }

    protected function extend(callable $f) : Validation
    {
        return $this;
    }

    protected function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an \'of\' method).'
            );
        }

        return call_user_func([$className, 'of'], new static($this->value));
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

function Failure(...$args)
{
    return curry(function ($x) {
        return new Failure($x);
    })(...$args);
}
