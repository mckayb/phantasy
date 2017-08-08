<?php

namespace Phantasy\DataTypes\Collection;

use Phantasy\DataTypes\Set\Set;
use function Phantasy\Core\{map, concat, identity};

class Collection
{
    private $xs = [];

    public static function fromSet(Set $s) : Collection
    {
        return new Collection(...$s->toArray());
    }

    public static function fromArray(array $s) : Collection
    {
        return new Collection(...$s);
    }

    public static function fromLinkedList($l) : Collection
    {
        return $l->reduce(concat(), new Collection());
    }

    public static function empty() : Collection
    {
        return new Collection();
    }

    public function __construct(...$xs)
    {
        $this->xs = $xs;
    }

    public function map(callable $f) : Collection
    {
        return new Collection(map($f, $this->xs));
    }

    public function ap(Collection $c) : Collection
    {
        return $s->map(function ($fn) {
            return $this->map($fn);
        })->join();
    }

    public function chain(Collection $c) : Collection
    {
        return $this->map($f)->join();
    }

    public function concat(Collection $c) : Collection
    {
        return new Collection(array_merge($this->items, $s->toArray()));
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

    public function equals(Collection $c) : Collection
    {
        return $this->xs == $c->toArray();
    }

    public function join() : Collection
    {
         return $this->reduce(function ($prev, $curr) {
            return $prev->concat($curr);
         }, new Collection());
    }

    public function bind(Collection $c) : Collection
    {
        return $this->chain($c);
    }

    public function flatMap(Collection $c) : Collection
    {
        return $this->chain($c);
    }

    public function toArray()
    {
        return $this->xs;
    }
}

function Collection()
{
    return new Collection(...func_get_args());
}
