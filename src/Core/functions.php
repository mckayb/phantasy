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

function curryN($n, $callable)
{
    $curryN = curry(function($n, $callable) {
        $ref = new \ReflectionFunction($callable);

        $recurseFunc = function () use ($ref, &$recurseFunc, $n) {
            $args = func_get_args();
            if (func_num_args() === $n) {
                return $ref->invokeArgs($args);
            } else {
                return function() use ($args, &$recurseFunc) {
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
        'Phantasy\Core\identity');
}

// @codingStandardsIgnoreStart
const identity = 'Phantasy\Core\identity';
// @codingStandardsIgnoreEnd
function identity($x)
{
    return $x;
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

function trace($x)
{
    var_dump($x);
    return $x;
}

function map()
{
    $map = curry(
        function ($f, $x) {
            if (is_object($x) && method_exists($x, 'map')) {
                return $x->map($f);
            } else {
                $res = [];
                foreach ($x as $y) {
                    $res[] = $f($y);
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
    $ap = curry(function($fa, $a) {
        return $a->ap($fa);
    });

    return $ap(...func_get_args());
}

function semigroupConcat()
{
    $semigroupConcat = curry(function($x, $y) {
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

function liftA()
{
    $liftA = curry(function($f, $a) {
        return $a->map($f);
    });

    return $liftA(...func_get_args());
}

function liftA2()
{
    $liftA2 = curry(function($f, $a1, $a2) {
        return $a2->ap($a1->map($f));
    });

    return $liftA2(...func_get_args());
}

function liftA3()
{
    $liftA3 = curry(function($f, $a1, $a2, $a3) {
        return $a3->ap($a2->ap($a1->map($f)));
    });

    return $liftA3(...func_get_args());
}

function liftA4()
{
    $liftA4 = curry(function($f, $a1, $a2, $a3, $a4) {
        return $a4->ap($a3->ap($a2->ap($a1->map($f))));
    });

    return $liftA4(...func_get_args());
}

function liftA5()
{
    $liftA5 = curry(function($f, $a1, $a2, $a3, $a4, $a5) {
        return $a5->ap($a4->ap($a3->ap($a2->ap($a1->map($f)))));
    });

    return $liftA5(...func_get_args());
}

function Type()
{
    $Type = curry(function(string $tag, array $fields) {
        return new class($tag, $fields) {
            public function __construct($tag, $fields) {
                $this->tag = $tag;
                $this->fields = $fields;
            }

            public function __invoke(...$fieldValues) {
                if (count($this->fields) !== count($fieldValues)) {
                    throw new Exception(
                        'There are '
                        . count($this->fields)
                        . ' fields, but '
                        . count($fieldValues)
                        . ' were passed in!'
                    );
                }

                $tag = $this->tag;
                $this->$tag = function(...$fieldValues) {
                    $b = clone $this;

                    foreach ($b->fields as $i => $field) {
                        $b->$field = $fieldValues[$i];
                    }
                    $b->fieldValues = $fieldValues;

                    return $b;
                };

                foreach ($this->fields as $i => $field) {
                    $this->$field = $fieldValues[$i];
                }

                $this->fieldValues = $fieldValues;

                return $this;
            }

            public function __call($method, $args) {
                if (isset($this->$method) && is_callable($this->$method)) {
                    return $this->$method->call($this, ...$args);
                }
            }

            public function __toString() {
                return $this->tag .'(' . implode(', ', $this->fieldValues) . ')';
            }
        };
    });

    return $Type(...func_get_args());
}

function SumType()
{
    $SumType = curry(function(string $name, array $constructors) {
        return new class($name, $constructors) {
            public function __construct($name, $constructors)
            {
                $this->name = $name;
                $this->constructors = $constructors;

                // Create the new classes off of the constructors
                foreach ($constructors as $key => $fields) {
                    $self = $this;
                    $this->$key = function ($fieldValues) use ($key, $fields, $self) {
                        return new class($fields, $fieldValues, $key, $self)
                        {
                            public function __construct($fields, $fieldValues, $key, $parentCtx)
                            {
                                $this->fields = $fields;
                                $this->fieldValues = $fieldValues;
                                $this->parentCtx = $parentCtx;
                                $this->tag = $key;
                            }

                            public function __call($method, $arguments)
                            {
                                if (in_array($method, array_keys($this->parentCtx->constructors))) {
                                    return $this->parentCtx->$method->call($this->parentCtx, $arguments);
                                }

                                if (isset($this->parentCtx->$method) && is_callable($this->parentCtx->$method)) {
                                    return $this->parentCtx->$method->call($this, ...$arguments);
                                }
                            }

                            public function __toString()
                            {
                                return $this->parentCtx->name
                                    . '.' . $this->tag
                                    .'(' . implode(', ', $this->fieldValues) . ')';
                            }

                            public function cata($cases)
                            {
                                if (count($cases) !== count($this->parentCtx->constructors) && !isset($cases["_"])) {
                                    throw new \Exception('You didn\'t cover all of the cases!');
                                }

                                if (isset($cases[$this->tag]) && is_callable($cases[$this->tag])) {
                                    return $cases[$this->tag](...$this->fieldValues);
                                } elseif (isset($cases["_"]) && is_callable($cases["_"])) {
                                    return $cases["_"](...$this->fieldValues);
                                }

                                throw new \Exception('You didn\'t define a method for ' . $this->tag . '.');
                            }
                        };
                    };
                }
            }

            public function __call($method, $arguments)
            {
                if (isset($this->$method) && is_callable($this->$method)) {
                    return $this->$method->call($this, $arguments);
                }
            }

            public function __toString()
            {
                return $this->name;
            }
        };
    });

    return $SumType(...func_get_args());
}
