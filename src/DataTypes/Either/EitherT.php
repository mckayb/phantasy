<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Either\{Either, Left};
use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{curry, map, ap, chain, of};

final class EitherT
{
    use CurryNonPublicMethods;

    private $f = null;

    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    protected static function of(string $outer, $x) : EitherT
    {
        return new static(function () use ($outer, $x) {
            return of($outer, Either::of($x));
        });
    }

    protected function map(callable $f) : EitherT
    {
        return new static(function () use ($f) {
            return map(map($f), $this->run());
        });
    }

    protected function ap(EitherT $eitherTWithFunction) : EitherT
    {
        return new static(function () use ($eitherTWithFunction) {
            return chain(function (Either $mf) {
                return map(ap($mf), $this->run());
            }, $eitherTWithFunction->run());
        });
    }

    protected function chain(callable $f) : EitherT
    {
        return new static(function () use ($f) {
            $a = $this->run();

            return chain(function (Either $mf) use ($f, $a) {
                return $mf->fold(
                    function ($x) use ($a) {
                        return of(get_class($a), $x);
                    },
                    function ($x) use ($f) {
                        return $f($x)->run();
                    }
                );
            }, $a);
        });
    }

    protected function bind(callable $f) : EitherT
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f) : EitherT
    {
        return $this->chain($f);
    }

    public function run()
    {
        return call_user_func($this->f);
    }
}

function EitherT(...$args)
{
    return curry(function (callable $f) {
        return new EitherT($f);
    })(...$args);
}
