<?php

namespace Phantasy\DataTypes\Maybe;

use function Phantasy\Core\curry;
use Phantasy\Traits\CurryNonPublicMethods;

class Maybe
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

    final public static function zero() : Maybe
    {
        return new Nothing();
    }
}
