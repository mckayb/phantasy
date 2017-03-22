<?php

namespace PHPFP\DataTypes\Validation;

class Validation
{
    public static function of($x)
    {
        return new Success($x);
    }
}
