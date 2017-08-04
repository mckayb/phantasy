<?php

namespace Phantasy\DataTypes\LinkedList;

class Nil
{
    public function map(callable $f) : Nil
    {
        return new Nil();
    }

    public function ap($c) : Nil
    {
        return new Nil();
    }

    public function chain(callable $f) : Nil
    {
        return new Nil();
    }

    public function concat($c)
    {
        return $c;
    }

    public function reduce(callable $f, $acc)
    {
        return $acc;
    }

    public function join() : Nil
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
