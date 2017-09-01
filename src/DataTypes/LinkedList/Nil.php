<?php

namespace Phantasy\DataTypes\LinkedList;

final class Nil extends LinkedList
{
    public function map(callable $f) : LinkedList
    {
        return new Nil();
    }

    public function ap(LinkedList $c) : LinkedList
    {
        return new Nil();
    }

    public function chain(callable $f) : LinkedList
    {
        return new Nil();
    }

    public function concat(LinkedList $c) : LinkedList
    {
        return $c;
    }

    public function reduce(callable $f, $acc)
    {
        return $acc;
    }

    public function join() : LinkedList
    {
        return new Nil();
    }

    public function traverse(callable $of, callable $f)
    {
        return $of(new Nil());
    }

    public function sequence(callable $of)
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
