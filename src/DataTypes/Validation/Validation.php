<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Validation;

use Phantasy\Traits\CurryNonPublicMethods;

abstract class Validation
{
    use CurryNonPublicMethods;

    final private static function of($x) : Validation
    {
        return new Success($x);
    }

    final private static function fromNullable($failVal, $val) : Validation
    {
        return is_null($val) ? new Failure($failVal) : new Success($val);
    }

    final private static function tryCatch(callable $f) : Validation
    {
        try {
            return new Success($f());
        } catch (\Exception $e) {
            return new Failure($e);
        }
    }

    final public static function zero() : Validation
    {
        return new Failure([]);
    }
}
