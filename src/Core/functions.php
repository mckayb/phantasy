<?php
namespace Phantasy\Core;

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

function curryN()
{
    $curryN = curry(function ($n, $callable) {
        $ref = new \ReflectionFunction($callable);

        $recurseFunc = function () use ($ref, &$recurseFunc, $n) {
            $args = func_get_args();
            if (func_num_args() === $n) {
                return $ref->invokeArgs($args);
            } else {
                return function () use ($args, &$recurseFunc) {
                    return $recurseFunc(...array_merge($args, func_get_args()));
                };
            }
        };

        return $recurseFunc;
    });

    return $curryN(...func_get_args());
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
        identity()
    );
}

function identity()
{
    $id = curry(function ($x) {
        return $x;
    });

    return $id(...func_get_args());
}

function prop()
{
    $prop = curry(
        function ($s, $x) {
            if (is_object($x)) {
                return $x->{$s};
            } elseif (is_array($x)) {
                return $x[$s];
            } else {
                return null;
            }
        }
    );
    return $prop(...func_get_args());
}

function trace()
{
    $trace = curry(function ($x) {
        var_dump($x);
        return $x;
    });

    return $trace(...func_get_args());
}

function map()
{
    $map = curry(
        function ($f, $x) {
            if (is_object($x) && method_exists($x, 'map')) {
                return $x->map($f);
            } else {
                $res = [];
                foreach ($x as $k => $y) {
                    $res[$k] = $f($y);
                }
                return $res;
            }
        }
    );
    return $map(...func_get_args());
}

function fmap()
{
    return map(...func_get_args());
}

function filter()
{
    $filter = curry(
        function ($f, $x) {
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
        }
    );
    return $filter(...func_get_args());
}

function reduce()
{
    $reduce = curry(
        function ($f, $i, $x) {
            if (is_object($x) && method_exists($x, 'reduce')) {
                return $x->reduce($f, $i);
            } else {
                $acc = $i;
                foreach ($x as $y) {
                    $acc = $f($acc, $y);
                }
                return $acc;
            }
        }
    );

    return $reduce(...func_get_args());
}

function ap()
{
    $ap = curry(function ($fa, $a) {
        return $a->ap($fa);
    });

    return $ap(...func_get_args());
}

function chain()
{
    $chain = curry(function ($f, $a) {
        return $a->chain($f);
    });

    return $chain(...func_get_args());
}

function mjoin()
{
    $mjoin = curry(function ($a) {
        if (method_exists($a, 'join')) {
            return $a->join();
        } elseif (method_exists($a, 'mjoin')) {
            return $a->mjoin();
        }
    });

    return $mjoin(...func_get_args());
}

function semigroupConcat()
{
    $semigroupConcat = curry(function ($x, $y) {
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
    });

    return $semigroupConcat(...func_get_args());
}

function concat()
{
    return semigroupConcat(...func_get_args());
}

function mempty()
{
    $mempty = curry(function ($x) {
        if (is_array($x)) {
            return [];
        }

        if (is_string($x)) {
            return '';
        }

        if (is_object($x) && method_exists($x, 'empty')) {
            return $x->empty();
        }
    });

    return $mempty(...func_get_args());
}

function liftA()
{
    $liftA = curry(function ($f, $a) {
        return $a->map($f);
    });

    return $liftA(...func_get_args());
}

function liftA2()
{
    $liftA2 = curry(function ($f, $a1, $a2) {
        return $a2->ap($a1->map($f));
    });

    return $liftA2(...func_get_args());
}

function liftA3()
{
    $liftA3 = curry(function ($f, $a1, $a2, $a3) {
        return $a3->ap($a2->ap($a1->map($f)));
    });

    return $liftA3(...func_get_args());
}

function liftA4()
{
    $liftA4 = curry(function ($f, $a1, $a2, $a3, $a4) {
        return $a4->ap($a3->ap($a2->ap($a1->map($f))));
    });

    return $liftA4(...func_get_args());
}

function liftA5()
{
    $liftA5 = curry(function ($f, $a1, $a2, $a3, $a4, $a5) {
        return $a5->ap($a4->ap($a3->ap($a2->ap($a1->map($f)))));
    });

    return $liftA5(...func_get_args());
}

function isTraversable()
{
    $isTraversable = curry(function ($x) {
        return is_array($x) || $x instanceof \Traversable;
    });

    return $isTraversable(...func_get_args());
}
