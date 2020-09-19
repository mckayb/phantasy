<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Num;

use function Phantasy\Core\curry;
use Phantasy\Traits\CurryNonPublicMethods;

final class Num
{
    use CurryNonPublicMethods;

    private $num = null;

    public function __construct($num)
    {
        $this->num = $num;
    }
}

function Num(...$args)
{
    return curry(function ($num) {
        return new Num($num);
    })(...$args);
}
