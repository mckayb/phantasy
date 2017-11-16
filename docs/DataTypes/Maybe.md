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

#### static fromFalse ($val) : Maybe
Checks the value that is passed in. If it's false, it returns a `Nothing()`,
otherwise it returns a `Just($val)`.
```php
$a = false;
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
#### fold (callable $f, callable $g) (aliases: cata)
Used to extract the value out of the `Maybe` context.
If it's a `Just`, it runs the right function `g` on our value.
```php
$nothingFunc = function () {
    return 0;
};
$justFunc = function ($x) {
    return $x + 1;
};
Maybe::of(1)->fold($nothingFunc, $justFunc);
// 2
```
If it's a `Nothing`, it runs the left function.
```php
$nothingFunc = function () {
    return 0;
};
$justFunc = function ($x) {
    return $x + 1;
};
Maybe::fromNullable(null)->fold($nothingFunc, $justFunc);
// 0
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

#### sequence (callable $of)
Useful in swapping the types of the object you are dealing with.

If the instance is a `Just`, it simply swaps the inner type (given by `$className`) with the outer type `Just`.
```php
Just(Right(1))->sequence(Either::of());
// Right(Just(1))
```
If the instance is a `Nothing`, it just wraps it in the
type given by `$className`.
```php
Nothing()->sequence(Either::of());
// Right(Nothing())
```

#### traverse (callable $of, callable $f)
Does the same as sequence, but lets you map over the
value with `$f` before the types get swapped.

If the instance is a `Just`, it calls the function
`$f` on the current value before wrapping it in the
type given by `$className`.
```php
Just(1)->traverse(Either::of(), function ($x) {
    return Right($x + 1);
});
// Right(Just(2))
```
If the instance is `Nothing`, it just wraps the
instance in the type given by `$className`.
```php
Nothing()->traverse(Either::of(), function ($x) {
    return Right($x + 1);
});
// Right(Nothing())
```

#### getOrElse ($d)
Used as an alternative to `fold`, for extracting a value
from our `Maybe` context.
If the instance is a `Just`, it just returns the value
that our instance is holding.
```php
Just('foo')->getOrElse('bar');
// 'foo'
```
If the instance is a `Nothing`, it returns the parameter
value.
```php
Nothing()->getOrElse('bar');
// 'bar'
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
