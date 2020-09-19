<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Text;

use function Phantasy\Core\curry;
use Phantasy\Traits\CurryNonPublicMethods;

final class Text
{
    use CurryNonPublicMethods;

    private $str = null;

    public function __construct(string $str)
    {
        $this->str = $str;
    }
}

function Text(...$args)
{
    return curry(function (string $str) {
        return new Text($str);
    })(...$args);
}
