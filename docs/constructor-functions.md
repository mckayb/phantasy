# Constructor Functions

## Just
### Description
A helper function to create a `Just` while avoiding the annoyances of
new in PHP.
### Examples
```php
Just(5);
// Equivalent to new Just(5)

Just(function($x) {
    return $x;
});
// Equivalent to new Just(function($x) { return $x; });
```

## Nothing
### Description
A helper function to create a `Nothing` while avoiding the annoyances of
new in PHP.
### Examples
```php
Nothing();
// Equivalent to new Nothing()
```

## Left
### Description
A helper function to create a `Left` while avoiding the annoyances of
new in PHP.
### Examples
```php
Left('foo');
// Equivalent to new Left('foo')

Left([1, 2, 3]);
// Equivalent to new Left([1, 2, 3]);
```

## Right
### Description
A helper function to create a `Right` while avoiding the annoyances of
new in PHP.
### Examples
```php
Right('foo');
// Equivalent to new Right('foo')

Right([1, 2, 3]);
// Equivalent to new Right([1, 2, 3]);
```

## Failure
### Description
A helper function to create a `Failure` while avoiding the annoyances of
new in PHP.
### Examples
```php
Failure('foo');
// Equivalent to new Failure('foo')

Failure([1, 2, 3]);
// Equivalent to new Failure([1, 2, 3]);
```

## Success
### Description
A helper function to create a `Success` while avoiding the annoyances of
new in PHP.
### Examples
```php
Success('foo');
// Equivalent to new Success('foo')

Success([1, 2, 3]);
// Equivalent to new Success([1, 2, 3]);
```

## Reader
### Description
A helper function to create a `Reader` while avoiding the annoyances of
new in PHP.
### Examples
```php
Reader(function($x) {
    return $x;
});
// Equivalent to new Reader(function($x) { return $x; });
```
