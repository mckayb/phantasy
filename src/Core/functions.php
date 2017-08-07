<?php
namespace Phantasy\Core;

function curry(callable $callable)
{
    $ref = new \ReflectionFunction($callable);

    $recurseFunc = function () use ($ref, &$recurseFunc) {
        $args = func_get_args();
        if (func_num_args() >= $ref->getNumberOfRequiredParameters()) {
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
    $curryN = curry(function (int $n, callable $callable) {
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

// +identity :: a -> a
function identity()
{
    $id = curry(function ($x) {
        return $x;
    });

    return $id(...func_get_args());
}

// +id :: a -> a
function id()
{
    return identity(...func_get_args());
}

// +prop :: String -> a -> b
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

// +trace :: a -> IO a
function trace()
{
    $trace = curry(function ($x) {
        var_dump($x);
        return $x;
    });

    return $trace(...func_get_args());
}

// +equals :: Setoid a => a -> a -> Bool
function equals()
{
    $equals = curry(function ($a, $b) {
        if (is_object($a) && method_exists($a, 'equals')) {
            return $a->equals($b);
        }

        return $a === $b;
    });

    return $equals(...func_get_args());
}

// +lte :: Ord a => a -> a -> Bool
function lte()
{
    $lte = curry(function ($a, $b) {
        if (is_object($a) && method_exists($a, 'lte')) {
            return $a->lte($b);
        }

        return $a <= $b;
    });

    return $lte(...func_get_args());
}

// +contramap :: Contravariant f => (b -> a) -> f a -> f b
function contramap()
{
    $contramap = curry(
        function (callable $f, $x) {
            if (is_object($x) && method_exists($x, 'contramap')) {
                return $x->contramap($f);
            } elseif (is_object($x) && method_exists($x, 'cmap')) {
                return $x->cmap($f);
            } else {
                return null;
            }
        }
    );

    return $contramap(...func_get_args());
}

// +cmap :: Contravariant f => (b -> a) -> f a -> f b
function cmap()
{
    return contramap(...func_get_args());
}

// +map :: Functor f => (a -> b) -> f a -> f b
function map()
{
    $map = curry(
        function (callable $f, $x) {
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

// +fmap :: Functor f => (a -> b) -> f a -> f b
function fmap()
{
    return map(...func_get_args());
}

// +filter :: (a -> Bool) -> [a] -> [a]
function filter()
{
    $filter = curry(
        function (callable $f, $x) {
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

function bimap()
{
    $bimap = curry(
        function (callable $f, callable $g, $x) {
            return $x->bimap($f, $g);
        }
    );

    return $bimap(...func_get_args());
}

// +foldl :: (a -> b -> a) -> a -> [b] -> a
function foldl()
{
    return reduce(...func_get_args());
}

// +foldr :: (a -> b -> b) -> b -> [a] -> b
function foldr()
{
    return reduceRight(...func_get_args());
}

// +reduce :: (a -> b -> a) -> a -> [b] -> a
function reduce()
{
    $reduce = curry(
        function (callable $f, $i, $x) {
            if (is_object($x) && method_exists($x, 'reduce')) {
                return $x->reduce($f, $i);
            } elseif (is_object($x) && method_exists($x, 'foldl')) {
                return $x->foldl($f, $i);
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

// +reduceRight :: (a -> b -> b) -> b -> [a] -> b
function reduceRight()
{
    $reduceRight = curry(
        function (callable $f, $i, $x) {
            if (is_object($x) && method_exists($x, 'reduceRight')) {
                return $x->reduceRight($f, $i);
            } elseif (is_object($x) && method_exists($x, 'foldr')) {
                return $x->foldr($f, $i);
            } else {
                $c = count($x);
                $acc = $i;
                for ($j = $c - 1; $j >= 0; $j--) {
                    $acc = $f($acc, $x[$j]);
                }
                return $acc;
            }
        }
    );

    return $reduceRight(...func_get_args());
}

// +ap :: Apply f => f (a -> b) -> f a -> f b
function ap()
{
    $ap = curry(function ($fa, $a) {
        return $a->ap($fa);
    });

    return $ap(...func_get_args());
}

// +of :: a -> b -> f b
// Another weird workaround here
function of()
{
    $of = curry(function ($a, $x) {
        if (method_exists($a, 'of')) {
            return $a->of($x);
        }

        return null;
    });

    return $of(...func_get_args());
}

// +chain :: Chain m => (a -> m b) -> m a -> m b
function chain()
{
    $chain = curry(function (callable $f, $a) {
        return $a->chain($f);
    });

    return $chain(...func_get_args());
}

// +alt :: Alt f => f a -> f a -> f a
function alt()
{
    $alt = curry(function ($a, $b) {
        if (is_object($a) && is_object($b) && method_exists($a, 'alt')) {
            return $a->alt($b);
        }

        return $a || $b;
    });
}

// +zero :: Plus f => a -> f b
// Weird workaround here
function zero()
{
    $zero = curry(function ($a) {
        if (is_object($a) && method_exists($a, 'zero')) {
            return $a->zero();
        }

        return null;
    });

    return $zero(...func_get_args());
}

function sequence()
{
}

function traverse()
{
}

function chainRec()
{
}

function extend()
{
}

function extract()
{
}

function promap()
{
}

// +mjoin :: Monad m => m (m a) -> m a
function mjoin()
{
    $mjoin = curry(function ($a) {
        if (method_exists($a, 'join')) {
            return $a->join();
        } elseif (method_exists($a, 'mjoin')) {
            return $a->mjoin();
        } else {
            return null;
        }
    });

    return $mjoin(...func_get_args());
}

// +semigroupConcat :: Semigroup a => a -> a -> a
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

        return null;
    });

    return $semigroupConcat(...func_get_args());
}

// +concat :: Semigroup a => a -> a -> a
function concat()
{
    return semigroupConcat(...func_get_args());
}

// +mempty :: Monoid a => a -> a
// Little bit annoying limitation here.
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

        return null;
    });

    return $mempty(...func_get_args());
}

// +liftA :: Functor f => (a -> b) -> f a -> f b
function liftA()
{
    $liftA = curry(function (callable $f, $a) {
        return $a->map($f);
    });

    return $liftA(...func_get_args());
}

// +liftA2 :: Apply f => (a -> b -> c) -> f a -> f b -> f c
function liftA2()
{
    $liftA2 = curry(function (callable $f, $a1, $a2) {
        return $a2->ap($a1->map($f));
    });

    return $liftA2(...func_get_args());
}

// +liftA3 :: Apply f => (a -> b -> c -> d) -> f a -> f b -> f c -> f d
function liftA3()
{
    $liftA3 = curry(function (callable $f, $a1, $a2, $a3) {
        return $a3->ap($a2->ap($a1->map($f)));
    });

    return $liftA3(...func_get_args());
}

// +liftA4 :: Apply f => (a -> b -> c -> d -> e) -> f a -> f b -> f c -> f d -> f e
function liftA4()
{
    $liftA4 = curry(function (callable $f, $a1, $a2, $a3, $a4) {
        return $a4->ap($a3->ap($a2->ap($a1->map($f))));
    });

    return $liftA4(...func_get_args());
}

// +liftA5 :: Apply f => (a -> b -> c -> d -> e -> g) -> f a -> f b -> f c -> f d -> f e -> f g
function liftA5()
{
    $liftA5 = curry(function (callable $f, $a1, $a2, $a3, $a4, $a5) {
        return $a5->ap($a4->ap($a3->ap($a2->ap($a1->map($f)))));
    });

    return $liftA5(...func_get_args());
}

// +isTraversable :: a -> Bool
function isTraversable()
{
    $isTraversable = curry(function ($x) {
        return is_array($x) || $x instanceof \Traversable;
    });

    return $isTraversable(...func_get_args());
}
