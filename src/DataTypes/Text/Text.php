<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Text;

use function Phantasy\Core\curry;
use Phantasy\Traits\CurryNonPublicMethods;

final class Text
{
    use CurryNonPublicMethods;

    private $s = null;

    public function __construct(string $s)
    {
        $this->s = $s;
    }

    protected function lte(Text $t) : bool
    {
        return strcmp($this->s, $t->__toString()) <= 0;
    }

    protected function equals(Text $t) : bool
    {
        return $this == $t;
    }

    protected function concat(Text $t) : Text
    {
        return new static($this->s . $t->__toString());
    }

    public static function empty() : Text
    {
        return new static("");
    }

    protected function map(callable $f) : Text
    {
        return new static($f($this->s));
    }

    public function __toString() : string
    {
        return $this->s;
    }
}

function Text(...$args)
{
    return curry(function (string $s) {
        return new Text($s);
    })(...$args);
}
