<?php

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\Traits\CurryNonPublicMethods;

final class Nil extends LinkedList
{
    use CurryNonPublicMethods;

    private function equals(LinkedList $l) : bool
    {
        return $this == $l;
    }

    private function map(callable $f) : LinkedList
    {
        return new Nil();
    }

    private function ap(LinkedList $c) : LinkedList
    {
        return new Nil();
    }

    private function chain(callable $f) : LinkedList
    {
        return new Nil();
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
        return new Nil();
    }

    private function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !method_exists($className, 'of')) {
            throw new InvalidArgumentException('Method must be a class name of an Applicative (must have an of method).');
        }

        return $className::of(new Nil());
    }

    private function sequence(string $className)
    {
        if (!class_exists($className) || !method_exists($className, 'of')) {
            throw new InvalidArgumentException('Method must be a class name of an Applicative (must have an of method).');
        }

        return $className::of(new Nil());
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
