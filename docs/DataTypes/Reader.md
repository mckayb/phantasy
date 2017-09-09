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