<?php

namespace Phantasy\DataTypes\Reader;

class Reader
{
    private $f;

    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    public function run($x)
    {
        return call_user_func($this->f, $x);
    }

    public static function of($x) : Reader
    {
        return new Reader(function ($_) use ($x) {
            return $x;
        });
    }

    public function map(callable $g) : Reader
    {
        return new Reader(function ($x) use ($g) {
            return $g($this->run($x));
        });
    }

    public function ap(Reader $g) : Reader
    {
        return new Reader(function ($x) use ($g) {
            return $g->run($x)($this->run($x));
        });
    }

    public function chain(callable $r) : Reader
    {
        return new Reader(function ($s) use ($r) {
            return $r($this->run($s))->run($s);
        });
    }

    public function bind(callable $r) : Reader
    {
        return $this->chain($r);
    }

    public function flatMap(callable $r) : Reader
    {
        return $this->chain($r);
    }
}

function Reader(callable $f)
{
    return new Reader($f);
}
