<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Maybe;

use Phantasy\Traits\CurryNonPublicMethods;

abstract class Maybe
{
    use CurryNonPublicMethods;

    final private static function of($val) : Maybe
    {
        return new Just($val);
    }

    final private static function fromNullable($val) : Maybe
    {
        return is_null($val) ? new Nothing() : new Just($val);
    }

    final private static function tryCatch(callable $f) : Maybe
    {
        try {
            return new Just($f());
        } catch (\Exception $e) {
            return new Nothing();
        }
    }

    final public static function empty() : Maybe
    {
        return new Nothing();
    }

    final public static function zero() : Maybe
    {
        return new Nothing();
    }
}
