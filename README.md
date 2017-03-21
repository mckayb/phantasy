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
use PHPFP\DataTypes\Maybe\Maybe;

function getUserNames($user_json) {
  return Maybe::fromNullable(json_decode($user_json, true))
    ->map(function($user) {
      return $user["name"];
    })
    ->getOrElse([]);
}

$bad_json_users = "[{ "id": 1, "name" "Foo" }, { "id": 2, "name": "Bar" }]"; // No : after "name"
$good_json_users = "[{ "id": 1, "name: "Foo" }, { "id": 2, "name": "Bar" }]"; // Good json!
getUserNames($bad_json_users); // []
getUserNames($good_json_users); // ["Foo", "Bar"];
```

### Either Monad
```
use PHPFP\DataTypes\Either\{Right, Left};
use function PHPFP\Core\prop;

$getContents = function ($file) {
  return is_file($file) ? new Right(file_get_contents($file)) : new Left('Not a file!');
}

$parseJSON = function ($file) {
  $json = json_decode($file, true);
  return is_null($json) ? new Right($json) : new Left('Not valid json!');
}

// config.json
{
  "DB_HOST": "foo",
  ... More options
}

getContents('config.json')
  .chain($parseJSON)
  .map(prop('DB_HOST'));
  .fold(function($e) {
    // Do something with the error message
    return handleError($e);
  }, function($x) {
    return $x;
  }; // "foo"
```

### Validation Applicative Functor
```
use PHPFP\DataTypes\Validation;
```

## Inspiration
  * [Monet.js](https://github.com/cwmyers/monet.js)
  * [Folktale.js](https://github.com/origamitower/folktale)
