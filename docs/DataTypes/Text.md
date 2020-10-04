## Text
### Usage
```php
use function Phantasy\DataTypes\Text\Text;
```
### Description
A replacement for normal string types, using the same interfaces as the
other Data Types.

### Methods
#### equals(Text $t) : bool
Used to compare two `Text`'s for equality.
Two `Text`'s are equal if they are the same string value.
```php
Text("foo")->equals(Text("foo")); // true
Text("foo")->equals(Text("bar")); // false
```
#### lte(Text $t) : bool
Used to compare two strings, to know if one is "less than or equal to" the other. This uses `strcmp` under the hood.
```php
Text("bar")->lte(Text("foo")); // true
Text("foo")->lte(Text("bar")); // false
Text("")->lte(Text("Something")); // true
Text("foo")->lte(Text("foo")); // true
```
#### concat(Text $t) : Text
Used to concatenate two `Text`'s together.
```php
Text("foo")->concat(Text("bar")); // Text("foobar")
Text("")->concat(Text("foo")); // Text("foo")
Text("foo")->concat(Text("")); // Text("foo")

// Can also do this, but it removes the Text wrapper.
Text("foo") . Text("bar"); // "foobar":
```
#### static empty() : Text
Used to create a `Text` containing an empty string.
```php
Text::empty(); // Text("")
```
#### static fromString(string $s) : Text
Another way to create a `Text` from a string.
```php
Text::fromString("Foo"); // Text("Foo")
Text::fromString(""); // Text("")
```
