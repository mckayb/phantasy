<?php

namespace Phantasy\DataTypes\Validation;

class Validation
{
    public static function of($x)
    {
        return new Success($x);
    }

    public static function fromNullable($x, $msg = null)
    {
        return is_null($x) ? new Failure($msg) : new Success($msg);
    }

    public static function tryCatch($f)
    {
        try {
            return new Success($f());
        } catch (\Exception $e) {
            return new Failure($e);
        }
    }
}
