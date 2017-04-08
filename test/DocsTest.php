<?php

namespace Phantasy\Tests;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Either\{Either, Left, Right};
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};
use function Phantasy\Core\{map, prop, identity, curry, compose, liftA, SumType};
use const Phantasy\Core\identity;

class DocsTest extends TestCase
{
    public function testCallableIdentity()
    {
        $this->assertEquals('foobar', Either::fromNullable('foo')
            ->map(function ($x) {
                return $x . 'bar';
            })
            ->fold(identity, identity));
        $this->assertEquals(1, identity(1));
    }

    public function testIdentityExamples()
    {
        $this->assertEquals(identity(1), 1);
        $this->assertEquals(identity('foo'), 'foo');
        $this->assertEquals(identity(function ($x) {
            return $x;
        }), function ($x) {
            return $x;
        });

        $this->assertEquals('foobar', Either::fromNullable('foo')
            ->map(function ($x) {
                return $x . 'bar';
            })
            ->fold(identity, identity));
    }

    public function testCurryExamples()
    {
        $explode = curry('explode');
        $explodeBySpace = $explode(' ');
        $this->assertEquals($explodeBySpace('foo bar'), ['foo', 'bar']);
    }

    public function testComposeExample()
    {
        $data = [
            [ "title" => "Foo Bar" ],
            [ "title" => "Foo Baz" ]
        ];
        $explode = curry('explode');
        $implode = curry('implode');
        $snakeCase = compose(
            $implode('_'),
            map('strtolower'),
            $explode(' '),
            prop('title')
        );
        $getSnakeTitles = map($snakeCase);
        $this->assertEquals($getSnakeTitles($data), ["foo_bar", "foo_baz"]);
    }

    public function testPropExample()
    {
        $item = [ "name" => "Foo", "value" => 15 ];
        $this->assertEquals(prop('value', $item), 15);

        $getValue = prop('value');
        $this->assertEquals($getValue($item), 15);

        $data = [
            [ "name" => "Foo", "value" => 15 ],
            [ "name" => "Bar", "value" => 10 ]
        ];
        $this->assertEquals(map(prop('value'), $data), [15, 10]);
        $this->assertEquals(map(prop('name'), $data), ["Foo", "Bar"]);

        // prop and map are both curried
        $getValues = map(prop('value'));
        $getNames = map(prop('name'));
        $this->assertEquals($getValues($data), [15, 10]);
        $this->assertEquals($getNames($data), ["Foo", "Bar"]);
    }

    public function testMaybeExample()
    {
        $getUserName = function ($json) {
            return Maybe::fromNullable(json_decode($json, true))
              ->map(prop('name'))
              ->getOrElse([]);
        };

        $bad_json_user = '{"id": 1, "name" "Foo" }'; // No : after "name"
        $good_json_user = '{ "id": 1, "name": "Foo" }'; // Good json!

        $this->assertEquals([], $getUserName($bad_json_user));
        $this->assertEquals("Foo", $getUserName($good_json_user));
    }

    public function testEitherExample()
    {
        $getContents = function ($file) {
            return is_file($file)
                ? new Right(file_get_contents($file))
                : new Left('Not a file!');
        };

        $parseJSON = function ($json) {
            return Either::fromNullable(json_decode($json, true));
        };

        $handleError = function ($e) {
            return 'Error: ' . $e;
        };

        $a = $getContents('test/fixtures/config.json')
            ->chain($parseJSON)
            ->map(prop('DB_HOST'))
            ->fold($handleError, function ($x) {
                return $x;
            });

        $this->assertEquals("foo", $a);
    }

    public function testValidationExample()
    {
        $validatePasswordLength = function ($attrs) {
            return isset($attrs['password']) && strlen($attrs['password']) >= 10
                ? new Success($attrs)
                : new Failure(["Password must contain at least 10 characters."]);
        };

        $validateEmail = function ($attrs) {
            return isset($attrs['email']) && filter_var($attrs['email'], FILTER_VALIDATE_EMAIL) !== false
                ? new Success($attrs)
                : new Failure(["Must have a valid email address."]);
        };

        $validateName = function ($attrs) {
            return isset($attrs['name']) && strlen($attrs['name']) > 0
                ? new Success($attrs)
                : new Failure(["Name must not be empty."]);
        };

        $db = new class() {
            public function save($attrs)
            {
                return array_merge($attrs, ["id" => 1]);
            }
        };

        $createUser = function ($input) use (
            $db,
            $validateName,
            $validateEmail,
            $validatePasswordLength
        ) {
            return Validation::of($input)
                ->concat($validateName($input))
                ->concat($validateEmail($input))
                ->concat($validatePasswordLength($input))
                ->map(function ($attrs) use ($db) {
                    return $db->save($attrs);
                });
        };

        $this->assertEquals($createUser([]), new Failure([
            'Name must not be empty.',
            'Must have a valid email address.',
            'Password must contain at least 10 characters.'
        ]));
        $this->assertEquals($createUser([
            'name' => 'Foo',
            'email' => 'Bar',
            'password' => 'Baz1234567'
        ]), new Failure(['Must have a valid email address.']));
        $this->assertEquals($createUser([
            'name' => 'Foo',
            'email' => 'bar@example.com',
            'password' => 'Baz1234567'
        ]), new Success([
            'id' => 1,
            'name' => 'Foo',
            'email' => 'bar@example.com',
            'password' => 'Baz1234567'
        ]));
    }

    public function testLiftAExample()
    {
        $this->assertEquals(Maybe::of('foo bar'), liftA('strtolower', Maybe::of('Foo Bar')));
    }

    public function testSumTypeExample()
    {
        $List = SumType('List', [
            'Cons' => ['head', 'tail'],
            'Nil' => []
        ]);

        $List->map = function ($f) {
            return $this->cata([
                'Cons' => function ($head, $tail) use ($f) {
                    return $this->Cons($f($head), map($f, $tail));
                },
                'Nil' => function () {
                    return $this->Nil();
                }
            ]);
        };

        $List->toArray = function () {
            return $this->cata([
                'Cons' => function ($head, $tail) {
                    return array_merge([$head], $tail);
                },
                'Nil' => function () {
                    return [];
                }
            ]);
        };

        $c = $List->Cons(1, [2, 3])
            ->map(function ($x) {
                return $x * 2;
            })
            ->toArray();
        $this->assertEquals([2, 4, 6], $c);
    }
}
