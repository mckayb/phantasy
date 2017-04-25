<?php

namespace Phantasy\DataTypes\Validation;

use function Phantasy\Core\curry;

class Validation
{
    public static function of()
    {
        return curry(function ($x) {
            return new Success($x);
        })(...func_get_args());
    }

    public static function fromNullable()
    {
        return curry(function ($failVal, $val) {
            return is_null($val) ? new Failure($failVal) : new Success($val);
        })(...func_get_args());
    }

    public static function tryCatch()
    {
        return curry(function ($f) {
            try {
                return new Success($f());
            } catch (\Exception $e) {
                return new Failure($e);
            }
        })(...func_get_args());
    }
}
