# Data Types
Note: In these docs, you'll sometimes see functions included from `Phantasy\PHP`, these come from the [phantasy-php](https://github.com/mckayb/phantasy-php) package.

## Maybe
### Usage
```php
use function Phantasy\DataTypes\Maybe\{Just, Nothing};
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
```
### Description
`Maybe` (called `Option` in some FP languages) is a type that consists of two subclasses: `Just` or `Nothing`.
It is the simplest way to create null-safe code and can be used in computing with variables that can possibly be null, or in returning values that may or may not exist.
### Methods
#### static of ($val) : Maybe
Simply creates a `Just ($val)`. Good for lifting values into the `Maybe` context, so that you can map, apply or use other nice functions over it.
```php
$appendBar = function($x) {
    return $x . 'bar';
};
Maybe::of('foo')->map($appendBar);
// Equivalent to Just('foo')->map($appendBar);
```
#### static fromNullable ($val) : Maybe
Checks the value that is passed in. If it's null, it returns a `Nothing()`,
otherwise it returns a `Just($val)`.
```php
$a = null;
$b = 12;
Maybe::fromNullable($a); // Nothing()
Maybe::fromNullable($b); // Just(12)
```
#### static tryCatch (callable $f) : Maybe
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `Just`, with the value of the result of the function. If it throws an exception, it returns a `Nothing()`.
```php
// Throws exceptions
$connectToDB = function() {
    return new PDO(
        'MY_CONNECTION_STR',
        'MY_USERNAME',
        'MY_PASSWORD'
    );
};
Maybe::tryCatch($connectToDB);
// If an exception is thrown, it returns Nothing()
// If no exception was thrown, returns Just($connection)
```
#### static zero () : Maybe
Gives you the type-level empty instance for Maybe. You can think of this
like an empty method such as `[]` for arrays, `''` for strings, but now
we're dealing with the function level equivalent.
In Maybe's case, this is just `Nothing`.
```php
Maybe::zero();
// Nothing()

Maybe::zero()->map(function($x) {
    return $x + 1;
});
// Nothing()
```
#### equals (Maybe $m) : bool
Used to compare two `Maybe`'s for equality.
Two `Maybe`'s are equal if they are of the same type (Just or Nothing) and they contain the same value.
```php
use function Phantasy\DataTypes\Maybe\{Just, Nothing};

Just(1)->equals(Just(2)); // false
Just(1)->equals(Just(1)); // true
Just(1)->equals(Nothing()); // false
Nothing()->equals(Nothing()); // true
```
#### concat (Maybe $m) : Maybe
Used to concatencate two `Maybe`'s together.
If the instance is a `Nothing`, it just returns the parameter `$m`.
```php
Nothing()->concat(Nothing()); // Nothing()
Nothing()->concat(Just(1)); // Just(1)
```
If the instance is a `Just`, then it depends on the parameter `$m`.
If `$m` is `Nothing`, it just returns the instance `Just`.
```php
Just(1)->concat(Nothing()); // Just(1)
```
If `$m` is a `Just`, then it returns a `Just`, whose value is the concatenation
of each of the two `Just`'s values, assuming the concatenation makes sense.
(They have to each contain Semigroup's of the same type).
```php
Just([1])->concat(Just([2])); // Just([1, 2])
Just(true)->concat(Just(false)); // InvalidArgumentException is thrown
```
#### map (callable $f) : Maybe
Used when you want to transform the value of your `Maybe` instance.
If the instance is a `Just`, it performs the function `$f` on the value inside of our instance.
```php
Maybe::of(1)->map(function($x) {
    return $x + 1;
});
// Just(2)
```
If the instance is a `Nothing`, it ignores `$f` and just returns `Nothing`.
```php
Maybe::fromNullable(null)->map(function($x) {
    return $x + 1;
});
// Nothing()
```
#### ap (Maybe $m) : Maybe
Used when you want to apply a function inside of a `Maybe` to a value inside of a `Maybe`.
If the instance is a `Just`, then it takes the function inside `$m`, the parameter, and applies that function to our instance value.
```php
$a = Maybe::of(1);
$b = Maybe::of(function($x) {
    return $x + 1;
});
$a->ap($b);
// Just(2);
```
If the instance is a `Nothing`, then it just returns `Nothing`.
```php
$a = Maybe::fromNullable(null);
$b = Maybe::of(function($x) {
    return $x + 1;
});
$a->ap($b);
// Nothing()
```
#### chain (callable $f) : Maybe (aliases: bind, flatMap)
Used when you want to map with a function that also returns a `Maybe`.
If the instance is a `Just`, then it simply returns the result of the function `$f`.
```php
$json_str = '[]';
// Using map, this would return Just(Just([])),
// which can make it harder to work with.
// Chain flattens this so it becomes Just([])
Maybe::of($json_str)->chain(function($x) {
    return Maybe::fromNullable(json_decode($json_str, true));
});
// Just([])
```
If the instance is a `Nothing`, then it just returns `Nothing`, without executing the function.
```php
Maybe::fromNullable(null)->chain(function($x) {
    return Maybe::of(1);
});
// Nothing()
```
#### extend (callable $f) : Maybe
Very similar to map, it's used to extend the computation
instead of passing the value through. So the callable `$f` actually takes in the current instance, rather than the value of the instance.

If the instance is a `Just`, it simply calls the
function `$f`, passing the current instance as the
parameter.
```php
Just(1)->extend(function ($x) {
    return $x->map(function ($y) {
        return $y + 1;
    })->getOrElse('Error');
});
// Just(2)
```
If the instance is `Nothing`, it just returns `Nothing`,
without calling the function `$f`.
```php
Nothing()->extend(function ($x) {
    return $x->map(function ($y) {
        return $y + 1;
    })->getOrElse('Error');
});
// Nothing()
```
#### fold ($d) (aliases: getOrElse)
Used to extract the value out of the `Maybe` context.
If it's a `Just`, it returns the value of our instance.
```php
Maybe::of(1)->fold(0);
// 1
```
If it's a `Nothing`, it simply returns the parameter `$d`.
```php
Maybe::fromNullable(null)->fold('foo');
// 'foo'
```
#### alt (Maybe $m) : Maybe
Allows you to provide an alternative value as a fallback if the current
instance computation fails. You can look at it as a type-level if/else
statement, or as a computation with a contingency plan if something goes
wrong.
If the instance is already a `Just`, it just returns the current
instance.
```php
Maybe::of(1)->alt(Maybe::of(2));
// Just(1);

Maybe::of(1)->alt(Nothing());
// Just(1);
```
If the instance is a `Nothing`, it returns the parameter instance.
```php
Nothing()->alt(Just(1));
// Just(1)

Nothing()->alt(Nothing());
// Nothing()
```
#### reduce (callable $f, $acc)
Used to get the result of a function applied to an instance value.
Pulls the computation out of the `Maybe` context and just returns the
result.
If the instance is a `Just`, it just returns the result of the reducing
function.
```php
Maybe::of(1)->reduce(function($carry, $val) {
    return $carry + $val;
}, 2);
// 3
```
If the instance is a `Nothing`, it just returns the accumulating value.
```php
Nothing()->reduce(function($carry, $val) {
    return $carry + 1;
}, 2);
// 2
```
#### sequence (string $className)
Useful in swapping the types of the object you are dealing with.

If the instance is a `Just`, it simply swaps the inner type (given by `$className`) with the outer type `Just`.
```php
Just(Right(1))->sequence(Either::class);
// Right(Just(1))
```
If the instance is a `Nothing`, it just wraps it in the
type given by `$className`.
```php
Nothing()->sequence(Either::class);
// Right(Nothing())
```
#### traverse (string $className, callable $f)
Does the same as sequence, but lets you map over the
value with `$f` before the types get swapped.

If the instance is a `Just`, it calls the function
`$f` on the current value before wrapping it in the
type given by `$className`.
```php
Just(1)->traverse(Either::class, function ($x) {
    return Right($x + 1);
});
// Right(Just(2))
```
If the instance is `Nothing`, it just wraps the
instance in the type given by `$className`.
```php
Nothing()->traverse(Either::class, function ($x) {
    return Right($x + 1);
});
// Right(Nothing())
```
#### toEither ($val) : Either
Used to transform a `Maybe` into an `Either` context.
If the instance is a `Just`, then it returns a `Right` containing that instance value.
```php
Maybe::of(1)->toEither(0);
// Right(1)
```
If the instance is a `Nothing`, then it returns a `Left` containing the parameter `$val`.
```php
Maybe::fromNullable(null)->toEither(0);
// Left(0)
```

#### toValidation ($val) : Validation
Used to transform a `Maybe` into a `Validation` context.
If the instance is a `Just`, then it returns a `Success` containing that instance value.
```php
Maybe::of(1)->toValidation(0);
// Success(1)
```
If the instance is a `Nothing`, then it returns a `Failure` containing the parameter `$val`.
```php
Maybe::fromNullable(null)->toValidation('No val set!');
// Failure('No val set!')
```

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
#### toValidation () : Maybe
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

## Validation
### Usage
```php
use function Phantasy\DataTypes\Validation\{Success, Failure};
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};
````
### Description
`Validation` is a type that consists of two subclasses: `Success` or `Failure`.
It is used when a certain computation can fail in multiple ways, and you need to be able to keep track of all of the things that failed. It differs from Either in the fact that you can compose Validation Failures into a single Validation Failure.

### Methods
#### static of ($val) : Validation
Creates a `Success($val)`.
Useful for lifting values into the `Validation` context.
```php
Validation::of(2);
// Success(2)
```
#### static fromNullable ($failVal, $val) : Validation
Checks the value that is passed in. If it's null, it returns a `Failure($failVal)`,
otherwise it returns a `Success($val)`.
```php
$a = null;
$b = 12;
Validation::fromNullable(0, $a); // Failure(0)
Validation::fromNullable($b); // Success(12)
```
#### static tryCatch (callable $f) : Validation
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `Success`, with the value of the result of the function. If it throws an exception, it returns a `Failure` with the result of the exception.
```php
// Throws exceptions
$connectToDB = function() {
    return new PDO(
        'MY_CONNECTION_STR',
        'MY_USERNAME',
        'MY_PASSWORD'
    );
};
Validation::tryCatch($connectToDB);
// If an exception is thrown, it returns Failure(Exception('E_CONN_REFUSED'))
// or whatever exception.
// If no exception was thrown, returns Success($connection).
```
#### static zero () : Validation
Gives you the type-level empty instance for Validation. You can think of this
like an empty method such as `[]` for arrays, `''` for strings, but now
we're dealing with the function level equivalent.
In Validation's case, this is just `Failure`.
In my implementation, I chose to have it return `Failure([])`, assuming
that arrays will be the most common use of Validation Failure
collection.
```php
Validation::zero();
// Failure([])

Validation::zero()->map(function($x) {
    return $x + 1;
});
// Failure([])
```
#### equals (Validation $v) : bool
Used to compare two `Validation`'s for equality.
Two `Validation`'s are equal if they are of the same type (Success or Failure) and they contain the same value.
```php
use function Phantasy\DataTypes\Validation\{Success, Failure};

Success(1)->equals(Success(2)); // false
Success(1)->equals(Success(1)); // true
Success('foo')->equals(Failure('foo')); // false
Failure('foo')->equals(Failure('foo')); // true
```
#### map (callable $f) : Validation
Used to transform the value inside of our `Validation`.
Applies the function `$f` to the value inside of our instance.
If the instance is a `Success`, it returns a `Success` with the result of the function.
```php
Validation::of(1)->map(function($x) {
    return $x + 1;
});
// Success(2)
```
If the instance is a `Failure`, it just returns the instance.
```php
Failure('There was a problem')->map(function($x) {
    return $x + 1;
});
// Failure('There was a problem')
```
#### ap (Validation $v) : Validation
Used when you have a `Validation` with a function, and a `Validation` with a value, and we want to apply that function to our value.
If the instance is a `Success` it applies the function inside of `$v` to our instance.
```php
$a = Validation::of(1);
$b = Validation::of(function($x) {
    return $x + 1;
});
$c = $a->ap($b);
// Success(2)
```
If the instance is a `Failure` it just returns the instance.
```php
$a = Validation::fromNullable('No val set!', null);
$b = Validation::of(function($x) {
    return $x + 1;
});
$a->ap($b);
// Failure('No val set!');
```
#### fold (callable $f, callable $g) (aliases: cata)
Used to extract values from a `Validation` context.
If the instance is a `Success`, it returns the result of the right function `$g`.
```php
Validation::of(1)->fold(function($x) {
    return $x;
}, function($x) {
    return $x + 1;
});
// 2
```
If the instance is a `Failure`, it returns the result of the left function `$f`.
```php
Failure(['First problem'])->fold(
    function($e) {
        return $e;
    },
    function($x) {
        return 'Success';
    }
);
// ['First problem']
```
#### bimap (callable $f, callable $g) : Validation
Used to map over the value in our instance, dependent on if the instance is a `Success`, or a `Failure`.
If the instance is a `Success`, it calls the right function `$g`.
```php
Validation::of(1)->bimap(
    function($x) {
        return $x - 1;
    },
    function($x) {
        return $x + 1;
    }
);
// Success(2);
```
If the instance is a `Failure`, it calls the left function `$f`.
```php
Failure(12)->bimap(
    function($x) {
        return $x - 1;
    },
    function($x) {
        return $x + 1;
    }
);
// Failure(11)
```
```php
use Phantasy\DataTypes\Validation\Validation;
use function Phantasy\Core\identity;

Validation::tryCatch(function() {
    throw new Exception('Something went wrong!');
})->bimap(function ($e) {
    return $e->getMessage();
}, identity();
// 'Something went wrong!'
```
#### concat (Validation $v) : Validation
Used as a way of composing Validations.
If the instance is a `Success`, it just returns the parameter Validation.
```php
$a = Validation::of(1);
$b = Validation::of(2);
$c = $a->concat($b);
// Success(2)
```
```php
$a = Validation::of(1);
$b = Failure(['There was some problem.']);
$c = $a->concat($b);
// Failure(['There was some problem.'])
```
If the instance is a `Failure`, then it depends on the parameter.
If the parameter is a `Success`, it just returns the instance.
```php
$a = Validation::fromNullable('No val is set!', null);
$b = Validation::of(12);
$c = $a->concat($b);
// Failure('No val is set!')
```
If the parameter is a `Failure`, then it returns a Failure with a value of the concatenation of the instance value and the parameter value (assuming the concatenation makes sense).
```php
$a = Failure(['There was a problem.']);
$b = Failure(['There was another problem.']);
$c = $a->concat($b);
// Failure(['There was a problem.', 'There was another problem.']);
```
#### alt (Validation $m) : Validation
Allows you to provide an alternative value as a fallback if the current
instance computation fails. You can look at it as a type-level if/else
statement, or as a computation with a contingency plan if something goes
wrong.
If the instance is already a `Success`, it just returns the current
instance.
```php
Success(1)->alt(Success(2));
// Success(1);

Success(1)->alt(Failure(['Something went wrong...'));
// Success(1);
```
If the instance is a `Failure`, it returns the parameter instance.
```php
Failure(['Something went wrong!'])->alt(Success(1));
// Success(1)

Failure(['Something went wrong!])->alt(Failure(['Something else went
wrong!']);
// Failure(['Something else went wrong!'])
```
#### reduce (callable $f, $acc)
Used to get the result of a function applied to an instance value.
Pulls the computation out of the `Validation` context and just returns the
result.
If the instance is a `Success`, it just returns the result of the reducing
function.
```php
Validation::of(1)->reduce(function($carry, $val) {
    return $carry + $val;
}, 2);
// 3
```
If the instance is a `Failure`, it just returns the accumulating value.
```php
Failure(12)->reduce(function($carry, $val) {
    return $carry + 1;
}, 2);
// 2
```
#### toEither () : Either
Used to move from a `Validation` context to an `Either` context.
If the instance is a `Success`, it returns a `Right` with the same value.
```php
Validation::of(1)->toEither();
// Right(1)
```
If the instance is a `Failure`, it returns a `Left` with the same value.
```php
Failure('foobar')->toEither();
// Left('foobar')
```
#### toMaybe () : Maybe
Used to move from a `Validation` context to a `Maybe` context.
If the instance is a `Success`, it returns a `Just` with the same value.
```php
Validation::of(1)->toMaybe();
// Just(1)
```
If the instance is a `Failure`, it returns a `Nothing`.
```php
Failure('foo')->toMaybe();
// Nothing()
```
## Reader
### Usage
```php
use function Phantasy\DataTypes\Reader\Reader;
use Phantasy\DataTypes\Reader\Reader;
```
### Description
The Reader type is essentially just a way of composing functions where
each of the functions have access to some external state.
It helps to think of the Reader type as just a wrapper that holds a function with some extra
properties.
### Methods
#### static of ($x) : Reader
Creates a `Reader` with a function that just returns the parameter
value `$x`. The difference between using `Reader::of` and just calling
the helper function `Reader` is that `Reader::of` creates a function
returning whatever was passed in, while `Reader` just takes the parameter function as it's function, without the extra level of nesting.
```php
Reader::of(12)->run([]);
// 12
```
#### run ($s)
Simply runs the function that the `Reader` is holding with some state
`$s`. This is the state that will be referenced during the computation
that the `Reader` is performing.
#### map (callable $g) : Reader
This is the simplest way of composing new functions with the function
that is already stored inside the `Reader`. Simply composes the function
inside the `Reader` and the parameter function `$g`.
```php
use Phantasy\DataTypes\Reader\Reader;
use function Phantasy\Core\concat;

Reader::of('hello')->map(function($x) {
    return concat($x, ' World!');
})->run([]);
// 'Hello World!'
```
#### ap (Reader $g) : Reader
Used when you want to apply a function inside of a `Reader` to a value
inside of a `Reader`.
```php
Reader::of(12)->ap(Reader::of(function($x) {
    return $x + 1;
}))->run([]);
// 13
```
#### chain (callable $f) : Reader (aliases: bind, flatMap)
Used when you want to map with a function that also returns a `Reader`.
The `Reader` inside of the chain method has access to the external state
that was passed in.
```php
use Phantasy\DataTypes\Reader\Reader;
use function Phantasy\Core\concat;

$r = Reader::of('Hello')
    ->chain(function($x) {
        return Reader(function($s) use ($x) {
            return $s['ENVIRONMENT'] === 'production'
                ? concat($x, ' Customer!')
                : concat($x, ' Dev!');
        });
    });
$r->run([ 'ENVIRONMENT' => 'production' ]);
// Hello Customer!

$r->run([ 'ENVIRONMENT' => 'development' ]);
// Hello Dev!'
```
#### extend (callable $f) : Reader
Essentially let's you run the reader with an alternate
or slightly modifed state in the middle of your computation.
```php
Reader::of('The name is ')
    ->chain(function ($x) {
        return Reader(function ($s) use ($x) {
            return concat($x, $s['name']);
        });
    })
    ->extend(function (Reader $r) {
        return $r->run([ 'name' => 'Jim' ]);
    })
    ->run([ 'name' => 'Joe' ]);
// The name is Jim
```
#### extract ($m = [])
Runs the `Reader` monad with the empty method
of our environment. This defaults to an empty array,
but you can pass in a different Monoid if you'd like,
as long as it has an empty method that makes sense.
```php
Reader::of('foo')->extract();
// foo
```
## Writer
### Usage
```php
use function Phantasy\DataTypes\Writer\Writer;
use Phantasy\DataTypes\Writer\Writer;
```
### Description
The `Writer` type, similar to the `Reader` type, also encapsulates function composition, but instead of reading from some environment state, it allows you to write to some state.
The most common use case allows you to keep a log as you go through your function composition.
### Methods
#### static of ($x, $m = []) : Writer
Creates a `Writer` with a function that just returns the parameter
value `$x`. The difference between using `Writer::of` and just calling
the helper function `Writer` is that `Writer::of` creates a function
returning whatever was passed in, while `Writer` just takes the parameter
function as it's function, without the extra level of nesting.
```php
Writer::of('foo')->run();
// ['foo', []]

Writer::of('foobar', 'test')->run();
// ['foobar', '']
```
#### static chainRec(callable $f, $i, $m = []) : Writer
Used as an abstraction to get around exploding the call stack during complicated calculations.
Takes a function which has three parameters, `$next`, `$done`, and `$val`. This lets us simulate recursion, but is implemented in a way that will not blow the stack.
```php
use function Phantasy\DataTypes\Writer\Writer;
use Phantasy\DataTypes\Writer\Writer;

list($val, $log) = Writer::chainRec(function ($next, $done, $x) {
    return Writer(function () use ($next, $done, $x) {
        return [$x >= 10000 ? $done($x) : $next($x + 1), [$x]];
    });
}, 0)->run();
// $log = [0, 1, 2, ... , 10000]
// $val = [0, 1, 2, ... , 10000]
```
#### run ($s)
Runs the function that the current `Writer` instance is holding.
This will return you an array with the first value as the result of the computation and the second value as the resulting log at the end of the computation.
```php
Writer::of('foo')->run();
// ['foo', []]

Writer::of('foobar', 'test')->run();
// ['foobar', '']
```
#### map (callable $g) : Writer
Used to transform the value that will be returned by the `Writer`.
Simply runs the parameter function `$g` on the current value inside the `Writer`. This does not affect the log value.
```php
Writer::of('foo')->map(function($x) {
    return $x . 'bar';
})->run();
// ['foobar', []]
```
#### ap (Writer $g) : Writer
Used when you have a `Writer` whose computation value is a function, and you want to apply that function to the value inside of a different `Writer`. It also concatenates the log values of the two `Writer` instances.
```php
$a = Writer::of('foo');
$b = Writer::of(function($x) {
    return $x . 'bar';
});

$a->ap($b)->run();
// ['foobar', []]
```
#### chain (callable $f) : Writer (aliases: bind, flatMap)
Used when you want to map with a function that returns a `Writer`. The computation value becomes the result of the parameter function `$f`, while the log value is the result of combining the current instance with the returned `Writer`.
```php
Writer::of('foo')->chain(function($x) {
    return Writer(function() use ($x) {
        return [$x . 'bar', ['Bar Stuff']];
    });
});
// ['foobar', ['Bar Stuff']]
```
#### extend (callable $f) : Writer
Extend is essentially a map-like function over your Writer type, but with read access to the log val as well!
To emphasize this type, we're gonna create a small game.
```php
// Let's first define a Sum type to keep track
// of our hero's hunger.
 $Sum = function ($x) {
    return new class ($x) {
        public $x;
        public function __construct($x)
        {
            $this->x = $x;
        }

        public function concat($a)
        {
            return new static($this->x + $a->x);
        }

        public static function empty()
        {
            return new static(0);
        }
    };
};

// We'll start with our hero Jerry.
$exampleHero = [
    'name' => 'Jerry',
    'isHungry' => false
];

// An adventurer is just a hero, with
// his/her associated hunger.
$adventurer = function ($hero, $hunger) {
    return Writer(function () use ($hero, $hunger) {
        return [$hero, $hunger];
    });
};

// Slaying a dragon is hard work, and
// causes the hero's hunger to go up.
$slayDragon = function ($hero) use ($Sum, $adventurer) {
    return $adventurer($hero, $Sum(100));
};

// Running from the dragon also causes
// the hunger to go up, but by less
// than actually slaying it.
$runFromDragon = function ($hero) use ($Sum, $adventurer) {
    return $adventurer($hero, $Sum(50));
};

// Eating reduces our hunger!
$eat = function ($hero) use ($adventurer, $Sum) {
    return $hero['isHungry']
        ? $adventurer(array_merge($hero, ['isHungry' => false]), $Sum(-100))
        : $adventurer($hero, $Sum(0));
};

// This the function we care about.
// It takes an adventurer, and checks it's
// hunger val (the log val of our Writer)
// and uses that to affect our hero
// (the computation val of our Writer)
$areWeHungry = function ($adv) {
    list($hero, $hunger) = $adv->run();
    return $hunger->x > 200
        ? array_merge($hero, ['isHungry' => true])
        : $hero;
};

// We start battling!
// We first slay the dragon, then check
// if we're hungry. We then eat.
$battle1 = $slayDragon($exampleHero)->extend($areWeHungry)->chain($eat);
/*
$battle1->run();
[
    ['name' => 'Jerry', 'isHungry' => false],
    $Sum(100)
]
*/

// After the first battle, we slay another
// dragon and eat.
$battle2 = $battle1->chain($slayDragon)->extend($areWeHungry)->chain($eat);
/*
$battle2->run();
[
    ['name' => 'Jerry', 'isHungry' => false],
    $Sum(200)
]
*/

// After the second battle, we run from a
// dragon and eat.
$battle3 = $battle2->chain($runFromDragon)->extend($areWeHungry)->chain($eat);
/*
$battle3->run();
[
    ['name' => 'Jerry', 'isHungry' => false],
    $Sum(150)
]
*/

// Finally, after the third battle, we slay
// another dragon, and check if we're hungry.
$battle4 = $battle3->chain($slayDragon)->extend($areWeHungry);
/*
$battle4->run();
[
    ['name' => 'Jerry', 'isHungry' => true],
    $Sum(250)
]
*/
```
#### extract ()
Simply pulls the value out of the writer. It only pulls the computation value, not the log value.
```php
Writer::of(1)->extract();
// 1

Writer::of('foo')->map(function ($x) {
    return $x . 'bar';
})->extract();
// 'foobar'
```
## State
### Usage
```php
use function Phantasy\DataTypes\State\State;
use Phantasy\DataTypes\State\State;
```
### Description
You can think of the `State` type as a combination of the `Reader` and `Writer` types. With `Reader`, we had a purely functional way to read from some state for a computation. With `Writer` we had a way of writing to some state during a computation. With `State`, we get both.
The `State` holds 2 objects, the first being the value of the computation and the second being the current state the computation is managing.
### Methods
#### static of ($x) : State
```php
State::of('foo')->run('bar');
// ['foo', 'bar']

State::of('foobar')->run('bar');
// ['foobar', 'bar']
```
#### run ($s)
Runs the function that the current `State` instance is holding.
This will return you an array with the first value as the result of the computation and the second value as the current state the computation is managing.
```php
State::of('foo')->run(['title' => 'Foo Bar']);
// ['foo', ['title' => 'Foo Bar']]

State::of('foobar')->run(12);
// ['foobar', 12]
```
#### map (callable $g) : State
Used to transform the value that will be returned by the `State`.
Simply runs the parameter function `$g` on the current value inside the `State`. This does not affect the state, just the value.
```php
State::of('foo')->map(function($x) {
    return $x . 'bar';
})->run([]);
// ['foobar', []]
```
#### ap (State $g) : State
Used when you have a `State` whose computation value is a function, and you want to apply that function to the value inside of a different `State`. This does not affect the state value.
```php
$a = State::of('foo');
$b = State::of(function($x) {
    return $x . 'bar';
});

$a->ap($b)->run(15);
// ['foobar', 15]
```
#### chain (callable $f) : State (aliases: bind, flatMap)
Used when you want to map with a function that returns a `State`. The computation value becomes the result of the parameter function `$f`, while the state value is accessible to be read and changed.
```php
State::of('foo')->chain(function($x) {
    return State(function($s) use ($x) {
        return [$x . 'bar', $s . ' Stuff'];
    });
})->run('Bar');
// ['foobar', 'Bar Stuff']
```
## Linked List
### Usage
```php
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};
use Phantasy\DataTypes\LinkedList\{LinkedList, Cons, Nil};
```
### Description
A purely functional linked list implementation.
### Methods
#### static fromArray (array $arr) : LinkedList
Used to convert a php array into a LinkedList, a List element whose
only elements are the head of the list, and the tail which is another
LinkedList.
```php
LinkedList::fromArray([1, 2, 3]);
// Cons(1, Cons(2, Cons(3, Nil)))
```
#### static of ($x) : LinkedList
Simply creates a `Cons($x, Nil())`, a LinkedList whose only element is `$x`.
```php
LinkedList::of(1);
// Cons(1, Nil)
```
#### static empty () : LinkedList
Creates the empty element for a LinkedList, simply `Nil()`.
```php
LinkedList::empty();
// Nil()
```
#### equals (LinkedList $l) : bool
Used to compare two `LinkedList`'s for equality.
Two `LinkedList`'s are equal if they are of the same type (Cons or Nil) and they contain the same values.
```php
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};

Cons(1, Nil())->equals(Cons(2, Nil())); // false
Cons(1, Nil())->equals(Cons(1, Nil())); // true
Cons(1, Nil())->equals(Nil()); // false
Nil()->equals(Nil()); // true
```

#### map (callable $f) : LinkedList
Used when you want to run a transformation over all of the values of
your list.
If the instance is a `Cons`, it keeps running the transformation down the list until
it hits `Nil`, giving you a new list.
```php
LinkedList::fromArray([1, 2])->map(function ($x) {
    return $x + 1;
});
// Cons(2, Cons(3, Nil))
```
If the instance is a `Nil`, it ignores $f and simply returns `Nil`.
```php
Nil()->map(function($x) {
    return $x + 1;
});
// Nil
```
#### ap (LinkedList $c) : LinkedList
Used when you have a LinkedList of functions that you want to apply to a
LinkedList of values.
If the instance is a `Cons`, it runs each function in `$c` over each value
and append each result to a new list.
```php
$a = LinkedList::fromArray(['A', 'B']);
$b = LinkedList::fromArray([
    function ($x) {
        return 'foo' . $x;
    },
    function ($x) {
        return $x . '!';
    }
]);
$a->ap($b);
// Cons('fooA', Cons('fooB', Cons('A!', Cons('B!', Nil))))
```
If the instance is a `Nil`, it ignores `$c` and just returns `Nil`.
```php
$a = Nil();
$b = LinkedList::fromArray([
    function ($x) {
        return 'foo' . $x;
    },
    function ($x) {
        return $x . '!';
    }
]);
$a->ap($b);
// Nil
```
#### chain (callable $f) : LinkedList
Used when you have a function that returns a LinkedList.
If the instance is a `Cons`, it calls the
function on each of the values in the current LinkedList and then
flattens the results into a single LinkedList.
```php
$a = LinkedList::of(2)->chain(function($x) {
    return LinkedList::of($x + 1);
});
// Cons(3, Nil)
```
If the instance is a `Nil`, it ignores `$f` and just returns `Nil`.
```php
Nil()->chain(function($x) {
    return LinkedList::of($x + 1);
});
// Nil
```
#### concat (LinkedList $c) : LinkedList
Used to concatenate two linked lists together.
```php
LinkedList::of(2)->concat(LinkedList::of(3));
// Cons(2, Cons(3, Nil))

Nil()->concat(LinkedList::of(3));
// Cons(3, Nil)
```
#### reduce (callable $f, $acc)
Similar to `array_reduce`, this takes in a transformation function `$f`,
and an accumulator `$acc`, runs `$f` on each value in the list, starting
with `$acc` and returns the accumulated result.
```php
LinkedList::fromArray([1, 2, 3])->reduce(function ($sum, $n) {
    return $sum + $n;
}, 5);
// 11
```
If the instance is `Nil`, it just returns the accumulator value.
```php
Nil()->reduce(function($acc, $x) {
    return $acc + $x;
}, 12);
// 12
```
#### join () : LinkedList
Simply flattens a nested LinkedList one level.
```php
LinkedList::of(LinkedList::of(2))->join();
// Cons(2, Nil)
```
If the instance was `Nil`, it just returns `Nil`.
```php
Nil()->join();
// Nil
```
#### sequence (string $className)
Used when you have types that you want to swap. For example, converting
a `LinkedList` of `Maybe` to a `Maybe` of a `LinkedList`.
If the instance is a `Cons`, then it simply swaps the types.
```php
use Phantasy\DataTypes\Either\Either;
use function Phantasy\DataTypes\Either\Right;
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};

$a = Cons(Right(1), Cons(Right(2), Nil()));
$a->sequence(Either::class);
// Right(Cons(1, Cons(2, Nil)))
```
If the instance is a `Nil`, then it just wraps it in the result of `$of`.
```php
use Phantasy\DataTypes\Either\Either;
use function Phantasy\DataTypes\LinkedList\Nil;

$a = Nil();
$a->sequence(Either::class);
// Right(Nil)
```
#### traverse (string $className, callable $f)
Used when you have types that you want to swap, but also apply a
transformation function. For example, converting
a `LinkedList` of `Maybe` to a `Maybe` of a `LinkedList`.
If the instance is a `Cons`, then it simply swaps the types.
```php
use Phantasy\DataTypes\Either\Either;
use function Phantasy\DataTypes\Either\{Left, Right};
use function Phantasy\DataTypes\LinkedList\{Cons, Nil};

$a = Cons(0, Cons(1, Cons(2, Cons(3, Nil()))));
$toChar = function($n) {
    return $n < 0 || $n > 25
        ? Left($n . ' is out of bounds!')
        : Right(chr(833 + $n));
};
$a->traverse(Either::class, $toChar);
// Right(Cons('A', Cons('B', Cons('C', Cons('D', Nil)))))
```
If the instance is a `Nil`, then it just wraps it in the result of `$of`.
```php
use Phantasy\DataTypes\Either\{Either};
use function Phantasy\DataTypes\LinkedList\Nil;
use function Phantasy\Core\identity;

$a = Nil();
$a->traverse(Either::class, identity());
// Right(Nil)
```
