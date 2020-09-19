<?php declare(strict_types=1);

namespace Phantasy\Test;

use PHPUnit\Framework\TestCase;
use Phantasy\DataTypes\Text\Text;
use function Phantasy\DataTypes\Text\Text;
use Phantasy\Test\Traits\LawAssertions;

class TextTest extends TestCase
{
    use LawAssertions;

    public function testLaws()
    {
        $this->assertSetoidLaws(Text());
        $this->assertOrdLaws(Text());
        $this->assertSemigroupLaws(Text());
        $this->assertMonoidLaws(Text::class, Text());
        $this->assertFunctorLaws(Text());
    }

    public function testTextFunc()
    {
        $this->assertEquals(
            Text("foo"),
            new Text("foo")
        );
    }

    public function testTextCurried()
    {
        $text = Text();
        $this->assertEquals($text("foo"), new Text("foo"));
    }

    public function testTextLteCurried()
    {
        $text = Text("Foo");
        $lte = $text->lte();
        $text2 = Text("Bar");

        $this->assertEquals(
            $text->lte($text2),
            $lte($text2)
        );

        $this->assertEquals($text->lte($text2), strcmp("Foo", "Bar") <= 0);
    }

    public function testTextEqualsCurried()
    {
        $text = Text("Foo");
        $equals = $text->equals();
        $text2 = Text("Bar");

        $this->assertEquals(
            $text->equals($text2),
            $equals($text2)
        );

        $this->assertFalse($text->equals($text2));
    }

    public function testTextConcatCurried()
    {
        $text = Text("Foo");
        $concat = $text->concat();
        $text2 = Text("Bar");

        $this->assertEquals(
            $text->concat($text2),
            $concat($text2)
        );

        $this->assertEquals($text->concat($text2), Text("FooBar"));
    }

    public function testTextMapCurried()
    {
        $text = Text("Foo");
        $map = $text->map();
        $f = function ($s) {
            return $s . "Bar";
        };

        $this->assertEquals(
            $text->map($f),
            $map($f)
        );

        $this->assertEquals($text->map($f), Text("FooBar"));
    }
}