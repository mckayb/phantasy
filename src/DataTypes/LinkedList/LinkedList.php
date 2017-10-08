<?php declare(strict_types=1);

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\DataTypes\LinkedList\{Cons, Nil};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{reduce, curry};

abstract class LinkedList
{
    use CurryNonPublicMethods;

    abstract protected function equals(LinkedList $e) : bool;
    abstract protected function concat(LinkedList $e) : LinkedList;
    abstract protected function map(callable $f) : LinkedList;
    abstract protected function ap(LinkedList $eitherWithFunction) : LinkedList;
    abstract protected function chain(callable $f) : LinkedList;
    abstract protected function bind(callable $f) : LinkedList;
    abstract protected function flatMap(callable $f) : LinkedList;
    abstract protected function reduce(callable $f, $acc);
    abstract protected function traverse(string $className, callable $f);
    abstract protected function sequence(string $className);
    abstract public function join() : LinkedList;
    abstract public function __toString() : string;
    abstract public function head();
    abstract public function tail() : LinkedList;

    final private static function of($x) : LinkedList
    {
        return new Cons($x, new Nil());
    }

    final private static function fromArray(array $arr) : LinkedList
    {
        return reduce(function ($list, $x) {
            return $list->concat(new Cons($x, new Nil()));
        }, new Nil(), $arr);
    }

    final public static function empty() : LinkedList
    {
        return new Nil();
    }
}
