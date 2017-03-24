# php-fp [![Build Status](https://travis-ci.org/mckayb/php-fp.svg?branch=master)](https://travis-ci.org/mckayb/php-fp)  [![Coverage Status](https://coveralls.io/repos/github/mckayb/php-fp/badge.svg)](https://coveralls.io/github/mckayb/php-fp)
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
use function PHPFP\Core\{map, prop};

$getUserName = function($json) {
  return Maybe::fromNullable(json_decode($json, true))
    ->map(prop('name'))
    ->getOrElse([]);
};

$bad_json_user = '{"id": 1, "name" "Foo" }'; // No : after "name"
$good_json_user = '{ "id": 1, "name": "Foo" }'; // Good json!

$getUserName($bad_json_user); // []
$getUserName($good_json_user); // "Foo"
```

### Either Monad
```
$getContents = function ($file) {
  return is_file($file)
    ? new Right(file_get_contents($file))
    : new Left('Not a file!');
};

$parseJSON = function($json) {
  return Either::fromNullable(json_decode($json, true));
};

// config.json
{
  "DB_HOST": "foo"
  ... More options ...
}

$getContents('config.json')
  ->chain($parseJSON)
  ->map(prop('DB_HOST'))
  ->fold(function($e) {
    return 'Error: ' . $e;
  }, function($x) {
    return $x;
  });
// "foo"
```

### Validation Applicative Functor
```
use PHPFP\DataTypes\Validation\{Validation, Success, Failure};

$db = new class() {
  public function save($attrs) {
    // Insert into database...
    return array_merge($attrs, ["id" => 1]);
  }
};

$createUser = function($input) use ($db) {
  $validatePasswordLength = function($attrs) {
    return isset($attrs['password']) && strlen($attrs['password']) >= 10
      ? new Success($attrs)
      : new Failure(["Password must contain at least 10 characters."]);
  };

  $validateEmail = function($attrs) {
    return isset($attrs['email']) && filter_var($attrs['email'], FILTER_VALIDATE_EMAIL) !== false
      ? new Success($attrs)
      : new Failure(["Must have a valid email address."]);
  };

  $validateName = function($attrs) {
    return isset($attrs['name']) && strlen($attrs['name']) > 0
      ? new Success($attrs)
      : new Failure(["Name must not be empty."]);
  };
  
  return Validation::of($input)
    ->concat($validateName($input))
    ->concat($validateEmail($input))
    ->concat($validatePasswordLength($input))
    ->map(function($attrs) use ($db) {
      return $db->save($attrs);
    });
};
$createUser([]);
/*
Failure([
  'Name must not be empty.',
  'Must have a valid email address.',
  'Password must contain at least 10 characters.'
]);
*/

$createUser([
  'name' => 'Foo',
  'email' => 'Bar',
  'password' => 'Baz1234567'
]);
// Failure(['Must have a valid email address.']);

$createUser([
  'name' => 'Foo',
  'email' => 'bar@example.com',
  'password' => 'Baz1234567'
]);
/*
Success([
  'id' => 1,
  'name' => 'Foo',
  'email' => 'bar@example.com',
  'password' => 'Baz1234567'
]);
*/
```

## Inspiration
  * [Monet.js](https://github.com/cwmyers/monet.js)
  * [Folktale.js](https://github.com/origamitower/folktale)
