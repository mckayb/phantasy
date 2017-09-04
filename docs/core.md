# Core Functions
Note: In these docs, you'll sometimes see functions included from `Phantasy\PHP`, these come from the [phantasy-php](https://github.com/mckayb/phantasy-php) package.

## identity ($x) (aliases: id)
### Usage
```php
use function Phantasy\Core\{identity, id};
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

## curry (callable $f)
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

function myCustomFunction($a, $b) {
    // Do something...
}

// You can also curry already defined functions, by just using
// the string representation.
$curried = curry('myCustomFunction');

// And call with with all of the parameters.
$curried('foo', 'bar');

// Or only a portion of them.
$fooCurried = $curried('foo');
$fooCurried('bar');
```

## curryN (int $n, callable $f)
### Usage
```php
use function Phantasy\Core\curryN;
```

### Description
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

## compose (...$fns)
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
use function Phantasy\PHP\{explode, implode, strtolower};

$data = [
  [ "title" => "Foo Bar" ],
  [ "title" => "Foo Baz" ]
];

$snakeCase = compose(
  implode('_'),
  map(strtolower()),
  explode(' ')
);

$snakeTitle = compose($snakeCase, prop('title'));
$getSnakeTitles = map($snakeTitle);
$getSnakeTitles($data);
// [ "foo_bar", "foo_baz" ]
```

## prop (string $s, $x)
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

## equals ($x, $y)
### Usage
```php
use function Phantasy\Core\equals;
```
### Description
Simply checks for equality between two variables.
### Examples
```php
use function Phantasy\Core\equals;
use function Phantasy\DataTypes\Maybe\{Just, Nothing};

equals(1, 1); // true
equals(true, false); // false
equals(Just(1), Just(2)); // false
equals(Just(1), Just(1)); // true
equals('foo', 'bar'); // false
```

## lte ($a, $b)
### Usage
```php
use function Phantasy\Core\lte;
```
### Description
Simply checks if `$a` is less than or equal to `$b`.
### Examples
```php
lte(1, 2); // true
lte(2, 1); // false

$lte2 = lte(2);
$lte2(1); // false
$lte2(3); // true
```

## of ($a, $x)
### Usage
```php
use function Phantasy\Core\of;
```
### Description
Calls the of function, assuming that function exists on `$a`.
### Examples
```php
of(Maybe::class, 2);
// Just(2)

$ofMaybe = of(Maybe::class);
$ofMaybe(2);
// Just(2)
```

## map (callable $f, $x) (aliases: fmap)
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

## ap ($fa, $a)
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

## chain (callable $f, $a) (aliases: bind, flatMap)
### Usage
```php
use function Phantasy\Core\chain;
```
### Description
A simple wrapper around the chain method found in a Monad.
Used when you have a Monad with a function that returns a Monad, and a Monad with a value, and you want carry the computation through the result of the function.
### Examples
```php
$a = Maybe::of(1);
chain(function ($x) {
    return Maybe::of($x + 1);
}, $a);
// Just(2)
```

## alt ($a, $b)
### Usage
```php
use function Phantasy\Core\alt;
```
### Description
Simply calls the alt function on `$a`.
If it doesn't exist it just returns `$a` || `$b`.
### Examples
```php
use function Phantasy\Core\alt;
use function Phantasy\DataTypes\Maybe\{Just, Nothing};

alt(false, true); // true
alt(false, false); // false
alt(Just(1), Nothing()); // Just(1)
alt(Nothing(), Nothing()); // Nothing()
alt(Nothing(), Just(2)); // Just(2)
```

## zero ($a)
### Usage
```php
use function Phantasy\Core\zero;
```
### Description
Simply calls the zero function on the class, if it exists.
### Examples
```php
use Phantasy\DataTypes\Maybe\Maybe;

zero(Maybe::class); // Nothing()
```

## contramap (callable $f, $x) (aliases: cmap)
### Usage
```php
use function Phantasy\Core\contramap;
```
### Description
A simple wrapper around the contramap method found in a Contravariant functor.
Useful for mapping over the inputs of a function, rather than the outputs of a functions.
Most common use case is when you have a type that is always the output of a function, but never really useful as an input to another function, such as functions generating Markdown such as HTML or JSX, or even JSON.
### Examples
```php
$f = function ($s) {
    return '<html>' . $s . '</html>';
};

$Comp = new class ($f) {
    private $f = null;

    public function __construct(callable $f)
    {
        $this->f = $f;
    }

    public function contramap(callable $g)
    {
        $f = $this->f;
        return new static(function ($x) use ($f, $g) {
            return $f($g($x));
        });
    }

    public function fold($s = null)
    {
        return call_user_func($this->f, $s);
    }
};

$a = contramap(function ($x) {
    return '<body><div>' . $x . '</div></body>';
}, $Comp);
$b = contramap(function ($x) {
    return $x["title"];
}, $a);

$html = $b->fold([ "title" => "Blue" ]),
// "<html><body><div>Blue</div></body></html>"
```

## bimap (callable $f, callable $g, $x)
### Usage
```php
use function Phantasy\Core\bimap;
```
### Description
A simple wrapper around the bimap method of a Bifunctor.
Simply calls the proper method on the object passed in.
### Examples
```php
$left = function ($x) {
  return $x - 1;
};

$right = function ($x) {
  return $x + 1;
};
$a = Either::of(1);
bimap($left, $right, $a);
// Right(0)
```

## sequence (string $className, $x)
### Usage
```php
use function Phantasy\Core\sequence;
```
### Description
Simply calls the sequence function if it exists.
This lets you effectively swap types of a variable.
For example, if `$a = A(B(1))`, `sequence(B::class, $a)`
would return `B(A(1))`;
### Examples
```php
use function Phantasy\Core\sequence;
use Phantasy\DataTypes\Either\Either;

$a = new Cons(new Right(1), new Nil());

sequence(Either::class, $a);
// Right(Cons(1, Nil()))

$sequenceEither = sequence(Either::class);
$sequenceEither($a);
// Right(Cons(1, Nil()))
```

## traverse (string $className, callable $f, $x)
### Usage
```php
use function Phantasy\Core\traverse;
```
### Description
Simply calls the traverse function on `$x`, if it exists.
This, like sequence, allows you to swap the types of a variable, but with the added condition that you can map over result with the function `$f` before it returns.
### Examples
```php
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};
use function Phantasy\DataTypes\Either\{Left, Right};
use function Phantasy\DataTypes\Maybe\Maybe;

$a = new Cons(new Right(1), new Nil());
$f = function ($x) {
    return $x->toMaybe();
};

traverse(Maybe::class, $f, $a);
// Just(Cons(1, Nil()))
```

## chainRec (callable $f, $x, $m)
### Usage
```php
use function Phantasy\Core\chainRec;
```
### Description
Calls the chainRec function on our class, if it exists.
This lets us handle a recursive style of calling, in a way
that the stack doesn't explode.
### Examples
```php
use Phantasy\DataTypes\Writer\Writer;
use function Phantasy\DataTypes\Writer\Writer;
use function Phantasy\Core\chainRec;

$f = function ($next, $done, $v) {
    return Writer(function () use ($next, $done, $v) {
        return [$v >= 10000 ? $done($v) : $next($v + 1), [$v]];
    });
};

list($val, $log) = chainRec($f, 0, Writer::class)->run();
// $log = [0, 1, 2, ... , 10000]
// $val = 10000 
```

## extend (callable $f, $w)
### Usage
```php
use function Phantasy\Core\extend;
```
### Description
Simply calls the extend function on our object, if it exists.
Returns null otherwise.
### Examples
```php
use Phantasy\DataTypes\Writer\Writer;

$checkNum = function ($x) {
    list($comp, $log) = $x;
    return $comp > 10 ? $comp - 10 : $comp + 5;
};

$res = extend($checkNum, Writer::of(12))->run();
// [2, []];
```

## extract ($w)
### Usage
```php
use function Phantasy\Core\extract;
```
### Description
Simply calls the extract function on our object, if it exists.
### Examples
```php
use function Phantasy\Core\extract;
use Phantasy\DataTypes\Writer\Writer;

extract(Writer::of(1)); // 1
```

## mjoin
### Usage
```php
use function Phantasy\Core\mjoin;
```
### Description
A simple wrapper around the join or mjoin method.
If you have an object that has either of those defined, it will simply
call those functions on that object.
### Examples
```php
$a = LinkedList::of(LinkedList::of(2));
mjoin($a);
// Cons(2, Nil)
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

## reduce (aliases: foldl)
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

## reduceRight (aliases: foldr)
### Usage
```php
use function Phantasy\Core\reduceRight;
```
### Description
Sometimes called foldr in other programming languages, reduceRight works the exact same as reduce, accumulating values over some object, but starts with the last item and works down to the first.
### Examples
```php
reduceRight(concat(), '', ['a', 'b', 'c']);
// 'cba'

reduceRight(function ($prev, $curr) {
  return $prev + $curr;
}, 0, [1, 2, 3]);
// 6
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
use function Phantasy\PHP\strtolower;

liftA(strtolower(), Maybe::of('Foo Bar'));
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
use function Phantasy\PHP\{explode, ucfirst, implode};

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
use function Phantasy\Core\{curry, compose, map, trace};
use function Phantasy\PHP\{implode, explode, ucfirst};

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
// Any(true);
```

## mempty
### Usage
```php
use function Phantasy\Core\mempty;
```
### Description
Returns the proper empty instance of the type representative passed in.
### Examples
```php
mempty('foo bar');
// ''

mempty(['foo', 'bar']);
// []

$obj = new class() {
    public function empty() {
        return 'test';
    }
};
mempty($obj);
// 'test'
```
## isTraversable
### Usage
```php
use function Phantasy\Core\isTraversable;
```
### Description
Returns if the given object is traversable or not.
### Examples
```php
isTraversable(true);
// false

isTraversable([1, 2, 3]);
// true
```
