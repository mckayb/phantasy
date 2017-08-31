<?php

namespace Phantasy\DataTypes\Set;

use function Phantasy\Core\{concat, map, identity};

class Set
{
    private $xs = [];

    public static function fromArray(array $a) : Set
    {
        return new Set(...$a);
    }

    public static function fromList($l) : Set
    {
        return $l->reduce(function ($prev, $curr) {
            return $prev->concat(new Set($curr));
        }, new Set());
    }

    public static function empty() : Set
    {
        return new Set();
    }

    public static function of($x) : Set
    {
        return new Set($x);
    }

    public function __construct(...$xs)
    {
        $this->xs = array_values(array_unique($xs));
    }

    public function map(callable $f) : Set
    {
        return new Set(map($f, $this->xs));
    }

    public function ap(Set $s) : Set
    {
        return $s->map(function ($fn) {
            return $this->map($fn);
        })->join();
    }

    public function chain(callable $f) : Set
    {
        return $this->map($f)->join();
    }

    public function reduce(callable $f, $acc)
    {
        return array_reduce($this->xs, $f, $acc);
    }

    public function traverse(callable $of, callable $f)
    {
        return $this->reduce(function ($ys, $x) use ($f) {
            return $ys->ap($f($x)->map(curry(function ($a, $b) {
                return $b->concat(Set::of($a));
            })));
        }, $of(Set::empty()));
    }

    public function sequence(callable $of)
    {
        return $this->traverse($of, identity());
    }

    public function concat(Set $s) : Set
    {
        return new Set(...array_merge($this->xs, $s->toArray()));
    }

    public function equals(Set $s)
    {
        // Need equals regardless of order
        return $s->toArray() == $this->xs;
    }

    public function join() : Set
    {
        return $this->reduce(function ($prev, $curr) {
            return $prev->concat($curr);
        }, new Set());
    }

    public function bind(callable $f) : Set
    {
        return $this->chain($f);
    }

    public function flatMap(callable $f) : Set
    {
        return $this->chain($f);
    }

    public function union(Set $s) : Set
    {
        return $this->concat($s);
    }

    public function intersection(Set $s) : Set
    {
        return $this->reduce(function ($prev, $curr) use ($s) {
            if ($this->contains($curr) && $s->contains($curr)) {
                return $prev->concat($curr);
            }
            return $prev;
        }, new Set());
    }

    public function difference(Set $s) : Set
    {
        return $this->reduce(function ($prev, $curr) use ($s) {
            if ($this->contains($curr) && !$s->contains($curr)) {
                return $prev->concat($curr);
            }
            return $prev;
        }, new Set());
    }

    public function isSubsetOf(Set $s)
    {
        return $this->equals(Set::empty()) || $this->intersection($s)->equals($this);
    }

    public function isProperSubsetOf(Set $s)
    {
        return $this->isSubsetOf($s) && !$this->equals($s);
    }

    public function contains($x)
    {
        return Set::of($x)->isSubsetOf($this);
    }

    public function size(Set $s) : int
    {
        return count($this->xs);
    }

    public function toArray() : array
    {
        return $this->xs;
    }
}

function Set()
{
    return new Set(...func_get_args());
}
