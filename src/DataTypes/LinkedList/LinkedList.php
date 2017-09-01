<?php

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\DataTypes\LinkedList\{Cons, Nil};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{reduce, curry};

class LinkedList
{
    use CurryNonPublicMethods;

    final private static function of($x) : Cons
    {
        return new Cons($x, new Nil());
    }

    final private static function fromArray(array $arr)
    {
        return reduce(function ($list, $x) {
            return $list->concat(new Cons($x, new Nil()));
        }, new Nil(), $arr);
    }

    final public static function empty() : Nil
    {
        return new Nil();
    }
}
