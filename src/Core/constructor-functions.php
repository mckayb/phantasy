<?php
use Phantasy\DataTypes\Reader\Reader;
use Phantasy\DataTypes\Writer\Writer;
use Phantasy\DataTypes\Maybe\{Nothing, Just};
use Phantasy\DataTypes\Either\{Left, Right};
use Phantasy\DataTypes\Validation\{Failure, Success};
use Phantasy\DataTypes\LinkedList\{Cons, Nil};

if (!function_exists('Cons')) {
    function Cons($head, $tail)
    {
        return new Cons($head, $tail);
    }
}

if (!function_exists('Nil')) {
    function Nil()
    {
        return new Nil();
    }
}

if (!function_exists('Writer')) {
    function Writer($f)
    {
        return new Writer($f);
    }
}

if (!function_exists('Reader')) {
    function Reader($f)
    {
        return new Reader($f);
    };
}

if (!function_exists('Just')) {
    function Just($x)
    {
        return new Just($x);
    };
}

if (!function_exists('Nothing')) {
    function Nothing()
    {
        return new Nothing();
    };
}

if (!function_exists('Left')) {
    function Left($x)
    {
        return new Left($x);
    };
}

if (!function_exists('Right')) {
    function Right($x)
    {
        return new Right($x);
    };
}

if (!function_exists('Failure')) {
    function Failure($x)
    {
        return new Failure($x);
    };
}

if (!function_exists('Success')) {
    function Success($x)
    {
        return new Success($x);
    };
}
