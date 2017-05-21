<?php

namespace Phantasy\DataTypes\LinkedList;

use Phantasy\DataTypes\LinkedList\{Cons, Nil};
use function Phantasy\Core\curry;

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
            $a = new Nil();
            foreach ($arr as $x) {
                $a = $a->concat(new Cons($x, new Nil()));
            }
            return $a;
        })(...func_get_args());
    }
}
