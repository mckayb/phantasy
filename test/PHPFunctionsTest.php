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
    md52,
    metaphone,
    metaphone2,
    money_format,
    nl_langinfo,
    nl2br,
    nl2br2,
    number_format,
    number_format2,
    number_format4,
    ord,
    parse_str,
    quotemeta,
    rtrim,
    rtrim2,
    sha1_file,
    sha1_file2,
    sha1,
    sha12,
    similar_text,
    similar_text_pct,
    soundex,
    str_getcsv,
    str_getcsv2,
    str_getcsv3,
    str_getcsv4,
    str_ireplace,
    str_ireplace_count,
    str_pad,
    str_pad3,
    str_pad4,
    str_repeat,
    str_replace,
    str_replace_count,
    str_rot13,
    str_shuffle,
    str_split,
    str_split2,
    str_word_count,
    str_word_count2,
    str_word_count3,
    strcasecmp,
    strchr,
    strchr3,
    strcmp,
    strcoll,
    strcspn,
    strcspn3,
    strcspn4
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
            md5_file(realpath(dirname(__FILE__)) . '/fixtures/config.json'),
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

    function testMetaphone()
    {
        $this->assertEquals(metaphone('programming'), \metaphone('programming'));
        $this->assertEquals(metaphone('programming'), 'PRKRMNK');
    }

    function testMetaphone2()
    {
        $this->assertEquals(metaphone2(5, 'programming'), \metaphone('programming', 5));
        $this->assertEquals(metaphone2(5, 'programming'), 'PRKRM');

        $metaphone5Phonemes = metaphone2(5);
        $this->assertEquals($metaphone5Phonemes('programming'), 'PRKRM');
    }

    function testMoneyFormat()
    {
        $num = 1234.56;
        setlocale(LC_MONETARY, 'en_IN');
        $this->assertEquals(money_format('%i', $num), \money_format('%i', $num));
        $this->assertEquals(money_format('%i', $num), 'INR 1,234.56');
    }

    function testNLLangInfo()
    {
        $this->assertEquals(nl_langinfo(MON_1), \nl_langinfo(MON_1));
        $this->assertEquals(nl_langinfo(MON_1), "January");

        $nlLangInfo = nl_langinfo();
        $this->assertEquals($nlLangInfo(MON_1), "January");
    }

    function testNL2BR()
    {
        $str = "foo isn't\n bar";
        $this->assertEquals(nl2br($str), \nl2br($str));
        $this->assertEquals(nl2br($str), "foo isn't<br />\n bar");
    }

    function testNL2BR2()
    {
        $str = "Welcome\r\nThis is my HTML document";
        $expected = "Welcome<br>\r\nThis is my HTML document";
        $this->assertEquals(nl2br2(false, $str), \nl2br($str, false));

        $this->assertEquals(nl2br2(false, $str), $expected);

        $nl2brNotXHtml = nl2br2(false);
        $this->assertEquals($nl2brNotXHtml($str), $expected);
    }

    function testNumberFormat()
    {
        $num = 1234.56;
        $this->assertEquals(number_format($num), \number_format($num));
        $this->assertEquals(number_format($num), '1,235');

        $numFormat = number_format();
        $this->assertEquals($numFormat($num), '1,235');
    }

    function testNumberFormat2()
    {
        $num = 1234.56;
        $this->assertEquals(number_format2(2, $num), \number_format($num, 2));
        $this->assertEquals(number_format2(2, $num), '1,234.56');

        $formatTo2Decimals = number_format2(2);
        $this->assertEquals($formatTo2Decimals($num), '1,234.56');
    }

    function testNumberFormat4()
    {
        $num = 1234.56;
        $this->assertEquals(number_format4(2, ',', ' ', $num), \number_format($num, 2, ',', ' '));
        $this->assertEquals(number_format4(2, ',', ' ', $num), '1 234,56');

        $frNumberFormat = number_format4(2, ',', ' ');
        $this->assertEquals($frNumberFormat($num), '1 234,56');
    }

    function testOrd()
    {
        $str = "\n";
        $this->assertEquals(ord($str), \ord($str));
        $this->assertEquals(ord($str), 10);

        $ord = ord();
        $this->assertEquals($ord($str), 10);
    }

    function testParseStr()
    {
        $str = "first=value&arr[]=foo+bar&arr[]=baz";
        $output = parse_str($str);
        \parse_str($str, $expected);
        $this->assertEquals($output, $expected);
    }

    function testQuotemeta()
    {
        $str = "Hello world. (can you hear me?)";
        $expected = "Hello world\. \(can you hear me\?\)";
        $this->assertEquals(quotemeta($str), \quotemeta($str));
        $this->assertEquals(quotemeta($str), $expected);

        $quotemeta = quotemeta();
        $this->assertEquals($quotemeta($str), $expected);
    }

    function testRTrim()
    {
        $this->assertEquals(rtrim("test\n"), \rtrim("test\n"));
        $this->assertEquals(rtrim("test\n"), "test");

        $rtrim = rtrim();
        $this->assertEquals($rtrim("test\n"), "test");
    }

    function testRTrim2()
    {
        $this->assertEquals(rtrim2("World!", "Hello World!"), \rtrim("Hello World!", "World!"));
        $this->assertEquals(rtrim2("World!", "Hello World!"), "Hello ");

        $rtrimWorld = rtrim2("World!");
        $this->assertEquals($rtrimWorld("Hello World!"), "Hello ");
    }

    function testSHA1File()
    {
        $this->assertEquals(
            sha1_file(realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            \sha1_file(realpath(dirname(__FILE__)) . '/fixtures/config.json')
        );

        $this->assertEquals(
            sha1_file(realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            '7b25c66924c90da0ffd45fa1e78f858d37cae7bc'
        );
    }

    function testSHA1File2()
    {
        $this->assertEquals(
            sha1_file2(false, realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            \sha1_file(realpath(dirname(__FILE__)) . '/fixtures/config.json', false)
        );

        $sha1File2NoRaw = sha1_file2(false);
        $this->assertEquals(
            $sha1File2NoRaw(realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            \sha1_file(realpath(dirname(__FILE__)) . '/fixtures/config.json', false)
        );
    }

    function testSha1()
    {
        $str = 'apple';
        $expected = 'd0be2dc421be4fcd0172e5afceea3970e2f3d940';

        $this->assertEquals(sha1($str), \sha1($str));
        $this->assertEquals(sha1($str), $expected);

        $sha1 = sha1();
        $this->assertEquals($sha1($str), $expected);
    }

    function testSha12()
    {
        $str = 'apple';
        $this->assertEquals(sha12(true, $str), \sha1($str, true));
    }

    function testSimilarText()
    {
        $a = 'This is a test!';
        $b = 'A test, this is!';
        $this->assertEquals(similar_text($a, $b), \similar_text($a, $b));
        $this->assertEquals(similar_text($a, $b), 7);

        $similarToA = similar_text($a);
        $this->assertEquals($similarToA($b), 7);
    }

    function testSimilarTextPct()
    {
        $a = 'This is a test!';
        $b = 'A test, this is!';
        $sim = \similar_text($a, $b, $pct);
        $expected = 45.16129032258065;
        $this->assertEquals(similar_text_pct($a, $b), $pct);
        $this->assertEquals(similar_text_pct($a, $b), $expected);

        $similarToA = similar_text_pct($a);
        $this->assertEquals($similarToA($b), $expected);
    }

    function testSoundex()
    {
        $str = 'Euler';
        $expected = 'E460';
        $this->assertEquals(soundex($str), \soundex($str));
        $this->assertEquals(soundex($str), $expected);

        $soundex = soundex();
        $this->assertEquals($soundex($str), $expected);
    }

    function testStrGetCSV()
    {
        $str = 'a,b';
        $this->assertEquals(str_getcsv($str), \str_getcsv($str));
        $this->assertEquals(str_getcsv($str), ['a', 'b']);

        $strGetCSV = str_getcsv();
        $this->assertEquals($strGetCSV($str), ['a', 'b']);
    }

    function testStrGetCSV2()
    {
        $str = 'a.b';
        $this->assertEquals(str_getcsv2('.', $str), \str_getcsv($str, '.'));
        $this->assertEquals(str_getcsv2('.', $str), ['a', 'b']);

        $getCSVDotSep = str_getcsv2('.');
        $this->assertEquals($getCSVDotSep($str), ['a', 'b']);
    }

    function testStrGetCSV3()
    {
        $str = '&a&.&b&';
        $this->assertEquals(str_getcsv3('.', '&', $str), \str_getcsv($str, '.', '&'));
        $this->assertEquals(str_getcsv3('.', '&', $str), ['a', 'b']);

        $getCSVDotSep = str_getcsv3('.');
        $getCSVDotSepAmpEnclosure = $getCSVDotSep('&');
        $this->assertEquals($getCSVDotSepAmpEnclosure($str), ['a', 'b']);
    }

    function testStrGetCSV4()
    {
        $str = '&^a&.&^b&';
        $this->assertEquals(str_getcsv4('.', '&', '^', $str), \str_getcsv($str, '.', '&', '^'));
        $this->assertEquals(str_getcsv4('.', '&', '^', $str), ['^a', '^b']);

        $getCSVDotSepAmpEnclosureCaretEscape = str_getcsv4('.', '&', '^');
        $this->assertEquals($getCSVDotSepAmpEnclosureCaretEscape($str), ['^a', '^b']);
    }

    function testStrIReplace()
    {
        $str = 'This is a test!';
        $this->assertEquals(str_ireplace('!', '.', $str), \str_ireplace('!', '.', $str));
        $this->assertEquals(str_ireplace('!', '.', $str), 'This is a test.');

        $quiet = str_ireplace('!', '.');
        $this->assertEquals($quiet($str), 'This is a test.');
    }

    function testStrIReplaceCount()
    {
        $str = 'This is a test!';
        \str_ireplace('!', '.', $str, $count);
        $this->assertEquals(str_ireplace_count('!', '.', $str), $count);
        $this->assertEquals(str_ireplace_count('!', '.', $str), 1);

        $quiet = str_ireplace_count('!', '.');
        $this->assertEquals($quiet($str), 1);
    }

    function testStrPad()
    {
        $str = 'Alien';
        $this->assertEquals(str_pad(6, $str), \str_pad($str, 6));
        $this->assertEquals(str_pad(6, $str), 'Alien ');

        $padTo6 = str_pad(6);
        $this->assertEquals($padTo6($str), 'Alien ');
    }

    function testStrPad3()
    {
        $str = 'Alien';
        $this->assertEquals(str_pad3(6, '-', $str), \str_pad($str, 6, '-'));
        $this->assertEquals(str_pad3(6, '-', $str), 'Alien-');

        $padTo6 = str_pad3(6);
        $padTo6WithDash = $padTo6('-');
        $this->assertEquals($padTo6WithDash($str), 'Alien-');
    }

    function testStrPad4()
    {
        $str = 'Alien';
        $this->assertEquals(str_pad4(7, '-', STR_PAD_LEFT, $str), \str_pad($str, 7, '-', STR_PAD_LEFT));
        $this->assertEquals(str_pad4(7, '-', STR_PAD_LEFT, $str), '--Alien');

        $padTo7 = str_pad4(7);
        $padTo7WithDashOnTheLeft = $padTo7('-', STR_PAD_LEFT);
        $this->assertEquals($padTo7WithDashOnTheLeft($str), '--Alien');
    }

    function testStrRepeat()
    {
        $this->assertEquals(str_repeat(2, 'a'), \str_repeat('a', 2));
        $this->assertEquals(str_repeat(2, 'a'), 'aa');

        $repeatTwice = str_repeat(2);
        $this->assertEquals($repeatTwice('a'), 'aa');
    }

    function testStrReplace()
    {
        $str = 'This is a test!';
        $this->assertEquals(str_replace('!', '.', $str), \str_replace('!', '.', $str));
        $this->assertEquals(str_replace('!', '.', $str), 'This is a test.');

        $quiet = str_replace('!', '.');
        $this->assertEquals($quiet($str), 'This is a test.');
    }

    function testStrReplaceCount()
    {
        $str = 'This is a test!';
        \str_replace('!', '.', $str, $count);
        $this->assertEquals(str_replace_count('!', '.', $str), $count);
        $this->assertEquals(str_replace_count('!', '.', $str), 1);

        $quiet = str_replace_count('!', '.');
        $this->assertEquals($quiet($str), 1);
    }

    function testStrRot13()
    {
        $str = 'PHP 4.3.0';
        $expected = 'CUC 4.3.0';
        $this->assertEquals(str_rot13($str), \str_rot13($str));
        $this->assertEquals(str_rot13($str), $expected);

        $strRot13 = str_rot13();
        $this->assertEquals($strRot13($str), $expected);
    }

    function testStrShuffle()
    {
        $str = 'abcdefg';
        $this->assertEquals(strlen(str_shuffle($str)), strlen(\str_shuffle($str)));

        $shuffle = str_shuffle();
        $this->assertEquals(strlen($shuffle($str)), strlen(\str_shuffle($str)));
    }

    function testStrSplit()
    {
        $str = 'abc';
        $this->assertEquals(str_split($str), \str_split($str));
        $this->assertEquals(str_split($str), ['a', 'b', 'c']);

        $strSplit = str_split();
        $this->assertEquals($strSplit($str), ['a', 'b', 'c']);
    }

    function testStrSplit2()
    {
        $str = 'abc';
        $this->assertEquals(str_split2(2, $str), \str_split($str, 2));
        $this->assertEquals(str_split2(2, $str), ['ab', 'c']);

        $strSplit2 = str_split2(2);
        $this->assertEquals($strSplit2($str), ['ab', 'c']);
    }

    function testStrWordCount()
    {
        $str = 'This is a test.';
        $this->assertEquals(str_word_count($str), \str_word_count($str));
        $this->assertEquals(str_word_count($str), 4);

        $wc = str_word_count();
        $this->assertEquals($wc($str), 4);
    }

    function testStrWordCount2()
    {
        $str = 'This is a test.';
        $this->assertEquals(str_word_count2(1, $str), \str_word_count($str, 1));
        $this->assertEquals(str_word_count2(1, $str), [ 'This', 'is', 'a', 'test' ]);

        $words = str_word_count2(1);
        $this->assertEquals($words($str), ['This', 'is', 'a', 'test']);
    }

    function testStrWordCount3()
    {
        $str = 'This is a test.';
        $this->assertEquals(str_word_count3(0, "\ ", $str), \str_word_count($str, 0, "\ "));
        $this->assertEquals(str_word_count3(0, "\ ", $str), 1);

        $wc = str_word_count3(0, "\ ");
        $this->assertEquals($wc($str), 1);
    }

    function testStrCaseCmp()
    {
        $a = 'a';
        $b = 'b';
        $this->assertEquals(strcasecmp($a, $b), \strcasecmp($a, $b));
        $this->assertEquals(strcasecmp($a, $b), -1);

        $cmpWitha = strcasecmp($a);
        $this->assertEquals($cmpWitha($b), -1);
    }

    function testStrChr()
    {
        $email  = 'name@example.com';
        $this->assertEquals(strchr('@', $email), \strchr($email, '@'));
        $this->assertEquals(strchr('@', $email), '@example.com');

        $searchForAt = strchr('@');
        $this->assertEquals($searchForAt($email), '@example.com');
    }

    function testStrChr3()
    {
        $email  = 'name@example.com';
        $this->assertEquals(strchr3(true, '@', $email), \strchr($email, '@', true));
        $this->assertEquals(strchr3(true, '@', $email), 'name');

        $searchForAt = strchr3(true, '@');
        $this->assertEquals($searchForAt($email), 'name');
    }

    function testStrCmp()
    {
        $a = 'a';
        $b = 'b';
        $this->assertEquals(strcmp($a, $b), \strcmp($a, $b));
        $this->assertEquals(strcmp($a, $b), -1);

        $cmpWitha = strcmp($a);
        $this->assertEquals($cmpWitha($b), -1);
    }

    function testStrColl()
    {
        $a = 'a';
        $b = 'b';
        $this->assertEquals(strcoll($a, $b), \strcoll($a, $b));
        $this->assertEquals(strcoll($a, $b), -1);

        $cmpWitha = strcoll($a);
        $this->assertEquals($cmpWitha($b), -1);
    }

    function testStrcspn()
    {
        $a = 'abcd';
        $b = 'apple';

        $this->assertEquals(strcspn($b, $a), \strcspn($a, $b));

        $strcspnB = strcspn($b);
        $this->assertEquals($strcspnB($a), \strcspn($a, $b));
    }

    function testStrcspn3()
    {
        $a = 'abcdhelloabcd';
        $b = 'abcd';
        $this->assertEquals(strcspn3(-9, $b, $a), \strcspn($a, $b, -9));
        $this->assertEquals(strcspn3(-9, $b, $a), 5);

        $f = strcspn3(-9);
        $this->assertEquals($f($b, $a), \strcspn($a, $b, -9));
    }

    function testStrcspn4()
    {
        $a = 'abcdhelloabcd';
        $b = 'abcd';
        $this->assertEquals(strcspn4(-9, -5, $b, $a), \strcspn($a, $b, -9, -5));
        $this->assertEquals(strcspn4(-9, -5, $b, $a), 4);

        $f = strcspn4(-9, -5);
        $this->assertEquals($f($b, $a), \strcspn($a, $b, -9, -5));
    }
}
