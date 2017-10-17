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

#### static fromFalse ($failVal, $val) : Validation
Checks the value that is passed in. If it's false, it returns a `Failure($failVal)`,
otherwise it returns a `Success($val)`.
```php
$a = false;
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

#### sequence (string $className)
Helpful in swapping the types of the object you are
working with.

If the instance is a `Success`, it swaps the inner type (given by `$className`) and the `Success`.
```php
Success(Just(1))->sequence(Maybe::class);
// Just(Right(1))
```
If the instance is a `Failure`, it just wraps the current
instance in the type defined by `$className`.
```php
Failure('Request Failed')->sequence(Maybe::class);
// Just(Left('Request Failed'))
```

#### traverse (string $className, callable $f)
Does the same as sequence, but lets you map over the
value with `$f` before the types get swapped.

If the instance is a `Right`, it calls the function
`$f` on the current value before wrapping it in the
type given by `$className`.
```php
Success(1)->traverse(Maybe::class, function ($x) {
    return Just($x + 1);
});
// Just(Success(2))
```
If the instance is a `Failure`, it behaves the same as `sequence, by just wrapping the current instance
in the new type.
```php
Failure('Request Failed')->traverse(Maybe::class, function ($x) {
    return $x + 1;
});
// Just(Failure('Request Failed'))
```

#### extend (callable $f) : Validation
Very similar to map, it's used to extend the computation
instead of passing the value through. So the callable `$f` actually takes in the current instance, rather than the value of the instance.

If the instance is a `Success`, it simply calls the
function `$f`, passing the current instance as the
parameter.
```php
Success(1)->extend(function ($x) {
    return $x->map(function ($y) {
        return $y + 1;
    })->fold(identity(), identity());
});
// Success(2)
```
If the instance is a `Failure`, it just returns the current instance without calling the function `$f`.
```php
Failure('Request failed!')->extend(function ($x) {
    return $x->map(function ($y) {
        return $y + 1;
    })->fold(identity(), identity());
});
// Failure('Request failed!')
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