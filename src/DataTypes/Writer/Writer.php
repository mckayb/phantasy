<?php

namespace Phantasy\DataTypes\Writer;

use function Phantasy\Core\{mempty, concat, curry};
use Phantasy\Traits\CurryNonPublicMethods;

final class Writer
{
    use CurryNonPublicMethods;

    private $func = null;

    public function __construct(callable $f)
    {
        $this->func = $f;
    }

    private static function of($m, $val) : Writer
    {
        return new Writer(function () use ($val, $m) {
            return [$val, mempty($m)];
        });
    }

    public function run()
    {
        return call_user_func($this->func);
    }

    private function map(callable $f) : Writer
    {
        return new Writer(function () use ($f) {
            list ($compVal, $logVal) = $this->run();
            return [$f($compVal), $logVal];
        });
    }

    private function ap(Writer $w) : Writer
    {
        return new Writer(function () use ($w) {
            list ($compX, $logX) = $this->run();
            list ($compY, $logY) = $w->run();

            return [$compY($compX), concat($logX, $logY)];
        });
    }

    private function chain(callable $g) : Writer
    {
        return new Writer(function () use ($g) {
            list($compX, $logX) = $this->run();
            list($compY, $logY) = $g($compX)->run();
            return [$compY, concat($logY, $logX)];
        });
    }

    private function bind(callable $g) : Writer
    {
        return $this->chain($g);
    }

    private function flatMap(callable $g) : Writer
    {
        return $this->chain($g);
    }
}

function Writer()
{
    return curry(function (callable $f) {
        return new Writer($f);
    })(...func_get_args());
}
