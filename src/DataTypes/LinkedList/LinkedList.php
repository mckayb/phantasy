<?php

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\DataTypes\LinkedList\{Cons, Nil};
use function Phantasy\Core\{reduce, curry};

class LinkedList
{
    public static function of() : Cons
    {
        return curry(function ($x) {
            return new Cons($x, new Nil());
        })(...func_get_args());
    }

    public static function fromArray()
    {
        return curry(function (array $arr) {
            return reduce(function ($list, $x) {
                return $list->concat(new Cons($x, new Nil()));
            }, new Nil(), $arr);
        })(...func_get_args());
    }

    public static function empty() : Nil
    {
        return new Nil();
    }
}
