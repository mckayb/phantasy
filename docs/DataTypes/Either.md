## Either
### Usage
```php
use function Phantasy\DataTypes\Either\{Left, Right};
use Phantasy\DataTypes\Either\{Either, Left, Right};
```
### Description
`Either` is a type that consists of two subclasses: `Left` or `Right`.
It is most often used on computations that may fail, but where you want information about how it failed.
The key difference between `Either`  and `Maybe` is that `Left` can hold a value, whereas `Nothing` can't. This lets Either hold legitimate values for code branching, or contain information about failures.
### Methods
#### static of ($val) : Either
Creates a `Right($val)`;
```php
Either::of(1);
// Right(1)
```

#### static fromNullable ($failVal, $val) : Either
If `$val` is null, it creates a `Left($failVal)`, otherwise it creates a `Right($val)`.
```php
Either::fromNullable('left val', 'right val');
// Right('right val')

Either::fromNullable('left val', null);
// Left('left val')
```

#### static tryCatch (callable $f) : Either
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `Right($f())`, otherwise, it returns a `Left($exception)`.
```php
// Throws exceptions
$connectToDB = function() {
    return new PDO(
        'MY_CONNECTION_STR',
        'MY_USERNAME',
        'MY_PASSWORD'
    );
};
Either::tryCatch($connectToDB);
// If an exception is thrown, it could return
// Left(Exception('E_CONN_REFUSED')), or whatever exception
// was thrown.

// If no exception was thrown, returns Right($connection)
```

#### static zero () : Either
Gives you the type-level empty instance for Either. You can think of this
like an empty method such as `[]` for arrays, `''` for strings, but now
we're dealing with the function level equivalent.
In Either's case, this is just `Left`.
```php
Either::zero();
// Left(null)

Either::zero()->map(function($x) {
    return $x + 1;
});
// Left(null)
```

#### equals (Either $e) : bool
Used to compare two `Either`'s for equality.
Two `Eithers`'s are equal if they are of the same type (Left or Right) and they contain the same value.
```php
use function Phantasy\DataTypes\Either\{Left, Right};

Right(1)->equals(Right(2)); // false
Right(1)->equals(Right(1)); // true
Right(1)->equals(Left(1)); // false
Left(1)->equals(Left(1)); // true
```

#### concat (Either $e) : Either
Used to concatenate two `Either` values together.
If the instance is a `Left`, it just returns the
parameter instance `$e`.
```php
Left(2)->concat(Right(2)); // Right(2)
Left(2)->concat(Left(1)); // Left(2)
```
If the instance is a `Right`, it just returns the
current instance.
```php
Right(1)->concat(Left(1)); // Right(1)
Right(2)->concat(Right(1)); // Right(2)
```

#### map (callable $f) : Either
Used to transform the values inside of our Either instance.
If the instance is a `Right`, it applies the function `$f` and returns a `Right` containing the result of the function.
```php
Either::of(1)->map(function($x) {
    return $x + 1;
});
// Right(2)
```
If the instance is a `Left`, it just returns the instance without executing the function.
```php
Either::fromNullable('Val is null!', null)->map(function($x) {
    return ($x / 100) . '%';
});
// Left('Val is null!')
```

#### ap (Either $e) : Either
Used when you want to apply a function inside of an `Either` to a value inside of an `Either`.
If the instance is a `Right`, it applies the function inside of the parameter to the value inside of our instance.
```php
$a = Either::of(1);
$b = Either::of(function($x) {
    return $x + 1;
});
$a->ap($b);
// Right(2)
```
If the instance is a `Left`, it ignores the parameter and just returns the instance.
```php
use Phantasy\DataTypes\Either\Either;
use function Phantasy\PHP\strtolower;

$a = Either::fromNullable('Val is null!', null);
$b = Either::of(strtolower());
$a->ap($b);
// Left('Val is null!')
```

#### chain (callable $f) : Either (aliases: bind, flatMap)
Used when you want to map over your value, but where the mapping function returns an `Either` context.
If the instance is a `Right`, it simply returns the result of the function.
```php
$a = Either::of(1)->chain(function($x) {
    return $x >= 1 ? Right($x) : Left(1);
});
// Right(1)
```
If the instance is a `Left`, it just ignores the function and returns the current instance.
```php
$a = Either::fromNullable(0, null)->chain(function($x) {
    return $x >= 1 ? Right($x) : Left(1);
});
// Left(0)
```

#### extend (callable $f) : Either
Very similar to map, it's used to extend the computation
instead of passing the value through. So the callable `$f` actually takes in the current instance, rather than the value of the instance.

If the instance is a `Right`, it simply calls the
function `$f`, passing the current instance as the
parameter.
```php
Right(1)->extend(function ($x) {
    return $x->map(function ($y) {
        return $y + 1;
    })->fold(identity(), identity());
});
// Right(2)
```
If the instance is a `Left`, it just returns the current instance without calling the function `$f`.
```php
Left('Request failed!')->extend(function ($x) {
    return $x->map(function ($y) {
        return $y + 1;
    })->fold(identity(), identity());
});
// Left('Request failed!')
```

#### fold (callable $f, callable $g) (aliases: cata)
Used to extract values out of an `Either` context.
If the instance is a `Right`, it returns the result of the right function, `$g`.
```php
$a = Either::of('foo')->fold(
    function($e) {
        return 'Error';
    },
    function($x) {
        return $x . 'bar';
    }
);
// 'foobar'
```
If the instance is a `Left`, it returns the result of the left function `$f`.
```php
$a = Either::fromNullable(null, null)->fold(
    function($e) {
        return 'Error';
    },
    function($x) {
        return $x . 'bar';
    }
);
// 'Error'
```

#### bimap (callable $f, callable $g) : Either
Used to map over the value in our instance, dependent on if the instance is a `Right`, or a `Left`.
If the instance is a `Right`, it calls the right function `$g`.
```php
$a = Either::of('foo')->bimap(function($x) {
    return $x . 'baz';
}, function($x) {
    return $x . 'bar';
});
// Right('foobar')
```
If the instance is a `Left`, it calls the left function `$f`.
```php
$a = Left('foo')->bimap(function($x) {
    return $x . 'baz';
}, function($x) {
    return $x . 'bar';
});
// Left('foobaz')
```
```php
use Phantasy\DataTypes\Either\Either;
use function Phantasy\Core\identity;

Either::tryCatch(function() {
    throw new Exception('Something went wrong!');
})->bimap(function ($e) {
    return $e->getMessage();
}, identity();
// 'Something went wrong!'
```

#### alt (Either $m) : Either
Allows you to provide an alternative value as a fallback if the current
instance computation fails. You can look at it as a type-level if/else
statement, or as a computation with a contingency plan if something goes
wrong.
If the instance is already a `Right`, it just returns the current
instance.
```php
Right(1)->alt(Right(2));
// Right(1);

Right(1)->alt(Left(2));
// Right(1);
```
If the instance is a `Left`, it returns the parameter instance.
```php
Left(1)->alt(Right(2));
// Right(1)

Left(1)->alt(Left(2));
// Left(2)
```

#### reduce (callable $f, $acc)
Used to get the result of a function applied to an instance value.
Pulls the computation out of the `Either` context and just returns the
result.
If the instance is a `Right`, it just returns the result of the reducing
function.
```php
Either::of(1)->reduce(function($carry, $val) {
    return $carry + $val;
}, 2);
// 3
```
If the instance is a `Left`, it just returns the accumulating value.
```php
Left(12)->reduce(function($carry, $val) {
    return $carry + 1;
}, 2);
// 2
```

#### sequence (string $className)
Helpful in swapping the types of the object you are
working with.

If the instance is a `Right`, it swaps the inner type (given by `$className`) and the `Right`.
```php
Right(Just(1))->sequence(Maybe::class);
// Just(Right(1))
```
If the instance is a `Left`, it just wraps the current
instance in the type defined by `$className`.
```php
Left('Request Failed')->sequence(Maybe::class);
// Just(Left('Request Failed'))
```

#### traverse (string $className, callable $f)
Does the same as sequence, but lets you map over the
value with `$f` before the types get swapped.

If the instance is a `Right`, it calls the function
`$f` on the current value before wrapping it in the
type given by `$className`.
```php
Right(1)->traverse(Maybe::class, function ($x) {
    return Just($x + 1);
});
// Just(Right(2))
```
If the instance is a `Left`, it behaves the same as `sequence, by just wrapping the current instance
in the new type.
```php
Left('Request Failed')->traverse(Maybe::class, function ($x) {
    return $x + 1;
});
// Just(Left('Request Failed'))
```

#### toMaybe () : Maybe
Converts an `Either` into a `Maybe` context.
If the instance is a `Right`, it simply returns a `Just` with the same value.
```php
Either::of(1)->toMaybe();
// Just(1)
```
If the instance is a `Left`, it returns `Nothing`.
```php
Either::fromNullable(0, null)->toMaybe();
// Nothing()
```

#### toValidation () : Validation
Used to transform a `Either` into a `Validation` context.
If the instance is a `Right`, then it returns a `Success` containing that instance value.
```php
Either::of(1)->toValidation();
// Success(1)
```
If the instance is a `Left`, then it returns a `Failure` containing the parameter `$val`.
```php
Either::fromNullable('No val set!', null)->toValidation();
// Failure('No val set!')
```
