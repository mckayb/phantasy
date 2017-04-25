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
Simply creates a `new Just ($val)`. Good for lifting values into the `Maybe` context, so that you can map, apply or use other nice functions over it.
```php
$appendBar = function($x) {
    return $x . 'bar';
};
Maybe::of('foo')->map($appendBar);
// Equivalent to new Just('foo')->map($appendBar);
```
#### static fromNullable ($val)
Checks the value that is passed in. If it's null, it returns a `new Nothing()`,
otherwise it returns a `new Just($val)`.
```php
$a = null;
$b = 12;
Maybe::fromNullable($a); // Nothing()
Maybe::fromNullable($b); // Just(12)
```
#### static tryCatch ($f)
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `Just`, with the value of the result of the function. If it throws an exception, it returns a `new Nothing()`.
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
Creates a new Right(\$val);
```php
Either::of(1);
// Right(1)
```
#### static fromNullable ($failVal, $val)
If \$val is null, it creates a `new Left($failVal)`, otherwise it creates a `new Right($val)`.
```php
Either::fromNullable('left val', 'right val');
// Right('right val')

Either::fromNullable('left val', null);
// Left('left val')
```
#### static tryCatch ($f)
Performs a function that might throw an exception. If it succeeds,
it stores the result in a `new Right($f())`, otherwise, it returns a `new Left($exception)`.
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
$a = Either::fromNullable('Val is null!', null);
$b = Either::of(function($x) {
    return strtolower($x);
});
$a->ap($b);
// Left('Val is null!')
```
#### chain ($f) (aliases: bind, flatMap)
Used when you want to map over your value, but where the mapping function returns an `Either` context.
If the instance is a `Right`, it simply returns the result of the function.
```php
$a = Either::of(1)->chain(function($x) {
    return $x >= 1 ? new Right($x) : new Left(1);
});
// Right(1)
```
If the instance is a `Left`, it just ignores the function and returns the current instance.
```php
$a = Either::fromNullable(0, null)->chain(function($x) {
    return $x >= 1 ? new Right($x) : new Left(1);
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
$a = (new Left('foo'))->bimap(function($x) {
    return $x . 'baz';
}, function($x) {
    return $x . 'bar';
});
// Left('foobaz')
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
Creates a `new Success($val)`.
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
it stores the result in a `Just`, with the value of the result of the function. If it throws an exception, it returns a `new Nothing()`.
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
// If an exception is thrown, it returns Failure()
// If no exception was thrown, returns Success($connection)
```
#### map ($f)
Used to transform the value inside of our `Validation`.
Applies the function `$f` to the value inside of our instance.
If the instance is a `Success`, it returns a new `Success` with the result of the function.
```php
Validation::of(1)->map(function($x) {
    return $x + 1;
});
// Success(2)
```
If the instance is a `Failure`, it just returns the instance.
```php
(new Failure('There was a problem'))->map(function($x) {
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
(new Failure(['First problem']))->fold(
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
(new Failure(12))->bimap(
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
$b = new Failure(['There was some problem.']);
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
$a = new Failure(['There was a problem.']);
$b = new Failure(['There was another problem.']);
$c = $a->concat($b);
// Failure(['There was a problem.', 'There was another problem.']);
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
(new Failure('foobar'))->toEither();
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
(new Failure('foo'))->toMaybe();
// Nothing()
```
