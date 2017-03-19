<?php
namespace PHPFP\Core;

function identity($x) {
  return $x;
}

function curry($callable) {
  $ref = new \ReflectionFunction($callable);

  $recurseFunc = function () use ($ref, &$recurseFunc) {
    $args = func_get_args();
    if (func_num_args() === $ref->getNumberOfParameters()) {
      return $ref->invokeArgs($args);
    } else {
      return function() use ($args, &$recurseFunc) {
        return $recurseFunc(...array_merge($args, func_get_args()));
      };
    }
  };

  return $recurseFunc;
}

function compose(...$fns) {
  return array_reduce($fns, function($f, $g) {
    return function($x) use ($f, $g) {
      return $f($g($x));
    };
  }, 'PHPFP\Core\identity');
}

function prop($prop) {
  return function($x) use ($prop) {
    if (is_object($x)) {
      return $x->{$prop};
    } elseif (is_array($x)) {
      return $x[$prop];
    }
  };
}

function map() {
  $map = curry(function($f, $x) {
    $res = [];
    foreach ($x as $y) {
      $res[] = $f($y);
    }
    return $res;
  });
  return $map(...func_get_args());
}

function semigroupConcat($x, $y) {
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
