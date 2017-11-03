<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Collection;

use Phantasy\Traits\CurryNonPublicMethods;
use Phantasy\DataTypes\Set\Set;
use Phantasy\DataTypes\LinkedList\LinkedList;
use function Phantasy\Core\{curry, map, concat, identity, head, tail};

final class Collection
{
    use CurryNonPublicMethods;

    private $xs = [];

    protected static function fromSet(Set $s) : Collection
    {
        return new Collection(...$s->toArray());
    }

    protected static function fromArray(array $s) : Collection
    {
        return new Collection(...$s);
    }

    protected static function fromLinkedList(LinkedList $l) : Collection
    {
        return $l->reduce(function ($prev, $curr) {
            return $prev->concat(Collection::of($curr));
        }, new Collection());
    }

    protected static function of($x) : Collection
    {
        return new Collection($x);
    }

    public static function empty() : Collection
    {
        return new Collection();
    }

    public static function zero() : Collection
    {
        return new Collection();
    }

    public function __construct(...$xs)
    {
        $this->xs = $xs;
    }

    protected function map(callable $f) : Collection
    {
        return new Collection(...map($f, $this->xs));
    }

    protected function ap(Collection $c) : Collection
    {
        return $c->map(function ($fn) {
            return $this->map($fn);
        })->join();
    }

    protected function chain(callable $f) : Collection
    {
        return $this->map($f)->join();
    }

    protected function concat(Collection $c) : Collection
    {
        return new Collection(...array_merge($this->xs, $c->toArray()));
    }

    protected function alt(Collection $c) : Collection
    {
        return $this->concat($c);
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
                return $b->concat(Collection::of($a));
            })));
        }, call_user_func([$className, 'of'], Collection::empty()));
    }

    protected function sequence(string $className)
    {
        return $this->traverse($className, identity());
    }

    protected function equals(Collection $c) : bool
    {
        return $this->xs == $c->toArray();
    }

    protected function join() : Collection
    {
         return $this->reduce(concat(), new Collection());
    }

    protected function bind(callable $f) : Collection
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : Collection
    {
        return $this->chain($f);
    }

    public function toArray() : array
    {
        return $this->xs;
    }

    public function toSet() : Set
    {
        return Set::fromArray($this->xs);
    }

    public function toLinkedList() : LinkedList
    {
        return LinkedList::fromArray($this->xs);
    }

    public function head()
    {
        return head($this->xs);
    }

    public function tail() : Collection
    {
        return new Collection(...tail($this->xs));
    }

    public function __toString() : string
    {
        $vals = implode(',', array_map(function ($x) {
            return var_export($x, true);
        }, $this->xs));
        return "Collection(" . $vals . ")";
    }
}

function Collection(...$args)
{
    return new Collection(...$args);
}
