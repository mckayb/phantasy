<?php declare(strict_types=1);

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\identity;

final class Nil extends LinkedList
{
    use CurryNonPublicMethods;

    private function equals(LinkedList $l) : bool
    {
        return $this == $l;
    }

    private function map(callable $f) : LinkedList
    {
        return $this;
    }

    private function ap(LinkedList $c) : LinkedList
    {
        return $this;
    }

    private function chain(callable $f) : LinkedList
    {
        return $this;
    }

    private function concat(LinkedList $c) : LinkedList
    {
        return $c;
    }

    private function reduce(callable $f, $acc)
    {
        return $acc;
    }

    private function join() : LinkedList
    {
        return $this;
    }

    private function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !method_exists($className, 'of')) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an of method).'
            );
        }

        return call_user_func([$className, 'of'], new Nil());
    }

    private function sequence(string $className)
    {
        return $this->traverse($className, identity());
    }

    public function __toString()
    {
        return "Nil()";
    }
}

function Nil() : LinkedList
{
    return new Nil();
}
