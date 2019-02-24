# Phantasy [![CircleCI](https://circleci.com/gh/mckayb/phantasy.svg?style=svg)](https://circleci.com/gh/mckayb/phantasy)[![Coverage Status](https://coveralls.io/repos/github/mckayb/phantasy/badge.svg?branch=master)](https://coveralls.io/github/mckayb/phantasy)
Functional Programming Helpers and Data Types for PHP.

## Getting Started

### Installation
`composer require mckayb/phantasy`

### Usage
```php
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\Core\prop;

$user = [ "name" => "Foo", "email" => "foo@example.com" ];
$name = Maybe::of($user)
	->map(prop('name'))
	->getOrElse(null);
// "Foo"
```
For more information, read the [docs!](https://github.com/mckayb/phantasy/tree/master/docs)

## What's Included
  * Currying, Composition, Higher-Order Functions, etc
  * Maybe, Either, Reader, Writer, State, Linked List, Validation Data Types.
  * More coming...

## Contributing
Find a bug? Want to make any additions?
Just create an issue or open up a pull request.

## Want more?
For other helpers not included in this repo, check out
  * [Phantasy-PHP](https://github.com/mckayb/phantasy-php)
  * [Phantasy-Types](https://github.com/mckayb/phantasy-types)
  * [Phantasy-Recursion-Schemes](https://github.com/mckayb/phantasy-recursion-schemes)

## Inspiration
  * [Monet](https://github.com/monet/monet.js)
  * [Folktale](https://github.com/origamitower/folktale)
  * [Fantasy Land](https://github.com/fantasyland/fantasy-land)
  * [Daggy](https://github.com/fantasyland/daggy)
