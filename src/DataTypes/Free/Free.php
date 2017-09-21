<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Free;

use Phantasy\DataTypes\Free\{Pure, Roll};
use function Phantasy\DataTypes\Free\Pure;
use Phantasy\Traits\CurryNonPublicMethods;

abstract class Free
{
    use CurryNonPublicMethods;

    final private static function of($x) : Free
    {
        return new Pure($x);
    }

    final private static function liftF($x) : Free
    {
        return new Roll($x, Pure());
    }
}
