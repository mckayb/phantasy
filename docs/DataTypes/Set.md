## Set
### Usage
```php
use function Phantasy\DataTypes\Set\Set;
```
### Description
A purely functional Set implementation.

### Methods
#### static fromArray (array $arr) : Set
Used to convert a php array into a `Set`.
```php
Set::fromArray([1, 2, 3]);
// Set(1, 2, 3)
```

#### static fromSet (Set $s) : Set
Used to convert a `Set` into a `Set`.
```php
Set::fromSet(Set(1, 2, 3));
// Set(1, 2, 3)

Set::fromSet(Set());
// Set()
```

#### static fromLinkedList (LinkedList $l) : Set
Used to convert a `LinkedList` into a `Set`.
```php
Set::fromLinkedList(Cons(1, Cons(2, Cons(3, Nil()))));
// Set(1, 2, 3)

Set::fromLinkedList(Nil());
// Set()
```

#### static of ($x) : Set
Simply creates a singleton `Set($x)`, a `Set` whose only element is `$x`.
```php
Set::of(1);
// Set(1)
```

#### static empty () : Set
Creates the empty `Set`, a `Set` with no elements.
```php
Set::empty();
// Set
```

#### equals (Set $l) : bool
Used to compare two `Set`'s for equality.
Two `Set`'s are equal if they contain the same values, in the same order.
```php
Set(1)->equals(Set(2)); // false
Set(1)->equals(Set(1)); // true
Set(1)->equals(Set()); // false
Set()->equals(Set()); // true
```

#### map (callable $f) : Set
Used when you want to run a transformation over all of the values of your `Set`.
It keeps running the transformation over every element of the `Set`.
```php
Set(1, 2)->map(function ($x) {
    return $x + 1;
});
// Set(2, 3)

Set()->map(function ($x) {
    return $x + 1;
});
// Set()
```

#### ap (Set $c) : Set
Used when you have a `Set` of functions that you want to apply to a `Set` of values.
It runs each function in `$c` over each value in our instance and appends each result to a new list.
```php
$a = Set('A', 'B');
$b = Set(
    function ($x) {
        return 'foo' . $x;
    },
    function ($x) {
        return $x . '!';
    }
);
$a->ap($b);
// Set('fooA', 'fooB', 'A!', 'B!')

Set()->ap($b);
// Set()
```

#### chain (callable $f) : Set (aliases: bind, flatMap)
Used when you have a function that returns a `Set`.
It calls the function on each of the values in the current `Set` and then flattens the results into a single new `Set`.
```php
$a = Set::of(2)->chain(function($x) {
    return Set::of($x + 1);
});
// Set(3);
```

#### concat (Set $c) : Set (aliases: union)
Used to concatenate two `Set`s together. This is simply
the union of the two `Set`s.
```php
Set::of(2)->concat(Set::of(3));
// Set(2, 3)

Set()->concat(Set(3));
// Set(3)
```

#### reduce (callable $f, $acc)
Similar to `array_reduce`, this takes in a transformation function `$f`,
and an accumulator `$acc`, runs `$f` on each value in the `Set`, starting
with `$acc` and returns the accumulated result.
```php
Set(1, 2, 3)->reduce(function ($sum, $n) {
    return $sum + $n;
}, 5);
// 11
```

#### join () : Set
Simply flattens a nested `Set` one level.
```php
Set::of(Set::of(2))->join();
// Set(2)
```

#### sequence (callable $of)
Used when you have types that you want to swap. For example, converting
a `Set` of `Maybe` to a `Maybe` of a `Set`.
It simply swaps the types.
```php
$a = Set(Right(1), Right(2));
$a->sequence(Either::of());
// Right(Set(1, 2));

Set(Right(1), Left('foo'))->sequence(Either::of());
// Left(Set());
```

#### traverse (callable $of, callable $f)
Used when you have types that you want to swap, but also apply a
transformation function. For example, converting
a `Set` of `Maybe` to a `Maybe` of a `Set`.
It simply swaps the types after applying `$f`.
```php
$a = Set(0, 1, 2, 3);
$toChar = function($n) {
    return $n < 0 || $n > 25
        ? Left($n . ' is out of bounds!')
        : Right(chr(833 + $n));
};
$a->traverse(Either::of(), $toChar);
// Right(Set('A', 'B', 'C', 'D'))
```

#### intersect (Set $s) : Set
Used to find the common values between two sets.
Simply returns a new `Set` containing everything that
is in our current instance and the `Set` `$s`.
```php
Set(1, 2, 3, 4)->intersect(Set(2, 3, 4, 5));
// Set(2, 3, 4)

Set('foo', 'bar')->intersect(Set('bar', 'baz'));
// Set('bar')

Set(1, 2)->intersect(Set(1, 2));
// Set(1, 2)

Set()->intersect(Set('foo'));
// Set()

Set(1, 2)->intersect(Set(3, 4));
// Set()
```

#### difference (Set $s) : Set
Used to find all of the items that are in the instance `Set`, but not in the parameter `Set` `$s`.
```php
Set(1, 2, 3)->difference(Set(2, 3, 4));
// Set(1)

Set(1, 2)->difference(Set());
// Set(1, 2)

Set()->difference(Set());
// Set()

Set('foo', 'bar')->difference(Set('baz', 'quux'));
// Set('foo', 'bar')
```

#### isSubsetOf (Set $s) : bool
Used to see if our instance `Set` is contained in the parameter `Set` `$s`. Tells if you every item of our instance `Set` is also in the parameter `Set` `$s`.
```php
Set(1, 2, 3)->isSubsetOf(Set(1, 2, 3));
// True

Set(1, 2)->isSubsetOf(Set(1, 2, 3));
// True

Set()->isSubsetOf(Set('foo'));
// True

Set(1)->isSubsetOf(Set());
// False

Set(1, 2)->isSubsetOf(Set(1));
// False
```

#### isProperSubsetOf (Set $s) : bool
Used to see if our instance `Set` is contained in the parameter `Set` `$s`. Tells if you every item of our instance `Set` is also in the parameter `Set` `$s`, and also that the two `Set`'s are not the same.
```php
Set(1, 2, 3)->isProperSubsetOf(Set(1, 2, 3));
// False

Set(1, 2)->isProperSubsetOf(Set(1, 2, 3));
// True

Set()->isProperSubsetOf(Set('foo'));
// True

Set(1)->isProperSubsetOf(Set());
// False

Set(1, 2)->isProperSubsetOf(Set(1));
// False
```

#### cardinality () : int (aliases: size, count)
Gives you the number of items that are in the set.
```php
Set(1, 2, 3)->cardinality();
// 3

Set(1, 2, 3, 4, 5)->cardinality();
// 5

Set('foo', 'foo', 'bar')->cardinality();
// 2 (Duplicates get removed!)
```

#### contains ($x) : bool
Used to see if our instance `Set` contains the item
`$x`.
```php
Set(1)->contains(1);
// True

Set(1, 2, 3)->contains(4);
// False

Set()->contains(null);
// False
```

#### toArray () : array
Converts a `Set` into an `Array`.
```php
Set()->toArray();
// []

Set(1, 2, 3)->toArray();
// [1, 2, 3]
```

#### toSet () : Set
Converts a `Set` into a `Set`.
```php
Set()->toSet();
// Set()

Set(1, 2, 3, 1, 2, 3)->toSet();
// Set(1, 2, 3)
```

#### toLinkedList () : LinkedList
Converts a `Set` into a `LinkedList`.
```php
Set()->toLinkedList();
// Nil()

Set(1, 2, 3)->toLinkedList();
// Cons(1, Cons(2, Cons(3, Nil())))
```