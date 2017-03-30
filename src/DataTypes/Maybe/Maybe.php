<?php

namespace Phantasy\DataTypes\Maybe;

class Maybe
{
    public static function of($val)
    {
        return new Just($val);
    }

    public static function fromNullable($val)
    {
        return is_null($val) ? new Nothing() : new Just($val);
    }

    public static function tryCatch($f)
    {
        try {
            return new Just($f());
        } catch (\Exception $e) {
            return new Nothing();
        }
    }
}
