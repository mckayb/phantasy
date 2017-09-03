<?php

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\DataTypes\LinkedList\{Cons, Nil};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{reduce, curry};

abstract class LinkedList
{
    use CurryNonPublicMethods;

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
