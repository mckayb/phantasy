<?php declare(strict_types=1);

namespace Phantasy\DataTypes\State;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{of, map, ap, chain, curry};

final class StateT
{
    use CurryNonPublicMethods;

    private $f;

    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    protected function run($s)
    {
        return call_user_func($this->f, $s);
    }

    protected static function of(string $outer, $x): StateT
    {
        return new static(function () use ($outer, $x) {
            return of($outer, State::of($x));
        });
    }

    protected function map(callable $f) : StateT
    {
        return new static(function ($s) use ($f) {
            return map(map($f, $this->run($s)));
        });
    }

    protected function ap(StateT $stateTWithFunction) : StateT
    {
        return new static(function ($s) use ($stateTWithFunction) {
            return chain(function (State $w) use ($s) {
                return map(ap($w->run($s)), $this->run($s));
            }, $stateTWithFunction->run($s));
        });
    }

    protected function chain(callable $f): StateT
    {
        return new static(function ($s) use ($f) {
            return chain(function (State $w) use ($f, $s) {
                return $f($w->run($s))->run($s);
            }, $this->run($s));
        });
    }

    protected function bind(callable $f): StateT
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f): StateT
    {
        return $this->chain($f);
    }
}

function StateT(...$args)
{
    return curry(function (callable $f) {
        return new StateT($f);
    })(...$args);
}
