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
#### chain (callable $f) : LinkedList (aliases: bind, flatMap)
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
#### head ()
Simply pulls the head of the `LinkedList`.
If the instance is a `Cons`, it just grabs the value of the head.
```php
Cons(1, Nil())->head();
// 1

Cons('foo', Cons('bar', Nil()))->head();
// 'foo'
```
If the instance is a `Nil`, it returns null.
```php
Nil()->head();
// null

Maybe::fromNullable(Nil()->head());
// Nothing()
```
#### tail ()
Simply returns the tail of the `LinkedList`.
If the instance is a `Cons`, it returns everything but
the head.
```php
Cons(1, Nil())->tail();
// Nil()

Cons(1, Cons(2, Nil()))->tail();
// Cons(2, Nil())
```
If the instance is a `Nil`, it just returns `Nil`.
```php
Nil()->tail();
// Nil()
```