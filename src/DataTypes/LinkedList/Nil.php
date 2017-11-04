<?php declare(strict_types=1);

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\identity;
use Phantasy\DataTypes\Set\Set;
use Phantasy\DataTypes\Collection\Collection;

final class Nil extends LinkedList
{
    use CurryNonPublicMethods;

    public function __toString() : string
    {
        return "Nil()";
    }

    protected function equals(LinkedList $l) : bool
    {
        return $this == $l;
    }

    protected function map(callable $f) : LinkedList
    {
        return $this;
    }

    protected function ap(LinkedList $c) : LinkedList
    {
        return $this;
    }

    protected function chain(callable $f) : LinkedList
    {
        return $this;
    }

    protected function concat(LinkedList $c) : LinkedList
    {
        return $c;
    }

    protected function alt(LinkedList $l) : LinkedList
    {
        return $l;
    }

    protected function reduce(callable $f, $acc)
    {
        return $acc;
    }

    public function join() : LinkedList
    {
        return $this;
    }

    protected function traverse(callable $of, callable $f)
    {
        return call_user_func($of, new Nil());
    }

    protected function sequence(callable $of)
    {
        return $this->traverse($of, identity());
    }

    protected function bind(callable $f) : LinkedList
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : LinkedList
    {
        return $this->chain($f);
    }

    public function head()
    {
        return null;
    }

    public function tail() : LinkedList
    {
        return $this;
    }

    public function toSet() : Set
    {
        return new Set();
    }

    public function toCollection() : Collection
    {
        return new Collection();
    }

    public function toArray() : array
    {
        return [];
    }
}

function Nil() : LinkedList
{
    return new Nil();
}
