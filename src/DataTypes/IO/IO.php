<?php declare(strict_types=1);

namespace Phantasy\DataTypes\IO;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\curry;

final class IO
{
    use CurryNonPublicMethods;

    private $f;
    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    protected static function of($x) : IO
    {
        return new static(function () use ($x) {
            return $x;
        });
    }

    protected function map(callable $f) : IO
    {
        return new static(function () use ($f) {
            return $f($this->run());
        });
    }

    protected function ap(IO $g) : IO
    {
        return new static(function () use ($g) {
            return $g->run()($this->run());
        });
    }

    protected function chain(callable $f) : IO
    {
        return new static(function () use ($f) {
            return $f($this->run())->run();
        });
    }

    protected function flatMap(callable $f) : IO
    {
        return $this->chain($f);
    }

    protected function bind(callable $f) : IO
    {
        return $this->chain($f);
    }

    public function run()
    {
        return call_user_func($this->f);
    }
}

function IO(...$args)
{
    return curry(function (callable $f) {
        return new IO($f);
    })(...$args);
}
