## Writer
### Usage
```php
use function Phantasy\DataTypes\Writer\Writer;
use Phantasy\DataTypes\Writer\Writer;
```
### Description
The `Writer` type, similar to the `Reader` type, also encapsulates function composition, but instead of reading from some environment state, it allows you to write to some state.
The most common use case allows you to keep a log as you go through your function composition.
### Methods
#### static of ($x, $m = []) : Writer
Creates a `Writer` with a function that just returns the parameter
value `$x`. The difference between using `Writer::of` and just calling
the helper function `Writer` is that `Writer::of` creates a function
returning whatever was passed in, while `Writer` just takes the parameter
function as it's function, without the extra level of nesting.
```php
Writer::of('foo')->run();
// ['foo', []]

Writer::of('foobar', 'test')->run();
// ['foobar', '']
```
#### static chainRec(callable $f, $i, $m = []) : Writer
Used as an abstraction to get around exploding the call stack during complicated calculations.
Takes a function which has three parameters, `$next`, `$done`, and `$val`. This lets us simulate recursion, but is implemented in a way that will not blow the stack.
```php
use function Phantasy\DataTypes\Writer\Writer;
use Phantasy\DataTypes\Writer\Writer;

list($val, $log) = Writer::chainRec(function ($next, $done, $x) {
    return Writer(function () use ($next, $done, $x) {
        return [$x >= 10000 ? $done($x) : $next($x + 1), [$x]];
    });
}, 0)->run();
// $log = [0, 1, 2, ... , 10000]
// $val = [0, 1, 2, ... , 10000]
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
#### map (callable $g) : Writer
Used to transform the value that will be returned by the `Writer`.
Simply runs the parameter function `$g` on the current value inside the `Writer`. This does not affect the log value.
```php
Writer::of('foo')->map(function($x) {
    return $x . 'bar';
})->run();
// ['foobar', []]
```
#### ap (Writer $g) : Writer
Used when you have a `Writer` whose computation value is a function, and you want to apply that function to the value inside of a different `Writer`. It also concatenates the log values of the two `Writer` instances.
```php
$a = Writer::of('foo');
$b = Writer::of(function($x) {
    return $x . 'bar';
});

$a->ap($b)->run();
// ['foobar', []]
```
#### chain (callable $f) : Writer (aliases: bind, flatMap)
Used when you want to map with a function that returns a `Writer`. The computation value becomes the result of the parameter function `$f`, while the log value is the result of combining the current instance with the returned `Writer`.
```php
Writer::of('foo')->chain(function($x) {
    return Writer(function() use ($x) {
        return [$x . 'bar', ['Bar Stuff']];
    });
});
// ['foobar', ['Bar Stuff']]
```
#### extend (callable $f) : Writer
Extend is essentially a map-like function over your Writer type, but with read access to the log val as well!
To emphasize this type, we're gonna create a small game.
```php
// Let's first define a Sum type to keep track
// of our hero's hunger.
 $Sum = function ($x) {
    return new class ($x) {
        public $x;
        public function __construct($x)
        {
            $this->x = $x;
        }

        public function concat($a)
        {
            return new static($this->x + $a->x);
        }

        public static function empty()
        {
            return new static(0);
        }
    };
};

// We'll start with our hero Jerry.
$exampleHero = [
    'name' => 'Jerry',
    'isHungry' => false
];

// An adventurer is just a hero, with
// his/her associated hunger.
$adventurer = function ($hero, $hunger) {
    return Writer(function () use ($hero, $hunger) {
        return [$hero, $hunger];
    });
};

// Slaying a dragon is hard work, and
// causes the hero's hunger to go up.
$slayDragon = function ($hero) use ($Sum, $adventurer) {
    return $adventurer($hero, $Sum(100));
};

// Running from the dragon also causes
// the hunger to go up, but by less
// than actually slaying it.
$runFromDragon = function ($hero) use ($Sum, $adventurer) {
    return $adventurer($hero, $Sum(50));
};

// Eating reduces our hunger!
$eat = function ($hero) use ($adventurer, $Sum) {
    return $hero['isHungry']
        ? $adventurer(array_merge($hero, ['isHungry' => false]), $Sum(-100))
        : $adventurer($hero, $Sum(0));
};

// This the function we care about.
// It takes an adventurer, and checks it's
// hunger val (the log val of our Writer)
// and uses that to affect our hero
// (the computation val of our Writer)
$areWeHungry = function ($adv) {
    list($hero, $hunger) = $adv->run();
    return $hunger->x > 200
        ? array_merge($hero, ['isHungry' => true])
        : $hero;
};

// We start battling!
// We first slay the dragon, then check
// if we're hungry. We then eat.
$battle1 = $slayDragon($exampleHero)->extend($areWeHungry)->chain($eat);
/*
$battle1->run();
[
    ['name' => 'Jerry', 'isHungry' => false],
    $Sum(100)
]
*/

// After the first battle, we slay another
// dragon and eat.
$battle2 = $battle1->chain($slayDragon)->extend($areWeHungry)->chain($eat);
/*
$battle2->run();
[
    ['name' => 'Jerry', 'isHungry' => false],
    $Sum(200)
]
*/

// After the second battle, we run from a
// dragon and eat.
$battle3 = $battle2->chain($runFromDragon)->extend($areWeHungry)->chain($eat);
/*
$battle3->run();
[
    ['name' => 'Jerry', 'isHungry' => false],
    $Sum(150)
]
*/

// Finally, after the third battle, we slay
// another dragon, and check if we're hungry.
$battle4 = $battle3->chain($slayDragon)->extend($areWeHungry);
/*
$battle4->run();
[
    ['name' => 'Jerry', 'isHungry' => true],
    $Sum(250)
]
*/
```
#### extract ()
Simply pulls the value out of the writer. It only pulls the computation value, not the log value.
```php
Writer::of(1)->extract();
// 1

Writer::of('foo')->map(function ($x) {
    return $x . 'bar';
})->extract();
// 'foobar'
```