<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Reader;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{of, curry, ap, map, chain};

final class ReaderT
{
    use CurryNonPublicMethods;

    private $f;
    private $outer = null;

    public function __construct(callable $f, string $outer = '')
    {
        if ($outer === '') {
            $ref = new \ReflectionFunction($f);
            $outer = $ref->getReturnType();
            if (is_null($outer) || $outer === '' || ($outer->isBuiltIn() && (string)$outer !== 'array')) {
                throw new \Exception('Unable to determine outer type. Either specify the outer type as the second parameter of the constructor, or ensure that your callable has a valid monadic return type.');
            }
        }

        $this->outer = $outer;
        $this->f = $f;
    }

    protected function run($s)
    {
        return call_user_func($this->f, $s);
    }

    protected static function of(string $outer, $x): ReaderT
    {
        return new static(function ($s) use ($outer, $x) {
            return of($outer, $x);
        }, $outer);
    }

    protected function map(callable $f) : ReaderT
    {
        return new static(function ($s) use ($f) {
            return map($f, $this->run($s));
        }, $this->outer);
    }

    protected function ap(ReaderT $r) : ReaderT
    {
        return new static(function ($s) use ($r) {
            return ap($r->run($s), $this->run($s));
        }, $this->outer);
    }

    protected function chain(callable $f): ReaderT
    {
        return new static(function ($s) use ($f) {
            return chain(function ($a) use ($f, $s) {
                return $f($a)->run($s);
            }, $this->run($s));
        }, $this->outer);
    }

    protected function bind(callable $f): ReaderT
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f): ReaderT
    {
        return $this->chain($f);
    }
}

function ReaderT(...$args)
{
    return curry(function (callable $f) {
        return new ReaderT($f);
    })(...$args);
}
