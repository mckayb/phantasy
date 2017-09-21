<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Free;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\curry;

final class Pure extends Free
{
    use CurryNonPublicMethods;

    private $value = null;

    public function __construct($x)
    {
        $this->value = $x;
    }

    private function map(callable $f) : Free
    {
        return new static($f($x));
    }

    private function ap(Free $f) : Free
    {
        $val = $this->value;
        return $f->map(function ($fn) use ($val) {
            return $fn($val);
        });
    }

    private function chain(callable $f) : Free
    {
        return $f($this->value);
    }
}

function Pure(...$args)
{
    return curry(function ($x) {
        return new Pure($x);
    })(...$args);
}
