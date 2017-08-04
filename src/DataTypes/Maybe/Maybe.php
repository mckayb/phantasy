<?php

namespace Phantasy\DataTypes\Maybe;

use function Phantasy\Core\curry;

class Maybe
{
    public static function of()
    {
        return curry(function ($val) {
            return new Just($val);
        })(...func_get_args());
    }

    public static function fromNullable()
    {
        return curry(function ($val) {
            return is_null($val) ? new Nothing() : new Just($val);
        })(...func_get_args());
    }

    public static function tryCatch()
    {
        return curry(function (callable $f) {
            try {
                return new Just($f());
            } catch (\Exception $e) {
                return new Nothing();
            }
        })(...func_get_args());
    }

    public static function zero() : Nothing
    {
        return new Nothing();
    }
}
