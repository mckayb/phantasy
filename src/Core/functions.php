<?php declare(strict_types=1);

namespace Phantasy\Core;

use Phantasy\DataTypes\Maybe\Maybe;
use Phantasy\DataTypes\Either\Either;
use Phantasy\DataTypes\LinkedList\LinkedList;
use Phantasy\DataTypes\Collection\Collection;
use Phantasy\DataTypes\Set\Set;
use function Phantasy\DataTypes\Maybe\Nothing;

function curry(callable $callable)
{
    $ref = new \ReflectionFunction($callable);
    $numParams = $ref->getNumberOfRequiredParameters();

    $recurseFunc = function (...$args) use ($callable, $numParams, &$recurseFunc) {
        if (count($args) >= $numParams) {
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

// +flip :: (a -> b -> c) -> b -> a -> c
function flip(...$args)
{
    $flip = curry(function (callable $f) {
        return curry(function ($a, $b, ...$args) use ($f) {
            return curry($f)($b, $a, ...$args);
        });
    });

    return $flip(...$args);
}

// +constant :: a -> (b -> a)
function constant(...$args)
{
    $constant = curry(function ($x) {
        return curry(function ($y) use ($x) {
            return $x;
        });
    });
    return $constant(...$args);
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
        if (is_callable([$a, 'equals'])) {
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
        if (is_callable([$a, 'lte'])) {
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
            if (is_callable([$x, 'contramap'])) {
                return call_user_func([$x, 'contramap'], $f);
            } elseif (is_callable([$x, 'cmap'])) {
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
            if (is_callable([$x, 'map'])) {
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
            if (is_callable([$x, 'filter'])) {
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
            if (is_callable([$x, 'reduce'])) {
                return call_user_func([$x, 'reduce'], $f, $i);
            } elseif (is_callable([$x, 'foldl'])) {
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
            if (is_callable([$x, 'reduceRight'])) {
                return call_user_func([$x, 'reduceRight'], $f, $i);
            } elseif (is_callable([$x, 'foldr'])) {
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
        if (is_callable([$a, 'ap'])) {
            return call_user_func([$a, 'ap'], $fa);
        } elseif (is_array($a)) {
            return join(map(function (callable $fn) use ($a) {
                return map($fn, $a);
            }, $fa));
        }
    });

    return $ap(...$args);
}

// +of :: a -> b -> f b
function of(...$args)
{
    $of = curry(function ($a, $x) {
        if (is_callable([$a, 'of'])) {
            return call_user_func([$a, 'of'], $x);
        } elseif (is_callable([$a, 'pure'])) {
            return call_user_func([$a, 'pure'], $x);
        } elseif (is_callable([$a, 'return'])) {
            return call_user_func([$a, 'return'], $x);
        } elseif (is_array($a) || (is_string($a) && strtolower($a) === 'array')) {
            return [$x];
        }
    });

    return $of(...$args);
}

// +pure :: a -> b -> f b
function pure(...$args)
{
    return of(...$args);
}

// +chain :: Chain m => (a -> m b) -> m a -> m b
function chain(...$args)
{
    $chain = curry(function (callable $f, $a) {
        if (is_callable([$a, 'chain'])) {
            return call_user_func([$a, 'chain'], $f);
        } elseif (is_callable([$a, 'flatMap'])) {
            return call_user_func([$a, 'flatMap'], $f);
        } elseif (is_callable([$a, 'bind'])) {
            return call_user_func([$a, 'bind'], $f);
        } elseif (is_array($a)) {
            return join(map($f, $a));
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
        if (is_callable([$a, 'alt'])) {
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
        if (!is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an of method).'
            );
        }

        if (is_callable([$x, 'sequence'])) {
            return call_user_func([$x, 'sequence'], $className);
        } elseif (is_callable([$x, 'traverse'])) {
            return call_user_func([$x, 'traverse'], $className, identity());
        } else {
            return traverse($className, identity(), $x);
        }
    });

    return $sequence(...$args);
}

function traverse(...$args)
{
    $traverse = curry(function (string $className, callable $f, $x) {
        if (!is_callable([$className, 'of'])) {
            throw new \InvalidArgumentException(
                'Method must be a class name of an Applicative (must have an of method).'
            );
        }

        if (is_callable([$x, 'traverse'])) {
            return call_user_func([$x, 'traverse'], $className, $f);
        } elseif (is_array($x)) {
            return reduce(function ($ys, $z) use ($f, $className) {
                return liftA2(curry(function ($a, $b) {
                    return concat($b, of('array', $a));
                }), $f($z), $ys);
            }, of($className, mempty($x)), $x);
        }
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
        if (is_callable([$a, 'join'])) {
            return call_user_func([$a, 'join']);
        } elseif (is_callable([$a, 'mjoin'])) {
            return call_user_func([$a, 'mjoin']);
        } elseif (is_array($a)) {
            return reduce(concat(), [], $a);
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

        if (is_callable([$x, 'concat'])) {
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

        if (is_callable([$x, 'empty'])) {
            return call_user_func([$x, 'empty']);
        }

        if (is_string($x) && strtolower($x) === 'array') {
            return [];
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
    return map(...$args);
}

// +liftA2 :: Apply f => (a -> b -> c) -> f a -> f b -> f c
function liftA2(...$args)
{
    $liftA2 = curry(function (callable $f, $a1, $a2) {
        return ap(map($f, $a1), $a2);
        // return call_user_func([$a2, 'ap'], call_user_func([$a1, 'map'], $f));
    });

    return $liftA2(...$args);
}

// +liftA3 :: Apply f => (a -> b -> c -> d) -> f a -> f b -> f c -> f d
function liftA3(...$args)
{
    $liftA3 = curry(function (callable $f, $a1, $a2, $a3) {
        return ap(ap(map($f, $a1), $a2), $a3);
    });

    return $liftA3(...$args);
}

// +liftA4 :: Apply f => (a -> b -> c -> d -> e) -> f a -> f b -> f c -> f d -> f e
function liftA4(...$args)
{
    $liftA4 = curry(function (callable $f, $a1, $a2, $a3, $a4) {
        return ap(ap(ap(map($f, $a1), $a2), $a3), $a4);
    });

    return $liftA4(...$args);
}

// +liftA5 :: Apply f => (a -> b -> c -> d -> e -> g) -> f a -> f b -> f c -> f d -> f e -> f g
function liftA5(...$args)
{
    $liftA5 = curry(function (callable $f, $a1, $a2, $a3, $a4, $a5) {
        return ap(ap(ap(ap(map($f, $a1), $a2), $a3), $a4), $a5);
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

function head(...$args)
{
    $head = curry(function ($xs) {
        if (is_array($xs)) {
            return $xs[0] ?? null;
        } elseif (is_object($xs)) {
            if (property_exists($xs, 'head')) {
                return $xs->head ?? null;
            } elseif (is_callable([$xs, 'head'])) {
                return call_user_func([$xs, 'head']) ?? null;
            }
        }
    });
    return $head(...$args);
}

function tail(...$args)
{
    $tail = curry(function ($xs) {
        if (is_array($xs)) {
            return array_slice($xs, 1);
        } elseif (is_object($xs)) {
            if (property_exists($xs, 'tail')) {
                return $xs->tail ?? null;
            } elseif (is_callable([$xs, 'tail'])) {
                return call_user_func([$xs, 'tail']) ?? null;
            }
        }
    });
    return $tail(...$args);
}

function composeK(callable ...$fns)
{
    return array_reduce($fns, function ($f, $g) {
        return is_null($f)
            ? $g
            : function (...$args) use ($f, $g) {
                return chain($f, $g(...$args));
            };
    }, null);
}

function foldMap(...$args)
{
    $foldMap = curry(function (callable $f, $xs) {
        return compose(fold(), map($f))($xs);
    });

    return $foldMap(...$args);
}

function fold(...$args)
{
    $fold = curry(function ($xs) {
        return reduce(function ($prev, $curr) {
            return is_null($prev)
                ? concat(mempty($curr), $curr)
                : concat($prev, $curr);
        }, null, $xs);
    });

    return $fold(...$args);
}

function unfold(...$args)
{
    $go = function (callable $f, $seed, $acc) use (&$go) {
        list($val, $nextSeed) = $f($seed);
        return $val ? $go($f, $nextSeed, concat($acc, [$val])) : $acc;
    };

    $unfold = curry(function (callable $f, $seed) use ($go) {
        return $go($f, $seed, []);
    });

    return $unfold(...$args);
}

function mDo(callable $generatorFunc)
{
    $getOfObj = function ($x) {
        $par = get_parent_class($x);
        return $par === false ? $x : $par;
    };

    $handleGen = function (&$generator) use (&$handleGen, $getOfObj) {
        $curr = $generator->current();

        if (!$generator->valid()) {
            return $curr;
        }
        return chain(function ($x) use (&$handleGen, $generator, $getOfObj, $curr) {
            $generator->send($x);
            $val = $handleGen($generator);
            return $val ?? of($getOfObj($curr), $x);
        }, $curr);
    };

    $gen = $generatorFunc();
    return of($getOfObj($handleGen($gen)), $gen->getReturn());
}

function arrayToLinkedList(...$args)
{
    return curry(function (array $a) : LinkedList {
        return LinkedList::fromArray($a);
    })(...$args);
}

function arrayToCollection(...$args)
{
    return curry(function (array $a) : Collection {
        return Collection::fromArray($a);
    })(...$args);
}

function arrayToSet(...$args)
{
    return curry(function (array $a) : Set {
        return Set::fromArray($a);
    })(...$args);
}

function arrayFromSet(...$args)
{
    return curry(function (Set $a) : array {
        return $a->toArray();
    })(...$args);
}

function arrayFromLinkedList(...$args)
{
    return curry(function (LinkedList $a) : array {
        return $a->toArray();
    })(...$args);
}

function arrayFromCollection(...$args)
{
    return curry(function (Collection $a) : array {
        return $a->toArray();
    })(...$args);
}
