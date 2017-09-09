<?php declare(strict_types=1);

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

    private static function of($val, $m = []) : Writer
    {
        return new static(function () use ($val, $m) {
            return [$val, mempty($m)];
        });
    }

    private static function chainRec(callable $f, $i, $m = []) : Writer
    {
        $loopOrDone = function ($b) {
            return function ($x) use ($b) {
                return new class ($b, $x) {
                    public $x;
                    private $b;

                    public function __construct($b, $x)
                    {
                        $this->b = $b;
                        $this->x = $x;
                    }

                    public function isDone()
                    {
                        return $this->b;
                    }
                };
            };
        };

        $loop = $loopOrDone(false);
        $done = $loopOrDone(true);

        $acc = mempty($m);
        $currentVal = $loop($i);
        $count = 0;
        do {
            list($val, $log) = $f($loop, $done, $currentVal->x)->run();
            $acc = concat($acc, $log);
            $currentVal = $val;
        } while (!$currentVal->isDone());

        return new static(function () use ($acc, $currentVal) {
            return [$currentVal->x, $acc];
        });
    }

    public function run()
    {
        return call_user_func($this->func);
    }

    private function map(callable $f) : Writer
    {
        return new static(function () use ($f) {
            list ($compVal, $logVal) = $this->run();
            return [$f($compVal), $logVal];
        });
    }

    private function ap(Writer $w) : Writer
    {
        return new static(function () use ($w) {
            list ($compX, $logX) = $this->run();
            list ($compY, $logY) = $w->run();

            return [$compY($compX), concat($logX, $logY)];
        });
    }

    private function chain(callable $g) : Writer
    {
        return new static(function () use ($g) {
            list($compX, $logX) = $this->run();
            list($compY, $logY) = $g($compX)->run();
            return [$compY, concat($logY, $logX)];
        });
    }

    private function extend(callable $f) : Writer
    {
        return new static(function () use ($f) {
            $x = $this->run();
            return [$f($this), $x[1]];
        });
    }

    public function extract()
    {
        list($x, $_) = $this->run();
        return $x;
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

function Writer(...$args)
{
    return curry(function (callable $f) {
        return new Writer($f);
    })(...$args);
}
