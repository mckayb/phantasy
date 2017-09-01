<?php

namespace Phantasy\DataTypes\Writer;

use function Phantasy\Core\{mempty, concat};

final class Writer
{
    private $func = null;

    public function __construct($f)
    {
        $this->func = $f;
    }

    public static function of($val, $m = [])
    {
        return new Writer(function () use ($val, $m) {
            return [$val, mempty($m)];
        });
    }

    public function run()
    {
        return call_user_func($this->func);
    }

    public function map(callable $f) : Writer
    {
        return new Writer(function () use ($f) {
            list ($compVal, $logVal) = $this->run();
            return [$f($compVal), $logVal];
        });
    }

    public function ap(Writer $w) : Writer
    {
        return new Writer(function () use ($w) {
            list ($compX, $logX) = $this->run();
            list ($compY, $logY) = $w->run();

            return [$compY($compX), concat($logX, $logY)];
        });
    }

    public function chain(callable $g) : Writer
    {
        return new Writer(function () use ($g) {
            list($compX, $logX) = $this->run();
            list($compY, $logY) = $g($compX)->run();
            return [$compY, concat($logY, $logX)];
        });
    }

    public function bind(callable $g) : Writer
    {
        return $this->chain($g);
    }

    public function flatMap(callable $g) : Writer
    {
        return $this->chain($g);
    }
}

function Writer($f) : Writer
{
    return new Writer($f);
}
