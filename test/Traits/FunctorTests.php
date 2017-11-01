<?php

namespace Phantasy\Test\Traits;

use function Phantasy\Core\{of, id};

trait FunctorTests
{
    public function testFunctorLaws()
    {
        $successes = 0;
        if (!empty($this->testClasses ?? [])) {
            foreach ($this->testClasses ?? [] as $x) {
                // The non-lazy ones
                try {
                    // Identity
                    $a = new $x(1);
                    $b = (new $x(1))->map(id());
                    $this->assertEquals($a, $b);

                    // Composition
                    $u = new $x('foo');
                    $f = function ($x) {
                        return $x . 'bar';
                    };
                    $g = function ($x) {
                        return $x . 'baz';
                    };
                    $this->assertEquals($u->map(function ($x) use ($f, $g) {
                        return $f($g($x));
                    }), $u->map($f)->map($g));
                    $successes++;
                } catch (\Throwable $e) {
                } catch (\Exception $e) {}

                // the lazy-ones
                try {
                    // Identity
                    $a = new $x(function () {
                        return 1;
                    });
                    $b = (new $x(function () {
                        return 1;
                    }))->map(id());
                    $this->assertEquals($a, $b);

                    // Composition
                    $u = new $x(function () {
                        return 'foo';
                    });
                    $f = function ($x) {
                        return $x . 'bar';
                    };
                    $g = function ($x) {
                        return $x . 'baz';
                    };
                    $this->assertEquals($u->map(function ($x) use ($f, $g) {
                        return $f($g($x));
                    }), $u->map($f)->map($g));
                    $successes++;
                } catch (\Throwable $e) {
                } catch (\Exception $e) {}
            }
        }
        if ($successes === 0 || $successes !== count($this->testClasses ?? [])) {
            $this->fail();
        }
    }

    /**
     * @depends testSetupFunctor
     */
    public function testFunctorIdentity($data)
    {
        $a = $data[0];
        $this->assertEquals($a->map(id()), $a);
    }

    /**
     * @depends testSetupFunctor
     */
    public function testFunctorComposition($data)
    {

    }
}