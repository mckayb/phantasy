<?php

namespace Phantasy\DataTypes\LinkedList;

class Nil
{
    public function map(callable $f) : Nil
    {
        return new static();
    }

    public function ap($c) : Nil
    {
        return new static();
    }

    public function chain(callable $f) : Nil
    {
        return new static();
    }

    public function concat($c)
    {
        return $c;
    }

    public function reduce($f, $acc)
    {
        return $acc;
    }

    public function join() : Nil
    {
        return new static();
    }
}
