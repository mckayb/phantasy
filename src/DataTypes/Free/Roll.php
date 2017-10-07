<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Free;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{ap, map, curry, composeK};

final class Roll extends Free
{
    use CurryNonPublicMethods;

    private $value = null;
    private $g = null;

    public function __construct($x, callable $g)
    {
        $this->value = $x;
        $this->g = $g;
    }

    private function map(callable $f) : Free
    {
        $g = $this->g;
        return new static($this->value, function ($y) use ($f, $g) {
            return map($f, $g($y));
        });
    }

    private function ap(Free $a) : Free
    {
        $g = $this->g;
        return new static($this->value, function ($y) use ($a, $g) {
            return ap($a, $g($y));
        });
    }

    private function chain(callable $f) : Free
    {
        return new static($this->value, composeK($f, $this->g));
    }
}

function Roll(...$args)
{
    return curry(function ($x, callable $g) {
        return new Roll($x, $g);
    })(...$args);
}
