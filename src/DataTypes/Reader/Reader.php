<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Reader;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\curry;

final class Reader
{
    use CurryNonPublicMethods;

    private $f;

    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    private function run($x)
    {
        return call_user_func($this->f, $x);
    }

    private static function of($x) : Reader
    {
        return new static(function ($_) use ($x) {
            return $x;
        });
    }

    private function map(callable $g) : Reader
    {
        return new static(function ($x) use ($g) {
            return $g($this->run($x));
        });
    }

    private function ap(Reader $g) : Reader
    {
        return new static(function ($x) use ($g) {
            return $g->run($x)($this->run($x));
        });
    }

    private function chain(callable $r) : Reader
    {
        return new static(function ($s) use ($r) {
            return $r($this->run($s))->run($s);
        });
    }

    private function bind(callable $r) : Reader
    {
        return $this->chain($r);
    }

    private function flatMap(callable $r) : Reader
    {
        return $this->chain($r);
    }
}

function Reader(...$args)
{
    return curry(function (callable $f) {
        return new Reader($f);
    })(...$args);
}
