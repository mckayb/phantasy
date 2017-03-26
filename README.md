# PHP-FP [![Build Status](https://travis-ci.org/mckayb/php-fp.svg?branch=master)](https://travis-ci.org/mckayb/php-fp) [![Coverage Status](https://coveralls.io/repos/github/mckayb/php-fp/badge.svg)](https://coveralls.io/github/mckayb/php-fp)
Functional Programming Helpers and Data Types for PHP.

## Getting Started

### Installation
`composer require mckayb/php-fp`

### Usage
```
use PHPFP\DataTypes\Maybe\Maybe;
use function PHPFP\Core\prop;

$users = [
	[ "name" => "Foo", "email" => "foo@example.com"],
	[ "name" => "Bar", "email" => "bar@example.com"]
];
$names = Maybe::of($users)
	->map(prop('name'))
	->getOrElse([]);
// [ "Foo", "Bar" ]
```
For more information, read the docs!

## What's Included
  * Currying, Higher-Order Functions, etc
  * Maybe Monad
  * Either Monad
  * Validation Applicative Functor
  * More coming...

## Contributing
Find a bug? Want to make any additions?
Just create an issue or open up a pull request.

## Inspiration
  * [Monet.js](https://github.com/cwmyers/monet.js)
  * [Folktale.js](https://github.com/origamitower/folktale)
