<?php

namespace Phantasy\DataTypes\Either;

use function Phantasy\Core\curry;

class Either
{
    public static function of()
    {
        return curry(function ($val) {
            return new Right($val);
        })(...func_get_args());
    }

    public static function fromNullable()
    {
        return curry(function ($failVal, $val) {
            return is_null($val) ? new Left($failVal) : new Right($val);
        })(...func_get_args());
    }

    public static function tryCatch()
    {
        return curry(function ($f) {
            try {
                return new Right($f());
            } catch (\Exception $e) {
                return new Left($e);
            }
        })(...func_get_args());
    }
}
