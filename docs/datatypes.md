# Data Types

## Maybe
### Usage
```php
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
```
### Description
`Maybe` (called `Option` in some FP languages) is a type that consists of two subclasses: `Just` or `Nothing`.
It is the simplest way to create null-safe code and can be used in computing with variables that can possibly be null, or in returning values that may or may not exist.
### Methods
#### static of ($val)
Simply creates a `Just ($val)`. Good for lifting values into the `Maybe` context, so that you can map, apply or use other nice functions over it.
```php
$appendBar = function($x) {
    return $x . 'bar';
};
Maybe::of('foo')->map($appendBar);
// Equivalent to Just('foo')->map($appendBar);
```
#### static fromNullable ($val)
Checks the value that is passed in. If it's null, it returns a `Nothing()`,
otherwise it returns a `Just($val)`.
```php
$a = null;
$b = 12;
Maybe::fromNullable($a); // Nothing()
Maybe::fromNullable($b); // Just(12)
```
#### static tryCatch ($f)
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `Just`, with the value of the result of the function. If it throws an exception, it returns a `Nothing()`.
```php
// Throws exceptions
$connectToDB = function() {
    return new PDO->connect(
        'MY_CONNECTION_STR',
        'MY_USERNAME',
        'MY_PASSWORD'
    );
};
Maybe::tryCatch($connectToDB);
// If an exception is thrown, it returns Nothing()
// If no exception was thrown, returns Just($connection)
```
#### static zero ()
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
#### map ($f)
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
#### ap (Maybe $m)
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
#### chain ($f) (aliases: bind, flatMap)
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
#### alt (Maybe $m)
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
#### reduce ($f, $acc)
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
#### toEither ($val)
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

#### toValidation ($val)
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
use Phantasy\DataTypes\Either\{Either, Left, Right};
```
### Description
`Either` is a type that consists of two subclasses: `Left` or `Right`.
It is most often used on computations that may fail, but where you want information about how it failed.
The key difference between `Either`  and `Maybe` is that `Left` can hold a value, whereas `Nothing` can't. This lets Either hold legitimate values for code branching, or contain information about failures.
### Methods
#### static of ($val)
Creates a `Right($val)`;
```php
Either::of(1);
// Right(1)
```
#### static fromNullable ($failVal, $val)
If `$val` is null, it creates a `Left($failVal)`, otherwise it creates a `Right($val)`.
```php
Either::fromNullable('left val', 'right val');
// Right('right val')

Either::fromNullable('left val', null);
// Left('left val')
```
#### static tryCatch ($f)
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `Right($f())`, otherwise, it returns a `Left($exception)`.
```php
// Throws exceptions
$connectToDB = function() {
    return new PDO->connect(
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
#### static zero ()
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
#### map ($f)
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
#### ap (Either $e)
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
use function Phantasy\Core\PHP\strtolower;

$a = Either::fromNullable('Val is null!', null);
$b = Either::of(strtolower());
$a->ap($b);
// Left('Val is null!')
```
#### chain ($f) (aliases: bind, flatMap)
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
#### fold (\$f, \$g) (aliases: cata)
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
#### bimap (\$f,  \$g)
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
#### alt (Either $m)
Allows you to provide an alternative value as a fallback if the current
instance computation fails. You can look at it as a type-level if/else
statement, or as a computation with a contingency plan if something goes
wrong.
If the instance is already a `Right`, it just returns the current
instance.
```php
Either::of(1)->alt(Either::of(2));
// Right(1);

Either::of(1)->alt(Left(2));
// Right(1);
```
If the instance is a `Left`, it returns the parameter instance.
```php
Left(1)->alt(Right(2));
// Right(1)

Left(1)->alt(Left(2));
// Left(2)
```
#### reduce ($f, $acc)
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
#### toMaybe ()
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
#### toValidation ()
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
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};
````
### Description
`Validation` is a type that consists of two subclasses: `Success` or `Failure`.
It is used when a certain computation can fail in multiple ways, and you need to be able to keep track of all of the things that failed. It differs from Either in the fact that you can compose Validation Failures into a single Validation Failure.

### Methods
#### static of ($val)
Creates a `Success($val)`.
Useful for lifting values into the `Validation` context.
```php
Validation::of(2);
// Success(2)
```
#### static fromNullable ($failVal, $val)
Checks the value that is passed in. If it's null, it returns a `Failure($failVal)`,
otherwise it returns a `Success($val)`.
```php
$a = null;
$b = 12;
Validation::fromNullable(0, $a); // Failure(0)
Validation::fromNullable($b); // Success(12)
```
#### static tryCatch ($f)
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `Success`, with the value of the result of the function. If it throws an exception, it returns a `Failure` with the result of the exception.
```php
// Throws exceptions
$connectToDB = function() {
    return new PDO->connect(
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
#### static zero ()
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
#### map ($f)
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
#### ap (Validation $v)
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
#### fold (\$f, \$g) (aliases: cata)
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
#### bimap (\$f, \$g)
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
#### concat (Validation $v)
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
#### alt (Validation $m)
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
#### reduce ($f, $acc)
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
#### toEither ()
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
#### toMaybe ()
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
use Phantasy\DataTypes\Reader\Reader;
```
### Description
The Reader type is essentially just a way of composing functions where
each of the functions have access to some external state.
It helps to think of the Reader type as just a wrapper that holds a function with some extra
properties.
### Methods
#### static of ($x)
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
#### map ($g)
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
#### ap (Reader $g)
Used when you want to apply a function inside of a `Reader` to a value
inside of a `Reader`.
```php
Reader::of(12)->ap(Reader::of(function($x) {
    return $x + 1;
}))->run([]);
// 13
```
#### chain ($r) (aliases: bind, flatMap)
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
## Writer
### Usage
```php
use Phantasy\DataTypes\Writer\Writer;
```
### Description
The `Writer` type, similarly to the `Reader` type also encapsulates a function composition, but instead of reading from some environment state, it allows you to write to some state.
The most common use case allows you to keep a log as you go through your function composition.
### Methods
#### static of ($x, $m = [])
Creates a `Writer` with a function that just returns the parameter
value `$x`. The difference between using `Writer::of` and just calling
the helper function `Writer` is that `Writer::of` creates a function
returning whatever was passed in, while `Writer` just takes the parameter function as it's function, without the extra level of nesting.
```php
Writer::of('foo')->run();
// ['foo', []]

Writer::of('foobar', 'test')->run();
// ['foobar', '']
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
#### map ($g)
Used to transform the value that will be returned by the `Writer`.
Simply runs the parameter function `$g` on the current value inside the `Writer`. This does not affect the log value.
```php
Writer::of('foo')->map(function($x) {
    return $x . 'bar';
})->run();
// ['foobar', []]
```
#### ap (Reader $g)
Used when you have a `Writer` whose computation value is a function, and you want to apply that function to the value inside of a different `Writer`. It also concatenates the log values of the two `Writer` instances.
```php
$a = Writer::of('foo');
$b = Writer::of(function($x) {
    return $x . 'bar';
});

$a->ap($b)->run();
// ['foobar', []]
```
#### chain ($r) (aliases: bind, flatMap)
Used when you want to map with a function that returns a `Writer`. The computation value becomes the result of the parameter function `$r`, while the log value is the result of combining the current instance with the returned `Writer`.
```php
Writer::of('foo')->chain(function($x) {
    return Writer(function() use ($x) {
        return [$x . 'bar', ['Bar Stuff']];
    });
});
// ['foobar', ['Bar Stuff']]
```
