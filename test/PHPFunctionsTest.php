<?php

use PHPUnit\Framework\TestCase;
use function Phantasy\Core\PHP\{
    explode,
    implode,
    addcslashes,
    addslashes,
    bin2hex,
    chop,
    chop2,
    chr,
    chunk_split,
    chunk_split2,
    chunk_split3,
    convert_cyr_string,
    convert_uudecode,
    convert_uuencode,
    count_chars,
    count_chars2,
    crc32,
    crypt,
    hex2bin,
    htmlspecialchars_decode,
    htmlspecialchars_decode2,
    join,
    lcfirst,
    levenshtein,
    levenshtein5,
    ltrim,
    ltrim2,
    md5_file,
    md5_file2,
    md5,
    md52
};

class PHPFunctionsTest extends TestCase
{
    function testExplode()
    {
        $this->assertEquals(explode(' ', 'foo bar'), \explode(' ', 'foo bar'));
        $this->assertEquals(explode(' ', 'foo bar'), ['foo', 'bar']);

        $explodeBySpace = explode(' ');
        $this->assertEquals($explodeBySpace('foo bar'), ['foo', 'bar']);
    }

    function testImplode()
    {
        $arr = ['one', 'two', 'three'];
        $this->assertEquals(implode(',', $arr), \implode(',', $arr));
        $this->assertEquals(implode(',', $arr), 'one,two,three');

        $joinByComma = implode(',');
        $this->assertEquals($joinByComma($arr), 'one,two,three');
    }

    function testAddCSlashes()
    {
        $this->assertEquals(addcslashes('A..z', 'foo[ ]'), \addcslashes('foo[ ]', 'A..z'));
        $this->assertEquals(addcslashes('A..z', 'foo[ ]'), '\f\o\o\[ \]');
        $addCSlashesAToZ = addcslashes('A..z');

        $this->assertEquals($addCSlashesAToZ('foo[ ]'), '\f\o\o\[ \]');
    }

    function testAddSlashes()
    {
        $str = "Is your name O'Reilly?";

        $this->assertEquals(addslashes($str), \addslashes($str));
        $this->assertEquals(addslashes($str), "Is your name O\'Reilly?");

        $addSlashes = addslashes();
        $this->assertEquals($addSlashes($str), "Is your name O\'Reilly?");
    }

    function testBin2Hex()
    {
        $this->assertEquals(bin2hex('test'), \bin2hex('test'));
        $this->assertEquals(bin2hex('test'), '74657374');

        $bin2Hex = bin2hex();
        $this->assertEquals($bin2Hex('test'), '74657374');
    }

    function testChop()
    {
        $this->assertEquals(chop("test\n"), \chop("test\n"));
        $this->assertEquals(chop("test\n"), "test");

        $chop = chop();
        $this->assertEquals($chop("test\n"), "test");
    }

    function testChop2()
    {
        $this->assertEquals(chop2("World!", "Hello World!"), \chop("Hello World!", "World!"));
        $this->assertEquals(chop2("World!", "Hello World!"), "Hello ");

        $chopWorld = chop2("World!");
        $this->assertEquals($chopWorld("Hello World!"), "Hello ");
    }

    function testChr()
    {
        $this->assertEquals(chr(046), \chr(046));
        $this->assertEquals(chr(046), "&");

        $chr = chr();
        $this->assertEquals($chr(046), "&");
    }

    function testChunkSplit()
    {
        $this->assertEquals(chunk_split("test"), \chunk_split("test"));
        $this->assertEquals(chunk_split("test"), "test\r\n");

        $chunkSplit = chunk_split();
        $this->assertEquals($chunkSplit("test"), "test\r\n");
    }

    function testChunkSplit2()
    {
        $this->assertEquals(chunk_split2(2, "test"), \chunk_split("test", 2));
        $this->assertEquals(chunk_split2(2, "test"), "te\r\nst\r\n");

        $chunkSplitLen2 = chunk_split2(2);
        $this->assertEquals($chunkSplitLen2("test"), "te\r\nst\r\n");
    }

    function testChunkSplit3()
    {
        $this->assertEquals(chunk_split3(2, '.', 'test'), \chunk_split('test', 2, '.'));
        $this->assertEquals(chunk_split3(2, '.', "test"), "te.st.");

        $chunkSplitLen2 = chunk_split3(2);
        $chunkSplitLen2Dot = $chunkSplitLen2('.');
        $this->assertEquals($chunkSplitLen2('.', "test"), "te.st.");
        $this->assertEquals($chunkSplitLen2Dot("test"), "te.st.");
    }

    function testConvertCyrString()
    {
        $str = "Good Morning..";
        $expected = "Good Morning..";
        $this->assertEquals(convert_cyr_string('w', 'k', $str), \convert_cyr_string($str, 'w', 'k'));
        $this->assertEquals(convert_cyr_string('w', 'k', $str), $expected);

        $convertFromWToA = convert_cyr_string('w', 'k');
        $this->assertEquals($convertFromWToA($str), $expected);
    }

    function testConvertUUDecode()
    {
        $this->assertEquals(convert_uudecode("+22!L;W9E(%!(4\"$`\n`"), \convert_uudecode("+22!L;W9E(%!(4\"$`\n`"));
        $this->assertEquals(convert_uudecode("+22!L;W9E(%!(4\"$`\n`"), 'I love PHP!');
    }

    function testConvertUUEncode()
    {
        $this->assertEquals(convert_uuencode('I love PHP!'), \convert_uuencode('I love PHP!'));
        $this->assertEquals(convert_uuencode('I love PHP!'), "+22!L;W9E(%!(4\"$`\n`\n");
    }

    function testCountChars()
    {
        $this->assertEquals(count_chars('Test'), \count_chars('Test'));
        $res = count_chars('Test');
        $this->assertCount(256, $res);

        $this->assertEquals(array_filter($res, function($x) {
            return $x > 0;
        }), [
            '84' => 1,
            '101' => 1,
            '115' => 1,
            '116' => 1
        ]);
    }

    function testCountChars2()
    {
        $this->assertEquals(count_chars2(1, 'Test'), \count_chars('Test', 1));
        $this->assertEquals(count_chars2(1, 'Test'), [
            '84' => 1,
            '101' => 1,
            '115' => 1,
            '116' => 1
        ]);

        $countCharsGreaterThan0 = count_chars2(1);
        $this->assertEquals($countCharsGreaterThan0('Test'), [
            '84' => 1,
            '101' => 1,
            '115' => 1,
            '116' => 1
        ]);
    }

    function testCrc32()
    {
        $this->assertEquals(crc32('test'), \crc32('test'));
        $this->assertEquals(crc32('test'), 3632233996);

        $crc32 = crc32();
        $this->assertEquals($crc32('test'), 3632233996);
    }

    function testCrypt()
    {
        $this->assertEquals(crypt('Foo', 'Test'), \crypt('Test', 'Foo'));
        $this->assertEquals(crypt('Foo', 'Test'), 'Foh5xTm5Uw31I');
    }

    function testHex2Bin()
    {
        $this->assertEquals(hex2bin('6578616d706c65206865782064617461'), \hex2bin('6578616d706c65206865782064617461'));
        $this->assertEquals(hex2bin('6578616d706c65206865782064617461'), 'example hex data');
    }

    function testHtmlSpecialCharsDecode()
    {
        $this->assertEquals(
            htmlspecialchars_decode('<p>this -&gt; &quot;</p>\n'),
            \htmlspecialchars_decode('<p>this -&gt; &quot;</p>\n')
        );
        $this->assertEquals(
            htmlspecialchars_decode('<p>this -&gt; &quot;</p>\n'),
            '<p>this -> "</p>\n'
        );
    }

    function testHtmlSpecialCharsDecode2()
    {
        $this->assertEquals(
            htmlspecialchars_decode2(ENT_NOQUOTES, '<p>this -&gt; &quot;</p>\n'),
            \htmlspecialchars_decode('<p>this -&gt; &quot;</p>\n', ENT_NOQUOTES)
        );

        $removeWithoutQuotes = htmlspecialchars_decode2(ENT_NOQUOTES);
        $this->assertEquals(
            $removeWithoutQuotes('<p>this -&gt; &quot;</p>\n'),
            '<p>this -> &quot;</p>\n'
        );
    }

    function testJoin()
    {
        $arr = ['one', 'two', 'three'];
        $this->assertEquals(join(',', $arr), \join(',', $arr));
        $this->assertEquals(join(',', $arr), 'one,two,three');

        $joinByComma = join(',');
        $this->assertEquals($joinByComma($arr), 'one,two,three');
    }

    function testLcFirst()
    {
        $this->assertEquals(lcfirst('HelloWorld'), \lcfirst('HelloWorld'));
        $this->assertEquals(lcfirst('HelloWorld'), 'helloWorld');

        $lcFirst = lcfirst();
        $this->assertEquals($lcFirst('HelloWorld'), 'helloWorld');
    }

    function testLevenshtein()
    {
        $this->assertEquals(levenshtein('carrrot', 'carrot'), \levenshtein('carrrot', 'carrot'));
    }

    function testLevenshtein5()
    {
        $a = '1 apple';
        $b = '2 apples';
        $this->assertEquals(levenshtein5(1, 0, 0, $a, $b), \levenshtein($a, $b, 1, 0, 0));
    }

    function testLTrim()
    {
        $a = "\t\tThese are a few words :) ...  ";
        $this->assertEquals(ltrim($a), \ltrim($a));
        $this->assertEquals(ltrim($a), "These are a few words :) ...  ");
    }

    function testLTrim2()
    {
        $a = "\t\tThese are a few words :) ...  ";
        $this->assertEquals(ltrim2(" \t.", $a), \ltrim($a, " \t."));
        $this->assertEquals(ltrim2(" \t.", $a), "These are a few words :) ...  ");
    }

    function testMd5File()
    {
        $this->assertEquals(
            md5_file(realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            \md5_file(realpath(dirname(__FILE__)) . '/fixtures/config.json')
        );

        $this->assertEquals(
            \md5_file(realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            '625b7290ee67ba3bb84ebe1fa5c32fbe'
        );
    }

    function testMd5File2()
    {
        $this->assertEquals(
            md5_file2(true, realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            \md5_file(realpath(dirname(__FILE__)) . '/fixtures/config.json', true)
        );
    }

    function testMd5()
    {
        $str = 'apple';
        $expected = '1f3870be274f6c49b3e31a0c6728957f';

        $this->assertEquals(md5($str), \md5($str));
        $this->assertEquals(md5($str), $expected);
    }

    function testMd52()
    {
        $str = 'apple';
        $this->assertEquals(md52(true, $str), \md5($str, true));
    }
}
