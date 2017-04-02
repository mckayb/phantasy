# Phantasy [![Build Status](https://travis-ci.org/mckayb/phantasy.svg?branch=master)](https://travis-ci.org/mckayb/phantasy) [![Coverage Status](https://coveralls.io/repos/github/mckayb/phantasy/badge.svg)](https://coveralls.io/github/mckayb/phantasy)
Functional Programming Helpers and Data Types for PHP.

## Getting Started

### Installation
`composer require mckayb/phantasy`

### Usage
```
use Phantasy\DataTypes\Maybe\Maybe;
use function Phantasy\Core\prop;

$user = [ "name" => "Foo", "email" => "foo@example.com" ];
$names = Maybe::of($user)
	->map(prop('name'))
	->getOrElse([]);
// "Foo"
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
  * [Monet](https://github.com/cwmyers/monet.js)
  * [Folktale](https://github.com/origamitower/folktale)
  * [Fantasy Land](https://github.com/fantasyland/fantasy-land)
