<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Set;

use Phantasy\Traits\CurryNonPublicMethods;
use Phantasy\DataTypes\LinkedList\LinkedList;
use Phantasy\DataTypes\Collection\Collection;
use function Phantasy\Core\{curry, concat, map, identity};

final class Set
{
    use CurryNonPublicMethods;

    private $xs = [];

    protected static function fromArray(array $a) : Set
    {
        return new Set(...$a);
    }

    protected static function fromLinkedList(LinkedList $l) : Set
    {
        return $l->reduce(function ($prev, $curr) {
            return $prev->concat(new Set($curr));
        }, new Set());
    }

    protected static function fromCollection(Collection $c) : Set
    {
        return $c->reduce(function ($prev, $curr) {
            return $prev->concat(new Set($curr));
        }, new Set());
    }

    public static function empty() : Set
    {
        return new Set();
    }

    public static function zero() : Set
    {
        return new Set();
    }

    private static function of($x) : Set
    {
        return new Set($x);
    }

    public function __construct(...$xs)
    {
        $this->xs = array_values(array_unique($xs, SORT_REGULAR));
    }

    protected function map(callable $f) : Set
    {
        return new Set(...map($f, $this->xs));
    }

    protected function ap(Set $s) : Set
    {
        return $s->map(function ($fn) {
            return $this->map($fn);
        })->join();
    }

    protected function chain(callable $f) : Set
    {
        return $this->map($f)->join();
    }

    protected function reduce(callable $f, $acc)
    {
        return array_reduce($this->xs, $f, $acc);
    }

    protected function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an \'of\' method).'
            );
        }

        return $this->reduce(function ($ys, $x) use ($f) {
            return $ys->ap($f($x)->map(curry(function ($a, $b) {
                return $b->concat(Set::of($a));
            })));
        }, call_user_func([$className, 'of'], Set::empty()));
    }

    protected function sequence(string $className)
    {
        return $this->traverse($className, identity());
    }

    protected function concat(Set $s) : Set
    {
        return new Set(...array_merge($this->xs, $s->toArray()));
    }

    protected function alt(Set $s) : Set
    {
        return $this->concat($s);
    }

    protected function equals(Set $s)
    {
        return empty(array_diff($s->toArray(), $this->xs))
            && empty(array_diff($this->xs, $s->toArray()));
    }

    public function join() : Set
    {
        return $this->reduce(function ($prev, $curr) {
            return $prev->concat($curr);
        }, new Set());
    }

    protected function bind(callable $f) : Set
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : Set
    {
        return $this->chain($f);
    }

    protected function union(Set $s) : Set
    {
        return $this->concat($s);
    }

    protected function intersect(Set $s) : Set
    {
        return $this->reduce(function ($prev, $curr) use ($s) {
            if ($this->contains($curr) && $s->contains($curr)) {
                return $prev->concat(new Set($curr));
            }
            return $prev;
        }, new Set());
    }

    protected function difference(Set $s) : Set
    {
        return $this->reduce(function ($prev, $curr) use ($s) {
            if ($this->contains($curr) && !$s->contains($curr)) {
                return $prev->concat(new Set($curr));
            }
            return $prev;
        }, new Set());
    }

    protected function isSubsetOf(Set $s) : bool
    {
        return $this->equals(Set::empty()) || $this->intersect($s)->equals($this);
    }

    protected function isProperSubsetOf(Set $s) : bool
    {
        return $this->isSubsetOf($s) && !$this->equals($s);
    }

    protected function contains($x) : bool
    {
        return in_array($x, $this->xs);
    }

    public function cardinality() : int
    {
        return count($this->xs);
    }

    public function size() : int
    {
        return $this->cardinality();
    }

    public function count() : int
    {
        return $this->cardinality();
    }

    public function toArray() : array
    {
        return $this->xs;
    }

    public function toLinkedList() : LinkedList
    {
        return LinkedList::fromArray($this->xs);
    }

    public function toCollection() : Collection
    {
        return Collection::fromArray($this->xs);
    }

    public function __toString() : string
    {
        $vals = implode(',', array_map(function ($x) {
            return var_export($x, true);
        }, $this->xs));
        return "Set(" . $vals . ")";
    }
}

function Set(...$args)
{
    return new Set(...$args);
}
