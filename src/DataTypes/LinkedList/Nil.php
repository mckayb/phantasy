<?php declare(strict_types=1);

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\identity;

final class Nil extends LinkedList
{
    use CurryNonPublicMethods;

    public function __toString() : string
    {
        return "Nil()";
    }

    protected function equals(LinkedList $l) : bool
    {
        return $this == $l;
    }

    protected function map(callable $f) : LinkedList
    {
        return $this;
    }

    protected function ap(LinkedList $c) : LinkedList
    {
        return $this;
    }

    protected function chain(callable $f) : LinkedList
    {
        return $this;
    }

    protected function concat(LinkedList $c) : LinkedList
    {
        return $c;
    }

    protected function reduce(callable $f, $acc)
    {
        return $acc;
    }

    public function join() : LinkedList
    {
        return $this;
    }

    protected function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an of method).'
            );
        }

        return call_user_func([$className, 'of'], new Nil());
    }

    protected function sequence(string $className)
    {
        return $this->traverse($className, identity());
    }

    protected function bind(callable $f) : LinkedList
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : LinkedList
    {
        return $this->chain($f);
    }
}

function Nil() : LinkedList
{
    return new Nil();
}
