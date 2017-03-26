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
```php
use function PHPFP\Core\curry;
// You can also curry php's internal methods.
$explode = curry('explode');
$explodeBySpace = $explode(' ');
$explodeBySpace('foo bar');
// ['foo', 'bar']
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
// You can curry PHP's functions.
$explode = curry('explode');
$implode = curry('implode');
$snakeCase = compose(
  $implode('_'),
  map('strtolower'),
  $explode(' '),
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
$values = map(prop('value'), $data); // [15, 10]
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
A simple wrapper around the map method. If the data passed in has a map method, it just calls that. Otherwise, it just iterates over the data and returns an array.
### Examples
```php
use function PHPFP\Core\map;
$add1 = function($x) {
  return $x + 1;
};

map($add1, [1, 2, 3]);
// [2, 3, 4]

// It's curried, so you can pass one argument at a time.
$add1 = map($add1);
$add1([1, 2, 3]);
// [2, 3, 4]
```
```php
// Box.php
class Box {
  private $item = null;

  public function __construct($item) {
    $this->item = $item;
  }

  public function map($f) {
    return new Box($f($this->item));
  }
};

// OtherFile.php
use function PHPFP\Core\map;
$box = new Box(1);
$add = function($x) {
  return $x + 1;
};
map($add, $box);
// Box(2)
```

## filter
### Usage
`use function PHPFP\Core\filter;`
### Description
A simple wrapper around the filter method. If the data passed in has a filter method, it just calls that. Otherwise, it just iterates over the data and returns an array.
### Examples
```php
use function PHPFP\Core\filter;

$isOdd = function($x) {
  return $x % 2 === 1;
};
filter($isOdd, [1, 2, 3]);
// [1, 3]

// Or call it through the curried version.
$getOddNums = filter($isOdd);
$getOddNums([1, 2, 3]);
// [1, 3]
```
```php
// Box.php
class Box {
  private $items = [];

  public function __construct($items) {
    $this->items = $items;
  }

  public function filter($f) {
    // Could also use the filter function here, e.g
    // return new Box(filter($f, $this->items));
    return new Box(array_filter($this->items, $f));
  }
};

// OtherFile.php
use function PHPFP\Core\filter;
$isEven = function($x) {
  return $x % 2 === 0;
};
$box = new Box([1, 2, 3]);
filter($isEven, $box);
// Box([2]);
```

## reduce
### Usage
`use function PHPFP\Core\reduce;`
### Description
A simple wrapper around the filter method. If the data passed in has a reduce method, it just calls that. Otherwise, it just iterates over the data and returns an array.
### Examples
```php
use function PHPFP\Core\reduce;
$total = function($sum, $x) {
  return $sum + $x;
};
// Use all the arguments
$sum = reduce($total, 0, [1, 2, 3, 4]);
// 10

// Or call it through the curried version
$getSum = reduce($total, 0);
$getSum([1, 2, 3, 4]);
// 10;
```
```php
// Box.php
class Box {
  private $items = [];

  public function __construct($items) {
    $this->items = $items;
  }

  public function reduce($f, $initial) {
    // Could also use the reduce function here, e.g
    // return new Box(reduce($f, $initial, $this->items));
    return new Box(array_reduce($this->items, $f, $initial));
  }
};

// OtherFile.php
use function PHPFP\Core\reduce;
$isEven = function($x) {
  return $x % 2 === 0;
};
$oddSum = function($sum, $x) use ($isEven) {
  if ($isEven($x)) {
    return $sum + $x;
  }
  return $sum;
};
$box = new Box([1, 2, 3]);
reduce($oddSum, 0, $box);
// Box([2]);
```

## semigroupConcat
### Usage
`use function PHPFP\Core\semigroupConcat;`
### Description
Simply concatenates two types together, if the concatenation makes sense,
or the objects have a concat method.
### Examples
```php
use function PHPFP\Core\semigroupConcat;

semigroupConcat([1, 2], [3, 4]);
// [1, 2, 3, 4]

semigroupConcat('foo', 'bar');
// foobar

// Any.php
class Any {
  public $bool = '';
  public function __construct($bool) {
    $this->bool = $bool;
  }

  public function concat($any) {
    return new Any($this->bool || $any->bool);
  }
}

// OtherFile.php
use function PHPFP\Core\semigroupConcat;

$a = new Any(true);
$b = new Any(false);
semigroupConcat($a, $b);
// Any(false);
```
