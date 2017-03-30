<?php

use PHPUnit\Framework\TestCase;
use function Phantasy\Core\{
  identity,
  compose,
  curry,
  semigroupConcat,
  prop,
  map,
  filter,
  reduce
};

class FunctionsTest extends TestCase {
  public function testIdentity() {
    $this->assertEquals(1, identity(1));
    $this->assertEquals(false, identity(false));
    $this->assertEquals(true, identity(true));
    $this->assertEquals("Foo bar", identity("Foo bar"));
  }

  public function testCompose() {
    $a = function($x) {
      return $x + 1;
    };
    $b = function($x) {
      return $x + 2;
    };

    $f = compose($a, $b);
    $this->assertEquals($f(2), 5);
  }

  public function testComposeMultiple() {
    $a = function($x) {
      return $x / 3;
    };
    $b = function($x) {
      return $x * 2;
    };
    $c = function($x) {
      return $x + 4;
    };
    $f = compose($a, $b, $c);
    $this->assertEquals($f(2), 4);
  }

  public function testCurry() {
    $add = curry(function($a, $b, $c) {
      return $a + $b + $c;
    });

    $add1 = $add(1);
    $add11 = $add(1, 1);

    $add12 = $add1(2);
    $add112 = $add11(2);

    $add111 = $add(1, 1, 1);
    $add122 = $add1(2, 2);
    $add123 = $add12(3);

    $this->assertEquals($add111, 3);
    $this->assertEquals($add122, 5);
    $this->assertEquals($add123, 6);
  }

  public function testSemigroupConcatArrays() {
    $a = semigroupConcat([1, 2], [3, 4]);
    $this->assertEquals([1, 2, 3, 4], $a);
  }

  public function testSemigroupConcatStrings() {
    $a = semigroupConcat('foo', 'bar');
    $this->assertEquals('foobar', $a);
  }

  public function testSemigroupConcatObjects() {
    $f = function ($x) {
      return new class($x) {
        public $val = '';
        public function __construct($val) {
          $this->val = $val;
        }

        public function concat($x) {
          $this->val .= $x->val;
          return $this;
        }
      };
    };
    $a = $f('foo');
    $b = $f('bar');

    $m = semigroupConcat($a, $b);

    $this->assertEquals($f('foobar'), $m);
  }

  /**
   * @expectedException Exception
   */
  public function testSemigroupConcatNotAvailable() {
    $a = true;
    $b = false;
    semigroupConcat($a, $b);
  }

  public function testPropObjects() {
    $f = new class() {
      public $test = 'foo';
    };
    $g = new class() {
      public $test = 'bar';
    };

    $this->assertEquals(array_map(prop('test'), [$f, $g]), ['foo', 'bar']);
  }

  public function testPropArrays() {
    $f = [ "test" => "foo" ];
    $g = [ "test" => "bar" ];
    $this->assertEquals(array_map(prop('test'), [$f, $g]), ['foo', 'bar']);
  }

  public function testPropCurried() {
    $f = [ "test" => "foo" ];
    $g = [ "test" => "bar" ];
    $getTest = prop('test');
    $this->assertEquals($getTest($f), "foo");
    $this->assertEquals($getTest($g), "bar");
  }

  public function testMapArrays() {
    $f = function($x) {
      return $x + 1;
    };

    $this->assertEquals(map($f, [1, 2, 3]), [2, 3, 4]);
  }

  public function testMapCurried() {
    $plusOne = map(function($x) {
      return $x + 1;
    });
    $this->assertEquals($plusOne([1, 2, 3]), [2, 3, 4]);
  }

  public function testMapCurriedEmptyParams() {
    $a = map();
    $b = $a();
    $c = $b();
    $d = $c(function($x) {
      return $x + 1;
    });
    $e = $d();
    $f = $e();
    $g = $d([2, 4, 6]);
    $this->assertEquals([3, 5, 7], $g);
  }

  public function testMapExample() {
    $getBox = function($x) {
      return new class($x) {
        private $item = null;
        public function __construct($item) {
          $this->item = $item;
        }
        public function map($f) {
          return new static($f($this->item));
        }
      };
    };
    $add = function($x) {
      return $x + 1;
    };

    $this->assertEquals(map($add, $getBox(1)), $getBox(2));
  }

  public function testFilterArrays() {
    $f = function($x) {
      return $x % 2 === 0;
    };

    $this->assertEquals(filter($f, [1, 2, 3, 4]), [2, 4]);
  }

  public function testFilterCurried() {
    $isOdd = filter(function($x) {
      return $x % 2 === 1;
    });

    $this->assertEquals($isOdd([1, 2, 3, 4]), [1, 3]);
  }

	public function testFilterWithObject() {
		$getBox = function($x) {
			return new class($x) {
				private $items = [];
				public function __construct($items) {
					$this->items = $items;
				}
				public function filter($f) {
					return new static(array_values(array_filter($this->items, $f)));
				}
      };
    };
		$isEven = function($x) {
			return $x % 2 === 0;
		};
		$box = $getBox([1, 2, 3]);
		$this->assertEquals(filter($isEven, $box), $getBox([2]));
  }

  public function testReduceArrays() {
    $sum = reduce(function($x, $y) {
      return $x + $y;
    }, 0, [1, 2, 3]);
    $this->assertEquals($sum, 6);
  }

  public function testReduceCurried() {
    $sum = reduce(function($x, $y) {
      return $x + $y;
    });
    $sumWithInitial6 = $sum(6);
    $this->assertEquals($sumWithInitial6([1, 2, 3]), 12);
  }

  public function testReduceWithObject() {
    $getClass = function($x) {
      return new class($x) {
        private $items = [];

        public function __construct($items) {
          $this->items = $items;
        }

        public function reduce($f, $i) {
          return array_reduce($this->items, $f, $i);
        }
      };
    };
    $isOdd = function($x) {
      return $x % 2 === 1;
    };
    $oddSum = function($sum, $x) use ($isOdd) {
      if ($isOdd($x)) {
        return $sum + $x;
      }
      return $sum;
    };
    $box = $getClass([1, 2, 3]);
    $this->assertEquals(reduce($oddSum, 0, $box), 4);
  }
}
