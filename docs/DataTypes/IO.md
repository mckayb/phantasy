## IO
### Usage
```php
use function Phantasy\DataTypes\IO\IO;
use Phantasy\DataTypes\IO\IO;
```
### Description
`IO` is intended to provide a more transparent way of saying "this code is doing some stuff that is inherently unsafe". It's saying, that you don't really have a lot of control over whether this code is gonna throw exceptions, or do some other crazy stuff, so we're gonna delay it in a very transparent way until we absolutely need to.
### Methods
#### static of ($x) : IO
Simply take a value and lift it into the `IO` context.
```php
IO::of('foo')->run();
// 'foo'

IO::of('foobar')->run();
// 'foobar'
```
#### run ($s)
Runs the function that the current `IO` instance is holding.
```php
IO::of('foo')->run();
// 'foo'

IO::of('foobar')->run();
// 'foobar'
```
#### map (callable $g) : IO
Used to transform the value that will be returned by the `IO`.
Simply runs the parameter function `$g` on the current value inside the `IO`.
```php
IO::of('foo')->map(function($x) {
    return $x . 'bar';
})->run();
// 'foobar'
```
#### ap (IO $g) : IO
Used when you have a `IO` whose computation value is a function, and you want to apply that function to the value inside of a different `IO`.
```php
$a = IO::of('foo');
$b = IO::of(function($x) {
    return $x . 'bar';
});

$a->ap($b)->run();
// 'foobar'
```
#### chain (callable $f) : IO (aliases: bind, flatMap)
Used when you want to map with a function that returns a `IO`. The computation value becomes the result of the parameter function `$f`, while the state value is accessible to be read and changed.
```php
IO::of('/path/to/my/file')->chain(function($x) {
    return IO(function() use ($x) {
        return file_get_contents($x);
    });
})->run();
```