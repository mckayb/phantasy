<?php

namespace Phantasy\DataTypes\Reader;

class Reader
{
    private $f;

    public function __construct($f)
    {
        $this->f = $f;
    }

    public function run($x)
    {
        $f = $this->f;
        return $f($x);
    }

    public static function of($x)
    {
        return new Reader(function ($s) use ($x) {
            return $x;
        });
    }

    public function map($g)
    {
        return new Reader(function ($x) use ($g) {
            return $g($this->run($x));
        });
    }

    public function ap($g)
    {
        return new Reader(function ($x) use ($g) {
            return $g->run($x)($this->run($x));
        });
    }

    public function chain($r)
    {
        return new Reader(function ($s) use ($r) {
            return $r($this->run($s))->run($s)($s);
        });
    }

    public function bind($r)
    {
        return $this->chain($r);
    }

    public function flatMap($r)
    {
        return $this->chain($r);
    }
}
