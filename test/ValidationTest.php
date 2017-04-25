<?php

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Maybe\{Maybe, Just, Nothing};
use Phantasy\DataTypes\Either\{Either, Left, Right};
use Phantasy\DataTypes\Validation\{Validation, Success, Failure};

class ValidationTest extends TestCase
{
    public function testValidationOf()
    {
        $f = function ($a, $b) {
            return [$a, $b];
        };
        $x = Validation::of($f);

        $this->assertInstanceOf(Success::class, $x);
        $this->assertEquals(new Success($f), $x);
    }

    public function testValidationFromNullableFailure()
    {
        $a = Validation::fromNullable(null);
        $this->assertEquals(new Failure(null), $a);
    }

    public function testValidationFromNullableSuccess()
    {
        $this->assertEquals(new Success('foo'), Validation::of('foo'));
    }

    public function testValidationTryCatchSuccess()
    {
        $a = Validation::tryCatch(
            function () {
                return "foo bar";
            }
        );
        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(new Success("foo bar"), $a);
        $this->assertEquals(Validation::of("foo bar"), $a);
    }

    public function testValidationTryCatchFailure()
    {
        $a = Validation::tryCatch(
            function () {
                throw new \Exception('baz');
            }
        );
        $this->assertInstanceOf(Failure::class, $a);
        $this->assertEquals(new Failure(new \Exception('baz')), $a);
    }

    public function testSuccessMapIdentity()
    {
        $a = Validation::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of(124), $a);
        $this->assertEquals(new Success(124), $a);
    }

    public function testSuccessMapComposition()
    {
        $f = function ($x) {
            return $x . "foo";
        };

        $g = function ($x) {
            return $x . "bar";
        };

        $a = Validation::of("baz")->map(
            function ($x) use ($f, $g) {
                return $f($g($x));
            }
        );
        $b = Validation::of("baz")->map($g)->map($f);

        $this->assertEquals($a, $b);
        $this->assertEquals(Validation::of("bazbarfoo"), $a);
        $this->assertEquals(new Success("bazbarfoo"), $a);
    }

    public function testSuccessMapChaining()
    {
        $a = Validation::of(123)
        ->map(
            function ($x) {
                return $x + 1;
            }
        )
        ->map(
            function ($x) {
                return $x / 2;
            }
        );

        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of(62), $a);
        $this->assertEquals(new Success(62), $a);
    }

    public function testSuccessMapImmutability()
    {
        $a = Validation::of(123);
        $b = $a->map(
            function ($x) {
                return $x + 1;
            }
        );

        $this->assertInstanceOf(Success::class, $a);
        $this->assertInstanceOf(Success::class, $b);
        $this->assertEquals(Validation::of(123), $a);
        $this->assertEquals(Validation::of(124), $b);
        $this->assertEquals(new Success(123), $a);
        $this->assertEquals(new Success(124), $b);
    }

    public function testSuccessFoldOnlyAppliesRightFunction()
    {
        $a = Validation::of(123)
        ->fold(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );

        $this->assertEquals($a, 'Success');
    }

    public function testSuccessCataOnlyAppliesRightFunction()
    {
        $a = Validation::of(123)
        ->cata(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );

        $this->assertEquals($a, 'Success');
    }

    public function testSuccessAp()
    {
        $a = Validation::of("foo")
        ->ap(
            Validation::of(
                function ($x) {
                    return $x . "bar";
                }
            )
        );
        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of("foobar"), $a);
        $this->assertEquals(new Success("foobar"), $a);
    }

    public function testSuccessApFailure()
    {
        $a = new Failure("foo");
        $b = Validation::of("bar");

        $this->assertEquals($b->ap($a), $a);
    }

    public function testSuccessBimap()
    {
        $a = Validation::of("foo")
        ->bimap(
            function ($lv) {
                return $lv . "baz";
            },
            function ($rv) {
                return $rv . "bar";
            }
        );

        $this->assertInstanceOf(Success::class, $a);
        $this->assertEquals(Validation::of("foobar"), $a);
        $this->assertEquals(new Success("foobar"), $a);
    }

    public function testSuccessToEither()
    {
        $a = Validation::of("foo")->toEither();

        $this->assertInstanceOf(Right::class, $a);
        $this->assertEquals(Either::of("foo"), $a);
        $this->assertEquals(new Right("foo"), $a);
    }

    public function testSuccessToMaybe()
    {
        $a = Validation::of("foo")->toMaybe();
        $this->assertInstanceOf(Just::class, $a);
        $this->assertEquals(Maybe::of("foo"), $a);
        $this->assertEquals(new Just("foo"), $a);
    }

    public function testSuccessToString()
    {
        $a = Validation::of([1, 2]);
        $b = Validation::of('foo');
        $c = Validation::of(12);

        $expectedA = "Success(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Success('foo')";
        $expectedC = "Success(12)";
        ob_start();
        echo $a;
        $actualA = ob_get_contents();
        ob_end_clean();

        ob_start();
        echo $b;
        $actualB = ob_get_contents();
        ob_end_clean();

        ob_start();
        echo $c;
        $actualC = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expectedA, $actualA);
        $this->assertEquals($expectedB, $actualB);
        $this->assertEquals($expectedC, $actualC);
    }

    public function testFailureToString()
    {
        $a = new Failure([1, 2]);
        $b = new Failure('foo');

        $expectedA = "Failure(array (\n  0 => 1,\n  1 => 2,\n))";
        $expectedB = "Failure('foo')";
        ob_start();
        echo $a;
        $actualA = ob_get_contents();
        ob_end_clean();

        ob_start();
        echo $b;
        $actualB = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expectedA, $actualA);
        $this->assertEquals($expectedB, $actualB);
    }

    public function testFailureMap()
    {
        $a = new Failure("foo");
        $b = $a->map(
            function ($x) {
                return $x . "bar";
            }
        );

        $this->assertEquals($a, $b);
        $this->assertInstanceOf(Failure::class, $b);
    }

    public function testFailureAp()
    {
        $a = new Failure("foo");
        $b = $a->ap(Validation::of(function ($x) {
            return $x . "bar";
        }));

        $this->assertInstanceOf(Failure::class, $b);
        $this->assertEquals($a, $b);
    }

    public function testFailureConcatSuccess()
    {
        $a = new Failure("foo");
        $b = Validation::of("test");
        $c = $a->concat($b);

        $this->assertInstanceOf(Failure::class, $c);
        $this->assertEquals(new Failure("foo"), $c);
    }

    public function testFailureConcatFailure()
    {
        $a = new Failure("foo");
        $b = new Failure("bar");

        $this->assertEquals(new Failure("foobar"), $a->concat($b));
    }

    public function testFailureFold()
    {
        $a = (new Failure("foo"))->fold(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );
        $this->assertEquals($a, 'Error');
    }

    public function testFailureCata()
    {
        $a = (new Failure("foo"))
        ->cata(
            function ($x) {
                return 'Error';
            },
            function ($x) {
                return 'Success';
            }
        );
        $this->assertEquals($a, 'Error');
    }

    public function testFailureBimap()
    {
        $a = (new Failure("foo"))
        ->bimap(
            function ($x) {
                return $x . "baz";
            },
            function ($x) {
                return $x . "bar";
            }
        );

        $this->assertInstanceOf(Failure::class, $a);
        $this->assertEquals(new Failure("foobaz"), $a);
    }

    public function testFailureToEither()
    {
        $a = (new Failure("foo"))->toEither();
        $this->assertInstanceOf(Left::class, $a);
        $this->assertEquals(new Left("foo"), $a);
    }

    public function testFailureToMaybe()
    {
        $a = (new Failure("foo"))->toMaybe();
        $this->assertInstanceOf(Nothing::class, $a);
        $this->assertEquals(new Nothing(), $a);
    }
}
