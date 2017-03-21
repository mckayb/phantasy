<?php

use PHPUnit\Framework\TestCase;
use PHPFP\DataTypes\Maybe\{Maybe, Just, Nothing};
use PHPFP\DataTypes\Either\{Either, Left, Right};
use PHPFP\DataTypes\Validation\{Validation, Success, Failure};

class ValidationTest extends TestCase {
  public function testValidationOf() {
    $f = function($a, $b) {
      return [$a, $b];
    };
    $x = Validation::of($f);

    $this->assertInstanceOf(Success::class, $x);
    $this->assertEquals(new Success($f), $x);
  }

  public function testSuccessMapIdentity() {
    $a = Validation::of(123)
      ->map(function($x) {
        return $x + 1;
      });

    $this->assertInstanceOf(Success::class, $a);
    $this->assertEquals(Validation::of(124), $a);
    $this->assertEquals(new Success(124), $a);
  }

  public function testSuccessMapComposition() {
    $f = function($x) {
      return $x . "foo";
    };

    $g = function($x) {
      return $x . "bar";
    };

    $a = Validation::of("baz")->map(function($x) use ($f, $g) {
      return $f($g($x));
    });
    $b = Validation::of("baz")->map($g)->map($f);

    $this->assertEquals($a, $b);
    $this->assertEquals(Validation::of("bazbarfoo"), $a);
    $this->assertEquals(new Success("bazbarfoo"), $a);
  }

  public function testSuccessMapChaining() {
    $a = Validation::of(123)
      ->map(function($x) {
        return $x + 1;
      })
      ->map(function($x) {
        return $x / 2;
      });

    $this->assertInstanceOf(Success::class, $a);
    $this->assertEquals(Validation::of(62), $a);
    $this->assertEquals(new Success(62), $a);
  }

  public function testSuccessMapImmutability() {
    $a = Validation::of(123);
    $b = $a->map(function($x) {
      return $x + 1;
    });

    $this->assertInstanceOf(Success::class, $a);
    $this->assertInstanceOf(Success::class, $b);
    $this->assertEquals(Validation::of(123), $a);
    $this->assertEquals(Validation::of(124), $b);
    $this->assertEquals(new Success(123), $a);
    $this->assertEquals(new Success(124), $b);
  }

  public function testSuccessFoldOnlyAppliesRightFunction() {
    $a = Validation::of(123)
      ->fold(function($x) {
        return 'Error';
      }, function($x) {
        return 'Success';
      });

    $this->assertEquals($a, 'Success');
  }

  public function testSuccessCataOnlyAppliesRightFunction() {
    $a = Validation::of(123)
      ->cata(function($x) {
        return 'Error';
      }, function($x) {
        return 'Success';
      });

    $this->assertEquals($a, 'Success');
  }

  public function testSuccessAp() {
    $a = Validation::of("foo")
      ->ap(Validation::of(function($x) {
        return $x . "bar";
      }));
    $this->assertInstanceOf(Success::class, $a);
    $this->assertEquals(Validation::of("foobar"), $a);
    $this->assertEquals(new Success("foobar"), $a);
  }

  public function testSuccessApFailure() {
    $a = new Failure("foo");
    $b = Validation::of("bar");

    $this->assertEquals($b->ap($a), $a);
  }

  public function testSuccessBimap() {
    $a = Validation::of("foo")
      ->bimap(function($lv) {
        return $lv . "baz";
      }, function($rv) {
        return $rv . "bar";
      });

    $this->assertInstanceOf(Success::class, $a);
    $this->assertEquals(Validation::of("foobar"), $a);
    $this->assertEquals(new Success("foobar"), $a);
  }

  public function testSuccessToEither() {
    $a = Validation::of("foo")->toEither();

    $this->assertInstanceOf(Right::class, $a);
    $this->assertEquals(Either::of("foo"), $a);
    $this->assertEquals(new Right("foo"), $a);
  }

  public function testSuccessToMaybe() {
    $a = Validation::of("foo")->toMaybe();
    $this->assertInstanceOf(Just::class, $a);
    $this->assertEquals(Maybe::of("foo"), $a);
    $this->assertEquals(new Just("foo"), $a);
  }

  public function testFailureMap() {
    $a = new Failure("foo");
    $b = $a->map(function($x) {
      return $x . "bar";
    });

    $this->assertEquals($a, $b);
    $this->assertInstanceOf(Failure::class, $b);
  }

  public function testFailureApSuccess() {
    $a = new Failure("foo");
    $b = $a->ap(Validation::of("bar"));


    $this->assertInstanceOf(Failure::class, $b);
    $this->assertEquals($a, $b);
  }

  public function testFailureApFailure() {
    $a = new Failure("foo");
    $b = $a->ap(new Failure("bar"));

    $this->assertInstanceOf(Failure::class, $b);
    $this->assertEquals(new Failure("foobar"), $b);
  }

  public function testFailureFold() {
    $a = (new Failure("foo"))->fold(function($x) {
      return 'Error';
    }, function($x) {
      return 'Success';
    });
    $this->assertEquals($a, 'Error');
  }

  public function testFailureCata() {
    $a = (new Failure("foo"))
      ->cata(function($x) {
        return 'Error';
      }, function($x) {
        return 'Success';
      });
    $this->assertEquals($a, 'Error');
  }

  public function testFailureBimap() {
    $a = (new Failure("foo"))
      ->bimap(function($x) {
        return $x . "baz";
      }, function($x) {
        return $x . "bar";
      });

    $this->assertInstanceOf(Failure::class, $a);
    $this->assertEquals(new Failure("foobaz"), $a);
  }

  public function testFailureToEither() {
    $a = (new Failure("foo"))->toEither();
    $this->assertInstanceOf(Left::class, $a);
    $this->assertEquals(new Left("foo"), $a);
  }

  public function testFailureToMaybe() {
    $a = (new Failure("foo"))->toMaybe();
    $this->assertInstanceOf(Nothing::class, $a);
    $this->assertEquals(new Nothing(), $a);
  }
}
