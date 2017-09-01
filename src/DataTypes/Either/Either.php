<?php

namespace Phantasy\DataTypes\Either;

use function Phantasy\Core\curry;
use Phantasy\Traits\CurryNonPublicMethods;

class Either
{
    use CurryNonPublicMethods;

    final private static function of($val)
    {
        return new Right($val);
    }

    final private static function fromNullable($failVal, $val)
    {
        return is_null($val) ? new Left($failVal) : new Right($val);
    }

    final private static function tryCatch(callable $f)
    {
        try {
            return new Right($f());
        } catch (\Exception $e) {
            return new Left($e);
        }
    }

    final public static function zero() : Either
    {
        return new Left(null);
    }
}
