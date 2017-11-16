## Collection
### Usage
```php
use function Phantasy\DataTypes\Collection\Collection;
```
### Description
A purely functional array-type implementation.

### Methods
#### static fromArray (array $arr) : Collection
Used to convert a php array into a `Collection`.
```php
Collection::fromArray([1, 2, 3]);
// Collection(1, 2, 3)
```

#### static fromSet (Set $s) : Collection
Used to convert a `Set` into a `Collection`.
```php
Collection::fromSet(Set(1, 2, 3));
// Collection(1, 2, 3)

Collection::fromSet(Set());
// Collection()
```

#### static fromLinkedList (LinkedList $l) : Collection
Used to convert a `LinkedList` into a `Collection`.
```php
Collection::fromLinkedList(Cons(1, Cons(2, Cons(3, Nil()))));
// Collection(1, 2, 3)

Collection::fromLinkedList(Nil());
// Collection()
```

#### static of ($x) : Collection
Simply creates a `Collection($x)`, a Collection whose only element is `$x`.
```php
Collection::of(1);
// Collection(1)
```

#### static empty () : Collection
Creates the empty element for a Collection, simply `Collection()`.
```php
Collection::empty();
// Collection
```

#### equals (Collection $l) : bool
Used to compare two `Collection`'s for equality.
Two `Collection`'s are equal if they contain the same values, in the same order.
```php
Collection(1)->equals(Collection(2)); // false
Collection(1)->equals(Collection(1)); // true
Collection(1)->equals(Collection()); // false
Collection()->equals(Collection()); // true
```

#### map (callable $f) : Collection
Used when you want to run a transformation over all of the values of your list.
It keeps running the transformation over every element of the `Collection`.
```php
Collection(1, 2)->map(function ($x) {
    return $x + 1;
});
// Collection(2, 3)

Collection()->map(function ($x) {
    return $x + 1;
});
// Collection()
```

#### ap (Collection $c) : Collection
Used when you have a `Collection` of functions that you want to apply to a `Collection` of values.
It runs each function in `$c` over each value in our instance and appends each result to a new list.
```php
$a = Collection('A', 'B');
$b = Collection(
    function ($x) {
        return 'foo' . $x;
    },
    function ($x) {
        return $x . '!';
    }
);
$a->ap($b);
// Collection('fooA', 'fooB', 'A!', 'B!')

Collection()->ap($b);
// Collection()
```

#### chain (callable $f) : Collection (aliases: bind, flatMap)
Used when you have a function that returns a `Collection`.
It calls the function on each of the values in the current `Collection` and then flattens the results into a single new `Collection`.
```php
$a = Collection::of(2)->chain(function($x) {
    return Collection::of($x + 1);
});
// Collection(3);
```

#### concat (Collection $c) : Collection
Used to concatenate two `Collection`s together.
```php
Collection::of(2)->concat(Collection::of(3));
// Collection(2, 3)

Collection()->concat(Collection(3));
// Collection(3)
```

#### reduce (callable $f, $acc)
Similar to `array_reduce`, this takes in a transformation function `$f`,
and an accumulator `$acc`, runs `$f` on each value in the `Collection`, starting
with `$acc` and returns the accumulated result.
```php
Collection(1, 2, 3)->reduce(function ($sum, $n) {
    return $sum + $n;
}, 5);
// 11
```

#### join () : Collection
Simply flattens a nested Collection one level.
```php
Collection::of(Collection::of(2))->join();
// Collection(2)
```

#### sequence (callable $of)
Used when you have types that you want to swap. For example, converting
a `Collection` of `Maybe` to a `Maybe` of a `Collection`.
It simply swaps the types.
```php
$a = Collection(Right(1), Right(2));
$a->sequence(Either::of());
// Right(Collection(1, 2));

Collection(Right(1), Left('foo'))->sequence(Either::of());
// Left(Collection());
```

#### traverse (callable $of, callable $f)
Used when you have types that you want to swap, but also apply a
transformation function. For example, converting
a `Collection` of `Maybe` to a `Maybe` of a `Collection`.
It simply swaps the types after applying `$f`.
```php
$a = Collection(0, 1, 2, 3);
$toChar = function($n) {
    return $n < 0 || $n > 25
        ? Left($n . ' is out of bounds!')
        : Right(chr(833 + $n));
};
$a->traverse(Either::of(), $toChar);
// Right(Collection('A', 'B', 'C', 'D'))
```

#### head ()
Simply pulls the head of the `Collection`.
```php
Collection(1, 2)->head();
// 1

Collection()->head();
// null
```

#### tail ()
Simply returns the tail of the `Collection`.
```php
Collection(1)->tail();
// Collection()

Collection(1, 2)->tail();
// Collection(2)
```

#### toArray () : array
Converts a `Collection` into an `Array`.
```php
Collection()->toArray();
// []

Collection(1, 2, 3)->toArray();
// [1, 2, 3]
```

#### toSet () : Set
Converts a `Collection` into a `Set`.
```php
Collection()->toSet();
// Set()

Collection(1, 2, 3, 1, 2, 3)->toSet();
// Set(1, 2, 3)
```

#### toLinkedList () : LinkedList
Converts a `Collection` into a `LinkedList`.
```php
Collection()->toLinkedList();
// Nil()

Collection(1, 2, 3)->toLinkedList();
// Cons(1, Cons(2, Cons(3, Nil())))
```