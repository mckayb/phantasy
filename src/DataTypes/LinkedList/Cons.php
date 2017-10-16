<?php declare(strict_types=1);

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{concat, curry, identity, map, liftA2};
use Phantasy\DataTypes\Set\Set;
use Phantasy\DataTypes\Collection\Collection;

final class Cons extends LinkedList
{
    use CurryNonPublicMethods;

    private $head = null;
    private $tail = null;

    public function __construct($head, LinkedList $tail)
    {
        $this->head = $head;
        $this->tail = $tail;
    }

    public function __toString() : string
    {
        return "Cons(" . $this->head . ", " . $this->tail . ")";
    }

    protected function equals(LinkedList $l) : bool
    {
        return $this == $l;
    }

    protected function map(callable $f) : LinkedList
    {
        return new static($f($this->head), $this->tail->map($f));
    }

    protected function ap(LinkedList $c) : LinkedList
    {
        return $c->map(function ($fn) {
            return $this->map($fn);
        })->join();
    }

    protected function chain(callable $f) : LinkedList
    {
        return $this->map($f)->join();
    }

    protected function concat(LinkedList $c) : LinkedList
    {
        return new static($this->head, $this->tail->concat($c));
    }

    protected function reduce(callable $f, $acc)
    {
        return $this->tail->reduce($f, $f($acc, $this->head));
    }

    public function join() : LinkedList
    {
        return $this->head->concat($this->tail->join());
    }

    protected function traverse(string $className, callable $f)
    {
        if (!class_exists($className) || !is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an \'of\' method).'
            );
        }

        return $this->reduce(function ($ys, $x) use ($f) {
            return liftA2(curry(function ($a, $b) {
                return $b->concat(Cons($a, Nil()));
            }), $f($x), $ys);
        }, call_user_func([$className, 'of'], new Nil()));
    }

    protected function sequence(string $className)
    {
        return $this->traverse($className, identity());
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
        return $this->head;
    }

    public function tail() : LinkedList
    {
        return $this->tail;
    }

    public function toSet() : Set
    {
        return new Set(...$this->toArray());
    }

    public function toCollection() : Collection
    {
        return new Collection(...$this->toArray());
    }

    public function toArray() : array
    {
        return $this->reduce(function ($prev, $curr) {
            return concat($prev, [$curr]);
        }, []);
    }
}

function Cons(...$args)
{
    return curry(function ($head, $tail) {
        return new Cons($head, $tail);
    })(...$args);
}
