<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Writer;

use Phantasy\Traits\CurryNonPublicMethods;
use function Phantasy\Core\{of, map, curry};

final class WriterT
{
    use CurryNonPublicMethods;

    private $f;

    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    protected function run()
    {
        return call_user_func($this->f);
    }

    protected static function of(string $outer, $x, $m = []): WriterT
    {
        return new static(function () use ($outer, $x, $m) {
            return of($outer, Writer::of($x, $m));
        });
    }

    protected function map(callable $f) : WriterT
    {
        return new static(function () use ($f) {
            return map(map($f, $this->run()));
        });
    }

    protected function ap(WriterT $writerTWithFunction) : WriterT
    {
        return new static(function () use ($writerTWithFunction) {
            return chain(function (Writer $w) {
                return map(ap($w->run()), $this->run());
            }, $writerTWithFunction->run());
        });
    }

    protected function chain(callable $f): WriterT
    {
        return new static(function () use ($f) {
            return chain(function (Writer $w) use ($f) {
                return $f($w->run())->run();
            }, $this->run());
        });
    }

    protected function bind(callable $f): WriterT
    {
        return $this->chain($f);
    }

    protected function flatMap(callable $f): WriterT
    {
        return $this->chain($f);
    }
}

function WriterT(...$args)
{
    return curry(function (callable $f) {
        return new WriterT($f);
    })(...$args);
}
