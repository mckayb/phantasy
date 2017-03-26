# Core Functions

## identity
### Usage
```php
// If you want to call the function directly.
use function PHPFP\Core\identity;

// If you want to pass the function as a callable.
use const PHPFP\Core\identity;
```

### Description
Simply returns whatever you pass into it. Useful when you need
to lift a value into a function, or when you just want to return the value without modifying it.

### Examples
```php
use PHPFP\DataTypes\Either\Either;
use function PHPFP\Core\identity;
use const PHPFP\Core\identity as id;

identity(1);
// 1

identity('foo');
// 'foo'

identity(function($x) { return $x; });
// function($x) { return $x; });

Either::fromNullable('foo')
  ->map(function($x) {
    return $x . 'bar';
  })
  ->fold(id, id);
// foobar
```

## curry
### Usage
`use function PHPFP\Core\curry;`

### Description
Currying is when you call a function with a number of arguments less than the number of required arguments.
It lets you create new functions by fixing parameters of already defined functions.
All of the functions in `PHPFP\Core` have been curried for you, eg. `map`, `filter`, `prop`, etc.


### Examples
```php
use function PHPFP\Core\curry;

$add = curry(function($a, $b) {
  return $a + $b;
});
// Use the parameters one at a time.
$add2 = $add(2);
$add2(3); // 5

// Or all at once.
$add(2, 3); // 5;
```

## compose
### Usage
`use function PHPFP\Core\compose`;

### Description
Composition lets you chain results of multiple functions into a single function.
It helps you to manage nesting and, when combined with currying, helps you create more complex functions by using simple functions as building blocks.

### Examples
```php
use function PHPFP\Core\{compose, prop, map, curry};

$data = [
  [ "title" => "Foo Bar" ],
  [ "title" => "Foo Baz" ]
];
$split = curry(function($sep, $str) {
  return explode($sep, $str);
});
$join = curry(function($sep, $arr) {
  return implode($sep, $arr);
});
$snakeCase = compose(
  $join('_'),
  map('strtolower'),
  $split(' '),
  prop('title')
);
$getSnakeTitles = map($snakeCase);
$getSnakeTitles($data);
// [ "foo_bar", "foo_baz" ]
```

## prop
### Usage
`use function PHPFP\Core\prop;`

### Description
A helper function for extracting named values out of an array or object.

### Examples
```php
use function PHPFP\Core\{map, prop};

$item = [ "name" => "Foo", "value" => 15 ];
// Call it all at once
prop('value', $item); // 15

// Or call it one parameter at a time.
$getValue = prop('value');
$getValue($item); // 15

// Especially useful for retrieving values from multiple items.
$data = [
  [ "name" => "Foo", "value" => 15 ],
  [ "name" => "Bar", "value" => 10 ]
];
$values = map(prop('values'), $data); // [15, 10]
$names = map(prop('name'), $data); // ["Foo", "Bar"]

// prop and map are both curried
$getValues = map(prop('values'));
$getNames = map(prop('names'));
$getValues($data); // [15, 10]
$getNames($data); // ["Foo", "Bar"]
```

## map
### Usage
`use function PHPFP\Core\map;`
### Description
### Examples

## filter
### Usage
`use function PHPFP\Core\filter;`
### Description
### Examples

## reduce
### Usage
`use function PHPFP\Core\reduce;`
### Description
### Examples

## semigroupConcat
### Usage
`use function PHPFP\Core\semigroupConcat;`
### Description
### Examples
