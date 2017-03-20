# php-fp
Functional Programming Helpers and Data Types for PHP.

## What's Included
  * Currying, Higher-Order Functions, etc
  * Maybe Monad
  * Either Monad
  * Validation Applicative Functor
  * (More coming...)
  
### Currying
```
use function PHPFP\Core\curry;

$add = curry(function($a, $b) {
  return $a + $b;
});

// Use one parameter at a time.
$add2 = $add(2);
$add2(5) // 7

// Or all at once.
$add(2, 5) // 7
```

### Maybe Monad
```
use PHPFP\DataTypes\Maybe;

$bad_json_users = "[{ "id": 1, "name" "Foo" }, { "id": 2, "name": "Bar" }]"; // No : after "name"
$good_json_users = "[{ "id": 1, "name: "Foo" }, { "id": 2, "name": "Bar" }]"; // Good json!

function getUserNames($user_json) {
  Maybe::fromNullable(json_decode($user_json, true))
    ->map(function($user) {
      return $user["name"];
    })
    ->getOrElse([]);
}

getUserNames($bad_json_users); // []
getUserNames($good_json_users); // ["Foo", "Bar"];
```

### Either Monad
```
use PHPFP\DataTypes\Either;
```

### Validation Applicative Functor
```
use PHPFP\DataTypes\Validation;
```

## Inspiration
  * [Monet.js](https://github.com/cwmyers/monet.js)
  * [Folktale.js](https://github.com/origamitower/folktale)
