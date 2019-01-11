<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Maybe\{Maybe, Nothing};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{curry, map, ap, chain, of};

final class MaybeT
{
    use CurryNonPublicMethods;

    private $f = null;

    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    protected static function of(string $outer, $x) : MaybeT
    {
        return new static(function () use ($outer, $x) {
            return of($outer, Maybe::of($x));
        });
    }

    protected function map(callable $f) : MaybeT
    {
        return new static(function () use ($f) {
            return map(map($f), $this->run());
        });
    }

    protected function ap(MaybeT $maybeTWithFunction) : MaybeT
    {
        return new static(function () use ($maybeTWithFunction) {
            return chain(function (Maybe $mf) {
                return map(ap($mf), $this->run());
            }, $maybeTWithFunction->run());
        });
    }

    protected function chain(callable $f) : MaybeT
    {
        return new static(function () use ($f) {
            $a = $this->run();

            return chain(function (Maybe $mf) use ($f, $a) {
                return $mf->fold(
                    function () use ($a) {
                        return of(get_class($a), new Nothing());
                    },
                    function ($x) use ($f) {
                        return $f($x)->run();
                    }
                );
            }, $a);
        });
    }

    protected function bind(callable $f) : MaybeT
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : MaybeT
    {
        return $this->chain($f);
    }

    public function run()
    {
        return call_user_func($this->f);
    }

    public function __toString()
    {
        return "MaybeT(" . $this->run() . ")";
    }
}

function MaybeT(...$args)
{
    return curry(function (callable $f) {
        return new MaybeT($f);
    })(...$args);
}
