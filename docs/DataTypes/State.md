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