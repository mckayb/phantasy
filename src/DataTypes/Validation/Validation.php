<?php

namespace Phantasy\DataTypes\Validation;

use Phantasy\Traits\CurryNonPublicMethods;

class Validation
{
    use CurryNonPublicMethods;

    private static function of($x) : Validation
    {
        return new Success($x);
    }

    private static function fromNullable($failVal, $val) : Validation
    {
        return is_null($val) ? new Failure($failVal) : new Success($val);
    }

    private static function tryCatch(callable $f) : Validation
    {
        try {
            return new Success($f());
        } catch (\Exception $e) {
            return new Failure($e);
        }
    }

    public static function zero() : Validation
    {
        return new Failure([]);
    }
}
