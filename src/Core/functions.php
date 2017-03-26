<?php
namespace PHPFP\Core;

function curry($callable)
{
    $ref = new \ReflectionFunction($callable);

    $recurseFunc = function () use ($ref, &$recurseFunc) {
        $args = func_get_args();
        if (func_num_args() === $ref->getNumberOfRequiredParameters()) {
            return $ref->invokeArgs($args);
        } else {
            return function () use ($args, &$recurseFunc) {
                return $recurseFunc(...array_merge($args, func_get_args()));
            };
        }
    };

    return $recurseFunc;
}

function compose(...$fns)
{
    return array_reduce(
        $fns,
        function ($f, $g) {
            return function ($x) use ($f, $g) {
                return $f($g($x));
            };
        },
        'PHPFP\Core\identity'
    );
}

// @codingStandardsIgnoreStart
const identity = 'PHPFP\Core\identity';
// @codingStandardsIgnoreEnd
function identity($x)
{
    return $x;
}

function prop()
{
    $prop = curry(function ($s, $x) {
        if (is_object($x)) {
            return $x->{$s};
        } elseif (is_array($x)) {
            return $x[$s];
        }
    });
    return $prop(...func_get_args());
}

function map()
{
    $map = curry(function ($f, $x) {
        if (is_object($x) && method_exists($x, 'map')) {
            return $x->map($f);
        } else {
            $res = [];
            foreach ($x as $y) {
                $res[] = $f($y);
            }
            return $res;
        }
    });
    return $map(...func_get_args());
}

function filter()
{
    $filter = curry(function ($f, $x) {
        if (is_object($x) && method_exists($x, 'filter')) {
            return $x->filter($f);
        } else {
            $res = [];
            foreach ($x as $y) {
                if ($z = $f($y)) {
                    $res[] = $z;
                }
            }
            return $res;
        }
    });
    return $filter(...func_get_args());
}

function reduce()
{
    $reduce = curry(function ($f, $i, $x) {
        if (is_object($x) && method_exists($x, 'reduce')) {
            return $x->reduce($f, $i);
        } else {
            $acc = $i;
            foreach ($x as $y) {
                $acc = $f($acc, $y);
            }
            return $acc;
        }
    });

    return $reduce(...func_get_args());
}

function semigroupConcat($x, $y)
{
    if (is_array($x) && is_array($y)) {
        return array_merge($x, $y);
    }

    if (is_string($x) && is_string($y)) {
        return $x . $y;
    }

    if (is_object($x) && is_object($y) && method_exists($x, 'concat')) {
        return $x->concat($y);
    }

    throw new \Exception('Couldn\'t find a concat method to work with. Please specify your own concat function.');
}
