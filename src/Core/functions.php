<?php declare(strict_types=1);

namespace Phantasy\Core;

use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\DataTypes\Maybe\Nothing;

function curry(callable $callable)
{
    $ref = new \ReflectionFunction($callable);

    $recurseFunc = function (...$args) use ($callable, $ref, &$recurseFunc) {
        if (count($args) >= $ref->getNumberOfRequiredParameters()) {
            return call_user_func_array($callable, $args);
        } else {
            return function (...$args2) use ($args, &$recurseFunc) {
                return $recurseFunc(...array_merge($args, $args2));
            };
        }
    };

    return $recurseFunc;
}

function curryN(...$args)
{
    $curryN = curry(function (int $n, callable $callable) {
        $ref = new \ReflectionFunction($callable);

        $recurseFunc = function (...$args) use ($callable, $ref, &$recurseFunc, $n) {
            if (count($args) >= $n) {
                return call_user_func_array($callable, $args);
            } else {
                return function (...$args2) use ($args, &$recurseFunc) {
                    return $recurseFunc(...array_merge($args, $args2));
                };
            }
        };

        return $recurseFunc;
    });

    return $curryN(...$args);
}

function compose(callable ...$fns)
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
function identity(...$args)
{
    $id = curry(function ($x) {
        return $x;
    });

    return $id(...$args);
}

// +id :: a -> a
function id(...$args)
{
    return identity(...$args);
}

// +prop :: String -> a -> b
function prop(...$args)
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
    return $prop(...$args);
}

// +maybeProp :: String -> a -> Maybe b
function maybeProp(...$args)
{
    $maybeProp = curry(function (string $s, $x) {
        if (is_object($x)) {
            return Maybe::fromNullable($x->{$s} ?? null);
        } elseif (is_array($x)) {
            return Maybe::fromNullable($x[$s] ?? null);
        } elseif (is_string($x) && class_exists($x)) {
            return Maybe::fromNullable($x::$$s ?? null);
        } else {
            return Nothing();
        }
    });
    return $maybeProp(...$args);
}

// +trace :: a -> IO a
function trace(...$args)
{
    $trace = curry(function ($x) {
        var_dump($x);
        return $x;
    });

    return $trace(...$args);
}

// +equals :: Setoid a => a -> a -> Bool
function equals(...$args)
{
    $equals = curry(function ($a, $b) {
        if (method_exists($a, 'equals')) {
            return call_user_func([$a, 'equals'], $b);
        }

        return $a === $b;
    });

    return $equals(...$args);
}

// +lte :: Ord a => a -> a -> Bool
function lte(...$args)
{
    $lte = curry(function ($a, $b) {
        if (method_exists($a, 'lte')) {
            return call_user_func([$a, 'lte'], $b);
        }

        return $a <= $b;
    });

    return $lte(...$args);
}

// +contramap :: Contravariant f => (b -> a) -> f a -> f b
function contramap(...$args)
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

    return $contramap(...$args);
}

// +cmap :: Contravariant f => (b -> a) -> f a -> f b
function cmap(...$args)
{
    return contramap(...$args);
}

// +map :: Functor f => (a -> b) -> f a -> f b
function map(...$args)
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
    return $map(...$args);
}

// +fmap :: Functor f => (a -> b) -> f a -> f b
function fmap(...$args)
{
    return map(...$args);
}

// +filter :: (a -> Bool) -> [a] -> [a]
function filter(...$args)
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
    return $filter(...$args);
}

function bimap(...$args)
{
    $bimap = curry(
        function (callable $f, callable $g, $x) {
            return call_user_func([$x, 'bimap'], $f, $g);
        }
    );

    return $bimap(...$args);
}

// +foldl :: (a -> b -> a) -> a -> [b] -> a
function foldl(...$args)
{
    return reduce(...$args);
}

// +foldr :: (a -> b -> b) -> b -> [a] -> b
function foldr(...$args)
{
    return reduceRight(...$args);
}

// +reduce :: (a -> b -> a) -> a -> [b] -> a
function reduce(...$args)
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

    return $reduce(...$args);
}

// +reduceRight :: (a -> b -> b) -> b -> [a] -> b
function reduceRight(...$args)
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

    return $reduceRight(...$args);
}

// +ap :: Apply f => f (a -> b) -> f a -> f b
function ap(...$args)
{
    $ap = curry(function ($fa, $a) {
        return call_user_func([$a, 'ap'], $fa);
    });

    return $ap(...$args);
}

// +of :: a -> b -> f b
function of(...$args)
{
    $of = curry(function ($a, $x) {
        return call_user_func([$a, 'of'], $x);
    });

    return $of(...$args);
}

// +chain :: Chain m => (a -> m b) -> m a -> m b
function chain(...$args)
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

    return $chain(...$args);
}

function bind(...$args)
{
    return chain(...$args);
}

function flatMap(...$args)
{
    return chain(...$args);
}

// +alt :: Alt f => f a -> f a -> f a
function alt(...$args)
{
    $alt = curry(function ($a, $b) {
        if (method_exists($a, 'alt')) {
            return call_user_func([$a, 'alt'], $b);
        }

        return $a || $b;
    });

    return $alt(...$args);
}

// +zero :: Plus f => a -> f b
function zero(...$args)
{
    $zero = curry(function ($a) {
        return call_user_func([$a, 'zero']);
    });

    return $zero(...$args);
}

function sequence(...$args)
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

    return $sequence(...$args);
}

function traverse(...$args)
{
    $traverse = curry(function (string $className, callable $f, $x) {
        if (!method_exists($className, 'of')) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an of method).'
            );
        }

        return call_user_func([$x, 'traverse'], $className, $f);
    });

    return $traverse(...$args);
}

function chainRec(...$args)
{
    $chainRec = curry(function (callable $f, $x, $m) {
        return call_user_func([$m, 'chainRec'], $f, $x);
    });

    return $chainRec(...$args);
}

function extend(...$args)
{
    $extend = curry(function (callable $f, $w) {
        return call_user_func([$w, 'extend'], $f);
    });

    return $extend(...$args);
}

function extract(...$args)
{
    $extract = curry(function ($x) {
        return call_user_func([$x, 'extract']);
    });

    return $extract(...$args);
}

// +mjoin :: Monad m => m (m a) -> m a
function mjoin(...$args)
{
    $mjoin = curry(function ($a) {
        if (method_exists($a, 'join')) {
            return call_user_func([$a, 'join']);
        } elseif (method_exists($a, 'mjoin')) {
            return call_user_func([$a, 'mjoin']);
        }
    });

    return $mjoin(...$args);
}

function join(...$args)
{
    return mjoin(...$args);
}

// +semigroupConcat :: Semigroup a => a -> a -> a
function semigroupConcat(...$args)
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

        throw new \InvalidArgumentException(
            'x and y must be Semigroups of the same type.'
        );
    });

    return $semigroupConcat(...$args);
}

// +concat :: Semigroup a => a -> a -> a
function concat(...$args)
{
    return semigroupConcat(...$args);
}

// +mempty :: Monoid a => a -> a
function mempty(...$args)
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

    return $mempty(...$args);
}

// +liftA :: Functor f => (a -> b) -> f a -> f b
function liftA(...$args)
{
    $liftA = curry(function (callable $f, $a) {
        return call_user_func([$a, 'map'], $f);
    });

    return $liftA(...$args);
}

// +liftA2 :: Apply f => (a -> b -> c) -> f a -> f b -> f c
function liftA2(...$args)
{
    $liftA2 = curry(function (callable $f, $a1, $a2) {
        return call_user_func([$a2, 'ap'], call_user_func([$a1, 'map'], $f));
    });

    return $liftA2(...$args);
}

// +liftA3 :: Apply f => (a -> b -> c -> d) -> f a -> f b -> f c -> f d
function liftA3(...$args)
{
    $liftA3 = curry(function (callable $f, $a1, $a2, $a3) {
        return call_user_func([$a3, 'ap'], call_user_func([$a2, 'ap'], call_user_func([$a1, 'map'], $f)));
    });

    return $liftA3(...$args);
}

// +liftA4 :: Apply f => (a -> b -> c -> d -> e) -> f a -> f b -> f c -> f d -> f e
function liftA4(...$args)
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

    return $liftA4(...$args);
}

// +liftA5 :: Apply f => (a -> b -> c -> d -> e -> g) -> f a -> f b -> f c -> f d -> f e -> f g
function liftA5(...$args)
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

    return $liftA5(...$args);
}

// +isTraversable :: a -> Bool
function isTraversable(...$args)
{
    $isTraversable = curry(function ($x) {
        return is_array($x) || $x instanceof \Traversable;
    });

    return $isTraversable(...$args);
}

function composeK(callable ...$fns)
{
    return array_reduce($fns, function ($f, $g) {
        return is_null($f)
            ? $g
            : function (...$args) use ($f, $g) {
                return $g(...$args)->chain($f);
            };
    }, null);
}

function fold(callable $f, $x)
{
    return cata($f, $x);
}

function cata(callable $f, $xs)
{
    return $f(map(function ($ys) use ($f) {
        return cata($f, $ys);
    }, $xs));
}

function ana($t)
{
    return function (callable $g, $x) {
    };
}

// +hylo :: Functor f => (f b -> b) -> (a -> f a) -> a -> b
function hylo(callable $f, callable $g, $t)
{
    return $f(map(function ($x) {
        return hylo($f, $g, $x);
    }, $g($t)));
}

function para(callable $f, $acc, $xs)
{
    if (count($xs) === 0) {
        return $acc;
    }

    return para($f, $f($acc, head($xs), $xs), tail($xs));
}
