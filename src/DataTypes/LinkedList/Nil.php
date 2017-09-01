<?php

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\Traits\CurryNonPublicMethods;

final class Nil extends LinkedList
{
    use CurryNonPublicMethods;

    private function equals(LinkedList $l) : bool
    {
        return $this === $l;
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

    private function traverse(callable $of, callable $f)
    {
        return $of(new Nil());
    }

    private function sequence(callable $of)
    {
        return $of(new Nil());
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
