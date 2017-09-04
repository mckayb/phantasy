<?php

namespace Phantasy\Core;

function curry(callable $callable)
{
    $ref = new \ReflectionFunction($callable);

    $recurseFunc = function () use ($callable, $ref, &$recurseFunc) {
        $args = func_get_args();
        if (func_num_args() >= $ref->getNumberOfRequiredParameters()) {
            return call_user_func_array($callable, $args);
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

        $recurseFunc = function () use ($callable, $ref, &$recurseFunc, $n) {
            $args = func_get_args();
            if (func_num_args() >= $n) {
                return call_user_func_array($callable, $args);
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
        function (callable $f, callable $g) {
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
        function (string $s, $x) {
            if (is_object($x)) {
                return $x->{$s};
            } elseif (is_array($x)) {
                return $x[$s];
            } elseif (is_string($x) && class_exists($x)) {
                return $x::$$s;
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
        if (method_exists($a, 'equals')) {
            return call_user_func([$a, 'equals'], $b);
        }

        return $a === $b;
    });

    return $equals(...func_get_args());
}

// +lte :: Ord a => a -> a -> Bool
function lte()
{
    $lte = curry(function ($a, $b) {
        if (method_exists($a, 'lte')) {
            return call_user_func([$a, 'lte'], $b);
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
            if (method_exists($x, 'contramap')) {
                return call_user_func([$x, 'contramap'], $f);
            } elseif (method_exists($x, 'cmap')) {
                return call_user_func([$x, 'cmap'], $f);
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
            if (method_exists($x, 'map')) {
                return call_user_func([$x, 'map'], $f);
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
            if (method_exists($x, 'filter')) {
                return call_user_func([$x, 'filter'], $f);
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
            return call_user_func([$x, 'bimap'], $f, $g);
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
            if (method_exists($x, 'reduce')) {
                return call_user_func([$x, 'reduce'], $f, $i);
            } elseif (method_exists($x, 'foldl')) {
                return call_user_func([$x, 'foldl'], $f, $i);
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
            if (method_exists($x, 'reduceRight')) {
                return call_user_func([$x, 'reduceRight'], $f, $i);
            } elseif (method_exists($x, 'foldr')) {
                return call_user_func([$x, 'foldr'], $f, $i);
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
        return call_user_func([$a, 'ap'], $fa);
    });

    return $ap(...func_get_args());
}

// +of :: a -> b -> f b
function of()
{
    $of = curry(function ($a, $x) {
        return call_user_func([$a, 'of'], $x);
    });

    return $of(...func_get_args());
}

// +chain :: Chain m => (a -> m b) -> m a -> m b
function chain()
{
    $chain = curry(function (callable $f, $a) {
        if (method_exists($a, 'chain')) {
            return call_user_func([$a, 'chain'], $f);
        } elseif (method_exists($a, 'flatMap')) {
            return call_user_func([$a, 'flatMap'], $f);
        } elseif (method_exists($a, 'bind')) {
            return call_user_func([$a, 'bind'], $f);
        }
    });

    return $chain(...func_get_args());
}

function bind()
{
    return chain(...func_get_args());
}

function flatMap()
{
    return chain(...func_get_args());
}

// +alt :: Alt f => f a -> f a -> f a
function alt()
{
    $alt = curry(function ($a, $b) {
        if (method_exists($a, 'alt')) {
            return call_user_func([$a, 'alt'], $b);
        }

        return $a || $b;
    });

    return $alt(...func_get_args());
}

// +zero :: Plus f => a -> f b
function zero()
{
    $zero = curry(function ($a) {
        return call_user_func([$a, 'zero']);
    });

    return $zero(...func_get_args());
}

function sequence()
{
    $sequence = curry(function (string $className, $x) {
        if (!method_exists($className, 'of')) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an of method).'
            );
        }

        if (method_exists($x, 'sequence')) {
            return call_user_func([$x, 'sequence'], $className);
        } elseif (method_exists($x, 'traverse')) {
            return call_user_func([$x, 'traverse'], $className, identity());
        }
    });

    return $sequence(...func_get_args());
}

function traverse()
{
    $traverse = curry(function (string $className, callable $f, $x) {
        if (!method_exists($className, 'of')) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an of method).'
            );
        }

        return call_user_func([$x, 'traverse'], $className, $f);
    });

    return $traverse(...func_get_args());
}

function chainRec()
{
    $chainRec = curry(function (callable $f, $x, $m) {
        return call_user_func([$m, 'chainRec'], $f, $x);
    });

    return $chainRec(...func_get_args());
}

function extend()
{
    $extend = curry(function (callable $f, $w) {
        return call_user_func([$w, 'extend'], $f);
    });

    return $extend(...func_get_args());
}

function extract()
{
    $extract = curry(function ($x) {
        return call_user_func([$x, 'extract']);
    });

    return $extract(...func_get_args());
}

// +mjoin :: Monad m => m (m a) -> m a
function mjoin()
{
    $mjoin = curry(function ($a) {
        if (method_exists($a, 'join')) {
            return call_user_func([$a, 'join']);
        } elseif (method_exists($a, 'mjoin')) {
            return call_user_func([$a, 'mjoin']);
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

        if (method_exists($x, 'concat')) {
            return call_user_func([$x, 'concat'], $y);
        }

        if (is_string($x) && is_string($y)) {
            return $x . $y;
        }
    });

    return $semigroupConcat(...func_get_args());
}

// +concat :: Semigroup a => a -> a -> a
function concat()
{
    return semigroupConcat(...func_get_args());
}

// +mempty :: Monoid a => a -> a
function mempty()
{
    $mempty = curry(function ($x) {
        if (is_array($x)) {
            return [];
        }

        if (method_exists($x, 'empty')) {
            return call_user_func([$x, 'empty']);
        }

        if (is_string($x)) {
            return '';
        }
    });

    return $mempty(...func_get_args());
}

// +liftA :: Functor f => (a -> b) -> f a -> f b
function liftA()
{
    $liftA = curry(function (callable $f, $a) {
        return call_user_func([$a, 'map'], $f);
    });

    return $liftA(...func_get_args());
}

// +liftA2 :: Apply f => (a -> b -> c) -> f a -> f b -> f c
function liftA2()
{
    $liftA2 = curry(function (callable $f, $a1, $a2) {
        return call_user_func([$a2, 'ap'], call_user_func([$a1, 'map'], $f));
    });

    return $liftA2(...func_get_args());
}

// +liftA3 :: Apply f => (a -> b -> c -> d) -> f a -> f b -> f c -> f d
function liftA3()
{
    $liftA3 = curry(function (callable $f, $a1, $a2, $a3) {
        return call_user_func([$a3, 'ap'], call_user_func([$a2, 'ap'], call_user_func([$a1, 'map'], $f)));
    });

    return $liftA3(...func_get_args());
}

// +liftA4 :: Apply f => (a -> b -> c -> d -> e) -> f a -> f b -> f c -> f d -> f e
function liftA4()
{
    $liftA4 = curry(function (callable $f, $a1, $a2, $a3, $a4) {
        return call_user_func(
            [$a4, 'ap'],
            call_user_func(
                [$a3, 'ap'],
                call_user_func(
                    [$a2, 'ap'],
                    call_user_func(
                        [$a1, 'map'],
                        $f
                    )
                )
            )
        );
    });

    return $liftA4(...func_get_args());
}

// +liftA5 :: Apply f => (a -> b -> c -> d -> e -> g) -> f a -> f b -> f c -> f d -> f e -> f g
function liftA5()
{
    $liftA5 = curry(function (callable $f, $a1, $a2, $a3, $a4, $a5) {
        return call_user_func(
            [$a5, 'ap'],
            call_user_func(
                [$a4, 'ap'],
                call_user_func(
                    [$a3, 'ap'],
                    call_user_func(
                        [$a2, 'ap'],
                        call_user_func(
                            [$a1, 'map'],
                            $f
                        )
                    )
                )
            )
        );
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
