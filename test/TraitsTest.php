<?php

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\Traits\CurryNonPublicMethods;

class A
{
    use CurryNonPublicMethods;

    private static function test1($x)
    {
        return $x;
    }

    protected static function test2($x)
    {
        return $x;
    }
}

class B
{
    use CurryNonPublicMethods;
    protected $methodsToCurry = ['test1'];

    private static function test1($x)
    {
        return $x;
    }

    protected static function test2($x)
    {
        return $x;
    }
}

class TraitsTest extends TestCase
{
    public function testCanRetrieveMethodsWithoutCallingThem()
    {
        $a = new class() {
            use CurryNonPublicMethods;

            private function test($x)
            {
                return 'foo' . $x;
            }

            private static function test2($x)
            {
                return 'bar' . $x;
            }
        };

        $f = function ($g) {
            return $g('bar');
        };

        $b = $a->test;
        $c = $b();
        $this->assertEquals($f($a->test), 'foobar');
        $this->assertEquals($f($b), 'foobar');
        $this->assertEquals($f($c), 'foobar');

        $b_ = $a->test2;
        $c_ = $b_();
        $this->assertEquals($f($a->test2), 'barbar');
        $this->assertEquals($f($b_), 'barbar');
        $this->assertEquals($f($c_), 'barbar');
    }

    public function testCurryNonPublicMethodsMagicGetOnMethodsThatAreNotToBeCurried()
    {
        $a = new class() {
            use CurryNonPublicMethods;
            protected $methodsToCurry = ['test1'];

            private function test($x)
            {
                return 'foo' . $x;
            }

            private function test1($x)
            {
                return 'baz' . $x;
            }
        };

        $f = function ($g) {
            return $g('bar');
        };

        $b = $a->test1;
        $c = $b();
        $this->assertEquals($f($a->test1), 'bazbar');
        $this->assertEquals($f($b), 'bazbar');
        $this->assertEquals($f($c), 'bazbar');

        $this->assertNull($a->test);
        $this->assertNull($a->test());

        try {
            $this->assertNull($a->test5);
            $this->assertNull($a->test5());
            $this->fail();
        } catch (\Exception $e) {
        }
    }

    public function testCurryNonPublicMethodsWorksOnPrivateMethods()
    {
        $a = new class() {
            use CurryNonPublicMethods;

            private function test($a)
            {
                return $a;
            }
        };
        $this->assertEquals(
            $a->test(1),
            1
        );

        $b = $a->test();
        $this->assertEquals(
            $b(2),
            2
        );
    }

    public function testCurryNonPublicMethodsWorksOnProtectedMethods()
    {
        $a = new class() {
            use CurryNonPublicMethods;

            protected function test($a)
            {
                return $a;
            }
        };
        $this->assertEquals(
            $a->test(1),
            1
        );

        $b = $a->test();
        $this->assertEquals(
            $b(2),
            2
        );
    }

    public function testCurryNonPublicMethodsWorksOnPrivateStaticMethods()
    {
        $a = new class() {
            use CurryNonPublicMethods;

            private static function test($a)
            {
                return $a;
            }
        };
        $this->assertEquals(
            $a->test(1),
            1
        );

        $b = $a->test();
        $this->assertEquals(
            $b(2),
            2
        );
    }

    public function testCurryNonPublicMethodsWorksOnProtectedStaticMethods()
    {
        $a = new class() {
            use CurryNonPublicMethods;

            protected static function test($a)
            {
                return $a;
            }
        };
        $this->assertEquals(
            $a->test(1),
            1
        );

        $b = $a->test();
        $this->assertEquals(
            $b(2),
            2
        );
    }

    public function testCurryNonPublicMethodsWorksOnPrivateMethodsWhenCalledStatically()
    {
        $this->assertEquals(A::test1('x'), 'x');
        $a = A::test1();
        $this->assertEquals($a('x'), 'x');
    }

    public function testCurryNonPublicMethodsWorksOnProtectedMethodsWhenCalledStatically()
    {
        $this->assertEquals(A::test2('x'), 'x');
        $a = A::test2();
        $this->assertEquals($a('x'), 'x');
    }

    public function testCurryNonPublicMethodsIgnoresMethodsNotInDefinedMethodsToCurry()
    {
        $a = new class() {
            use CurryNonPublicMethods;
            protected $methodsToCurry = ['test', 'test1'];

            private function test($x)
            {
                return $x;
            }

            private function test1($x)
            {
                return $x;
            }

            private function test2($x)
            {
                return $x;
            }
        };

        try {
            $a->test2();
            $this->fail();
        } catch (\Exception $e) {
        }

        $this->assertEquals($a->test(1), 1);
        $test = $a->test();
        $this->assertEquals($test(1), 1);

        $this->assertEquals($a->test1(1), 1);
        $test1 = $a->test1();
        $this->assertEquals($test1(1), 1);
    }

    public function testCurryNonPublicMethodsIgnoresStaticMethodsNotInDefinedMethodsToCurry()
    {
        $this->assertEquals(B::test1('x'), 'x');
        $test1 = B::test1();
        $this->assertEquals($test1('x'), 'x');

        try {
            B::test2('x');
            $this->fail();
        } catch (\Exception $e) {
        }

        try {
            B::test3('x');
            $this->fail();
        } catch (\Exception $e) {
        }
    }
}
