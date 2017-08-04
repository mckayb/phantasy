<?php

namespace Phantasy\DataTypes\LinkedList;

use function Phantasy\Core\{concat, curry, identity, map};

class Cons
{
    private $head = null;
    private $tail = null;

    public function __construct($head, $tail)
    {
        $this->head = $head;
        $this->tail = $tail;
    }

    public function map(callable $f) : Cons
    {
        return new static($f($this->head), $this->tail->map($f));
    }

    public function ap($c)
    {
        return $c->map(function ($fn) {
            return $this->map($fn);
        })->join();
    }

    public function chain(callable $f)
    {
        return $this->map($f)->join();
    }

    public function concat($c) : Cons
    {
        return new static($this->head, $this->tail->concat($c));
    }

    public function reduce(callable $f, $acc)
    {
        return $this->tail->reduce($f, $f($acc, $this->head));
    }

    public function join() : Cons
    {
        return $this->head->concat($this->tail->join());
    }

    public function traverse(callable $of, callable $f)
    {
        return $this->reduce(function ($ys, $x) use ($f) {
            return $ys->ap($f($x)->map(curry(function ($a, $b) {
                return $b->concat(new Cons($a, new Nil()));
            })));
        }, $of(new Nil()));
    }

    public function sequence(callable $of)
    {
        return $this->traverse($of, identity());
    }

    public function __toString()
    {
        return "Cons(" . $this->head . ", " . $this->tail . ")";
    }
}
