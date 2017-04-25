# Core Functions

## identity
### Usage
```php
use function Phantasy\Core\identity;
```

### Description
Simply returns whatever you pass into it. Useful when you need
to lift a value into a function, or when you just want to return the value without modifying it.

### Examples
```php
use Phantasy\DataTypes\Either\Either;
use function Phantasy\Core\identity;

identity(1);
// 1

identity('foo');
// 'foo'

identity(function($x) { return $x; });
// function($x) { return $x; });

Either::fromNullable('baz', 'foo')
  ->map(function($x) {
    return $x . 'bar';
  })
  ->fold(identity(), identity());
// foobar
```

## curry
### Usage
```php
use function Phantasy\Core\curry;
```

### Description
Currying is when you call a function with a number of arguments less than the number of required arguments.
It lets you create new functions by fixing parameters of already defined functions.
All of the functions in `Phantasy\Core` have been curried for you, eg. `map`, `filter`, `prop`, etc.


### Examples
```php
use function Phantasy\Core\curry;

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
use function Phantasy\Core\curry;
// You can also curry php's internal methods.
$explode = curry('explode');
$explodeBySpace = $explode(' ');
$explodeBySpace('foo bar');
// ['foo', 'bar']
```

## curryN
### Usage
```php
use function Phantasy\Core\curryN;
```

### Descripion
Used when you want to curry a function, but it has variadic arguments,
or it has optional parameters that you want to be included in the
currying.

### Examples
```php
use function Phantasy\Core\curryN;

$addNums = function(...$args) {
    return array_reduce($args, function($sum, $n) {
        return $sum + $n;
    }, 0);
};

$add4Nums = curryN(4, $addNums);
$add2MoreNums = $add4Nums(1, 2);
$add1MoreNum = $add2MoreNums(3);
$result = $add1MoreNum(4);
// 10
```

## compose
### Usage
```php
use function Phantasy\Core\compose;
```

### Description
Composition lets you chain results of multiple functions into a single function.
It helps you to manage nesting and, when combined with currying, helps you create more complex functions by using simple functions as building blocks.

### Examples
```php
use function Phantasy\Core\{compose, prop, map, curry};

$data = [
  [ "title" => "Foo Bar" ],
  [ "title" => "Foo Baz" ]
];
// You can curry PHP's functions.
// Or just use the ones already curried for you in Phantasy\Core\PHP.
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
```php
use function Phantasy\Core\prop;
```

### Description
A helper function for extracting named values out of an array or object.

### Examples
```php
use function Phantasy\Core\{map, prop};

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
$getValues = map(prop('value'));
$getNames = map(prop('name'));
$getValues($data); // [15, 10]
$getNames($data); // ["Foo", "Bar"]
```

## map (aliases: fmap)
### Usage
```php
use function Phantasy\Core\map;
```
### Description
A simple wrapper around the map method. If the data passed in has a map method, it just calls that. Otherwise, it just iterates over the data and returns an array.
### Examples
```php
use function Phantasy\Core\map;
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
use function Phantasy\Core\map;
$box = new Box(1);
$add = function($x) {
  return $x + 1;
};
map($add, $box);
// Box(2)
```

## ap
### Usage
```php
use function Phantasy\Core\ap;
```
### Description
A simple wrapper around the ap method found in an Apply.
Used when you have an Applicative with a function, and an Applicative with a value, and you want to apply the function to the value.
### Examples
```php
use function Phantasy\Core\ap;
use Phantasy\DataTypes\Maybe\Maybe;

$mAdd = Maybe::of(function($x) {
    return $x + 1;
});
$m1 = Maybe::of(1);
ap($mAdd, $m1);
// Just(2)
```

## filter
### Usage
```php
use function Phantasy\Core\filter;
```
### Description
A simple wrapper around the filter method. If the data passed in has a filter method, it just calls that. Otherwise, it just iterates over the data and returns an array.
### Examples
```php
use function Phantasy\Core\filter;

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
use function Phantasy\Core\filter;
$isEven = function($x) {
  return $x % 2 === 0;
};
$box = new Box([1, 2, 3]);
filter($isEven, $box);
// Box([2]);
```

## reduce
### Usage
```php
use function Phantasy\Core\reduce;
```
### Description
A simple wrapper around the filter method. If the data passed in has a reduce method, it just calls that. Otherwise, it just iterates over the data and returns an array.
### Examples
```php
use function Phantasy\Core\reduce;
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
use function Phantasy\Core\reduce;
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

## liftA
### Usage
```php
use function Phantasy\Core\liftA;
```
### Description
Used to lift a function with one argument across an Applicative Functor.
In other words, you can use this to call a function with one argument, but use Applicatives as parameters!
### Examples
```php
use function Phantasy\Core\liftA;

liftA('strtolower', Maybe::of('Foo Bar'));
// Just('foo bar');
```

## liftA2
### Usage
```php
use function Phantasy\Core\liftA2;
```
### Description
Used to lift a function with two arguments across Applicative Functors.
In other words, you can use this to call a function with two arguments, but use Applicatives as parameters!
One drawback, you have to make sure to curry the function before passing it to liftA2.
### Examples
```php
use function Phantasy\Core\{curry, liftA2};

$add = function($a, $b) {
    return $a + $b;
};
liftA2(curry($add), Either::of(2), Either::of(5));
// Right(7)

liftA2(curry($add), Either::of(2), Either::fromNullable(null, null));
// Left(null)
```

## liftA3
### Usage
```php
use function Phantasy\Core\liftA3;
```
### Description
Used to lift a function with three arguments across Applicative Functors.
In other words, you can use this to call a function with three arguments, but use Applicatives as parameters!
One drawback, you have to make sure to curry the function before passing it to liftA3.
### Examples
```php
use Phantasy\DataTypes\Validation\Validation;
use function Phantasy\Core\{curry, liftA3};

function connectToDatabase($username, $password, $host) {
    // Connect to the DB somehow
    return new DB()->connect([
        'username' => $username,
        'password' => $password,
        'host' => $host
    ]);
}

liftA3(
    curry('connectToDatabase'),
    Validation::of($username),
    Validation::of($password),
    Validation::of($host)
);
// Success($connection)

liftA3(
    curry('connectToDatabase'),
    Validation::of($username),
    Validation::fromNullable(null, null),
    Validation::of($host)
);
// Failure(null)
```

## liftA4
### Usage
```
use function Phantasy\Core\liftA4;
```
### Description
Used to lift a function with four arguments across Applicative Functors.
In other words, you can use this to call a function with four arguments, but use Applicatives as parameters!
One drawback, you have to make sure to curry the function before passing it to liftA4.
### Examples
```php
use Phantasy\DataTypes\Validation\Validation;
use function Phantasy\Core\{curry, liftA4};

function connectToDatabase($username, $password, $host, $db_name) {
    // Connect to the DB somehow
    return new DB()->connect([
        'username' => $username,
        'password' => $password,
        'host' => $host,
        'database' => $db_name
    ]);
}

liftA4(
    curry('connectToDatabase'),
    // Assuming these are defined somewhere...
    Validation::of($username),
    Validation::of($password),
    Validation::of($host),
    Validation::of($database_name)
);
// Success($connection)

liftA4(
    curry('connectToDatabase'),
    Validation::of($username),
    Validation::fromNullable(null, null),
    Validation::of($host)
    Validation::fromNullable(null, null)
);
// Failure(null)
```


## liftA5
### Usage
```php
use function Phantasy\Core\liftA5;
```
### Description
Used to lift a function with five arguments across Applicative Functors.
In other words, you can use this to call a function with five arguments, but use Applicatives as parameters!
One drawback, you have to make sure to curry the function before passing it to liftA5.
### Examples
```php
use function Phantasy\Core\{curryN, liftA5};
use Phantasy\DataTypes\Maybe\Maybe;

function add(...$nums) {
    $sum = 0;
    foreach ($nums as $num) {
        $sum += $num;
    }
    return $sum;
}

// You can also use this with variadic arguments, using curryN.
// Also, note that liftA5 is also curried, so you can partially
// apply it's arguments as well.
$add5Applicatives = liftA5(curryN(5, 'add'));

$add5Applicatives(
    Maybe::of(1),
    Maybe::of(2),
    Maybe::of(3),
    Maybe::of(4),
    Maybe::of(5)
);
// Just(15)

$add5Applicatives(
    Maybe::of(1),
    Maybe::of(2),
    Maybe::fromNullable(null),
    Maybe::of(4),
    Maybe::fromNullable(null)
);
// Nothing()
```

## trace
### Usage
```php
use function Phantasy\Core\trace;
```
### Description
Used in debugging in the middle of arrow chaining, when you want
to view the actual value inside one of your types at a certain point.
### Examples
```php
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\Core\{curry, map, trace};
use function Phantasy\Core\PHP\{explode, ucfirst, implode};

function titleCase($str) {
    return Maybe::fromNullable($str)
        ->map(trace())
        ->map(explode(' '))
        ->map(trace())
        ->map(map(ucfirst()))
        ->map(trace())
        ->map(implode(''))
        ->fold('Invalid string');
}
echo titleCase('foo bar');
// Output:
// 'foo bar'
// ['foo', 'bar']
// ['Foo', 'Bar']
// 'FooBar'

echo titleCase(null);
// 'Invalid string'
```

```php
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\Core\{curry, compose, map, trace};
use function Phantasy\Core\PHP\{implode, explode, ucfirst};

$titleCase = compose(
    implode(' ')
    trace(),
    map(ucfirst()),
    trace(),
    explode(' '),
    trace()
);
echo $titleCase('foo bar');
// Output:
// 'foo bar'
// ['foo', 'bar']
// ['Foo', 'Bar']
// 'FooBar'
```


## concat (aliases: semigroupConcat)
### Usage
```php
use function Phantasy\Core\concat;
```
### Description
Simply concatenates two types together, if the concatenation makes sense,
or the objects have a concat method.
### Examples
```php
use function Phantasy\Core\concat;

concat([1, 2], [3, 4]);
// [1, 2, 3, 4]

concat('foo', 'bar');
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
use function Phantasy\Core\concat;

$a = new Any(true);
$b = new Any(false);
concat($a, $b);
// Any(false);
```

## Type
### Usage
```php
use function Phantasy\Core\Type;
```

### Description
Inspired heavily by [daggy](github.com/fantasy-land/daggy), this function lets you dynamically create a tagged constructor. The best way to learn this is through examples, so let's dive right in.

### Examples
```php
use function Phantasy\Core\Type;
$Car = Type('Car', ['make', 'model']);

// Define a new method for all Car instances
$Car->drive = function() {
    echo 'Zoom';
};

// Now create a representative of that type.
$suzukiAerio = $Car('Suzuki', 'Aerio');
// Each gets a tag property to reference what type you've created.
$suzukiAerio->tag; // 'Car'
$suzukiAerio->make; // 'Suzuki'
$suzukiAerio->model; // 'Aerio'
$suzukiAerio->drive();
// Output:
// Zoom

```
```php
use function Phantasy\Core\Type;
$Coordinate = Type('Coordinate', ['x', 'y', 'z']);

// Define a scaling operation for all of the points.
$Coordinate->scale = function($n) {
    // Creating a new version of the same
    // type is allowed as a property inside
    // of the functions.
    return $this->Coordinate(
        $this->x * $n,
        $this->y * $n,
        $this->z * $n
    );
};

$point = $Coordinate(1, 2, 3);
$point->x; // 1
$point->y; // 2
$point->z; // 3

$newPoint = $point->scale(2);
$newPoint->x; // 2
$newPoint->y; // 4
$newPoint->z; // 6
```

## SumType

### Usage
```php
use function Phantasy\Core\SumType
```

### Description
Inspired heavily by [daggy](github.com/fantasy-land/daggy), this function lets you dynamically create a new sum type, also known as union types. The best way to learn this is through examples, so let's dive right in.

### Examples
```php
use function Phantasy\Core\SumType;

$Option = SumType('Option', [
    'Some' => ['x'],
    'None' => []
]);

// This creates two functions, $Option->Some, and $Option->None
// which take exactly as many arguments as you defined.
// So, $Option->Some has one parameter
// and $Option->None doesn't have any.

$a = $Option->Some(1);
$b = $Option->None();

// Next, we can define methods on our Option type.
$Option->map = function($f) {
    // A useful helper function for working with Sum Types.
    return $this->cata([
        'Some' => function ($x) use ($f) {
            return $this->Some($f($x));
        },
        'None' => function () {
            return $this->None();
        }
    ]);
};

// And all current or future instances of our Option type
// now have a map method!
$c = $a->map(function ($x) {
    return $x + 1;
});
// Some(2)

$d = $b->map(function($x) {
    return $x + 1;
});
// None()
```

```php
use function Phantasy\Core\{SumType, map};

$List = SumType('List', [
    'Cons' => ['head', 'tail'],
    'Nil' => []
]);

$List->map = function($f) {
    return $this->cata([
        'Cons' => function($head, $tail) use ($f) {
            return $this->Cons($f($head), map($f, $tail));
        },
        'Nil' => function() {
            return $this->Nil();
        }
    ]);
};

$List->toArray = function() {
    return $this->cata([
        'Cons' => function($head, $tail) {
            return array_merge([$head], $tail);
        },
        'Nil' => function() {
            return [];
        }
    ]);
};

$List->Cons(1, [2, 3])
    ->map(function($x) {
        return $x * 2;
    })
    ->toArray();
// [2, 4, 6]
```
