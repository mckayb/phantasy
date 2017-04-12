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
    strcspn4,
    strip_tags,
    strip_tags2,
    stripcslashes,
    stripos,
    stripos3,
    stripslashes,
    stristr,
    stristr3,
    strlen,
    strnatcasecmp,
    strnatcmp,
    strncasecmp,
    strncmp,
    strpbrk,
    strpos,
    strpos3,
    strrchr,
    strrev,
    strripos,
    strripos3,
    strrpos,
    strrpos3,
    strspn,
    strspn3,
    strspn4,
    strstr,
    strstr3,
    strtok,
    strtok1,
    strtolower,
    strtoupper,
    strtr,
    substr_compare,
    substr_compare4,
    substr_compare5,
    substr_count,
    substr_count3,
    substr_count4,
    substr_replace,
    substr_replace4,
    substr,
    substr3,
    trim,
    trim2,
    ucfirst,
    ucwords,
    ucwords2,
    wordwrap,
    wordwrap2,
    wordwrap3,
    wordwrap4
};

class PHPFunctionsTest extends TestCase
{
    public function testExplode()
    {
        $this->assertEquals(explode(' ', 'foo bar'), \explode(' ', 'foo bar'));
        $this->assertEquals(explode(' ', 'foo bar'), ['foo', 'bar']);

        $explodeBySpace = explode(' ');
        $this->assertEquals($explodeBySpace('foo bar'), ['foo', 'bar']);
    }

    public function testImplode()
    {
        $arr = ['one', 'two', 'three'];
        $this->assertEquals(implode(',', $arr), \implode(',', $arr));
        $this->assertEquals(implode(',', $arr), 'one,two,three');

        $joinByComma = implode(',');
        $this->assertEquals($joinByComma($arr), 'one,two,three');
    }

    public function testAddCSlashes()
    {
        $this->assertEquals(addcslashes('A..z', 'foo[ ]'), \addcslashes('foo[ ]', 'A..z'));
        $this->assertEquals(addcslashes('A..z', 'foo[ ]'), '\f\o\o\[ \]');
        $addCSlashesAToZ = addcslashes('A..z');

        $this->assertEquals($addCSlashesAToZ('foo[ ]'), '\f\o\o\[ \]');
    }

    public function testAddSlashes()
    {
        $str = "Is your name O'Reilly?";

        $this->assertEquals(addslashes($str), \addslashes($str));
        $this->assertEquals(addslashes($str), "Is your name O\'Reilly?");

        $addSlashes = addslashes();
        $this->assertEquals($addSlashes($str), "Is your name O\'Reilly?");
    }

    public function testBin2Hex()
    {
        $this->assertEquals(bin2hex('test'), \bin2hex('test'));
        $this->assertEquals(bin2hex('test'), '74657374');

        $bin2Hex = bin2hex();
        $this->assertEquals($bin2Hex('test'), '74657374');
    }

    public function testChop()
    {
        $this->assertEquals(chop("test\n"), \chop("test\n"));
        $this->assertEquals(chop("test\n"), "test");

        $chop = chop();
        $this->assertEquals($chop("test\n"), "test");
    }

    public function testChop2()
    {
        $this->assertEquals(chop2("World!", "Hello World!"), \chop("Hello World!", "World!"));
        $this->assertEquals(chop2("World!", "Hello World!"), "Hello ");

        $chopWorld = chop2("World!");
        $this->assertEquals($chopWorld("Hello World!"), "Hello ");
    }

    public function testChr()
    {
        $this->assertEquals(chr(046), \chr(046));
        $this->assertEquals(chr(046), "&");

        $chr = chr();
        $this->assertEquals($chr(046), "&");
    }

    public function testChunkSplit()
    {
        $this->assertEquals(chunk_split("test"), \chunk_split("test"));
        $this->assertEquals(chunk_split("test"), "test\r\n");

        $chunkSplit = chunk_split();
        $this->assertEquals($chunkSplit("test"), "test\r\n");
    }

    public function testChunkSplit2()
    {
        $this->assertEquals(chunk_split2(2, "test"), \chunk_split("test", 2));
        $this->assertEquals(chunk_split2(2, "test"), "te\r\nst\r\n");

        $chunkSplitLen2 = chunk_split2(2);
        $this->assertEquals($chunkSplitLen2("test"), "te\r\nst\r\n");
    }

    public function testChunkSplit3()
    {
        $this->assertEquals(chunk_split3(2, '.', 'test'), \chunk_split('test', 2, '.'));
        $this->assertEquals(chunk_split3(2, '.', "test"), "te.st.");

        $chunkSplitLen2 = chunk_split3(2);
        $chunkSplitLen2Dot = $chunkSplitLen2('.');
        $this->assertEquals($chunkSplitLen2('.', "test"), "te.st.");
        $this->assertEquals($chunkSplitLen2Dot("test"), "te.st.");
    }

    public function testConvertCyrString()
    {
        $str = "Good Morning..";
        $expected = "Good Morning..";
        $this->assertEquals(convert_cyr_string('w', 'k', $str), \convert_cyr_string($str, 'w', 'k'));
        $this->assertEquals(convert_cyr_string('w', 'k', $str), $expected);

        $convertFromWToA = convert_cyr_string('w', 'k');
        $this->assertEquals($convertFromWToA($str), $expected);
    }

    public function testConvertUUDecode()
    {
        $this->assertEquals(convert_uudecode("+22!L;W9E(%!(4\"$`\n`"), \convert_uudecode("+22!L;W9E(%!(4\"$`\n`"));
        $this->assertEquals(convert_uudecode("+22!L;W9E(%!(4\"$`\n`"), 'I love PHP!');
    }

    public function testConvertUUEncode()
    {
        $this->assertEquals(convert_uuencode('I love PHP!'), \convert_uuencode('I love PHP!'));
        $this->assertEquals(convert_uuencode('I love PHP!'), "+22!L;W9E(%!(4\"$`\n`\n");
    }

    public function testCountChars()
    {
        $this->assertEquals(count_chars('Test'), \count_chars('Test'));
        $res = count_chars('Test');
        $this->assertCount(256, $res);

        $this->assertEquals(array_filter($res, function ($x) {
            return $x > 0;
        }), [
            '84' => 1,
            '101' => 1,
            '115' => 1,
            '116' => 1
        ]);
    }

    public function testCountChars2()
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

    public function testCrc32()
    {
        $this->assertEquals(crc32('test'), \crc32('test'));
        $this->assertEquals(crc32('test'), 3632233996);

        $crc32 = crc32();
        $this->assertEquals($crc32('test'), 3632233996);
    }

    public function testCrypt()
    {
        $this->assertEquals(crypt('Foo', 'Test'), \crypt('Test', 'Foo'));
        $this->assertEquals(crypt('Foo', 'Test'), 'Foh5xTm5Uw31I');
    }

    public function testHex2Bin()
    {
        $this->assertEquals(hex2bin('6578616d706c65206865782064617461'), \hex2bin('6578616d706c65206865782064617461'));
        $this->assertEquals(hex2bin('6578616d706c65206865782064617461'), 'example hex data');
    }

    public function testHtmlSpecialCharsDecode()
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

    public function testHtmlSpecialCharsDecode2()
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

    public function testJoin()
    {
        $arr = ['one', 'two', 'three'];
        $this->assertEquals(join(',', $arr), \join(',', $arr));
        $this->assertEquals(join(',', $arr), 'one,two,three');

        $joinByComma = join(',');
        $this->assertEquals($joinByComma($arr), 'one,two,three');
    }

    public function testLcFirst()
    {
        $this->assertEquals(lcfirst('HelloWorld'), \lcfirst('HelloWorld'));
        $this->assertEquals(lcfirst('HelloWorld'), 'helloWorld');

        $lcFirst = lcfirst();
        $this->assertEquals($lcFirst('HelloWorld'), 'helloWorld');
    }

    public function testLevenshtein()
    {
        $this->assertEquals(levenshtein('carrrot', 'carrot'), \levenshtein('carrrot', 'carrot'));
    }

    public function testLevenshtein5()
    {
        $a = '1 apple';
        $b = '2 apples';
        $this->assertEquals(levenshtein5(1, 0, 0, $a, $b), \levenshtein($a, $b, 1, 0, 0));
    }

    public function testLTrim()
    {
        $a = "\t\tThese are a few words :) ...  ";
        $this->assertEquals(ltrim($a), \ltrim($a));
        $this->assertEquals(ltrim($a), "These are a few words :) ...  ");
    }

    public function testLTrim2()
    {
        $a = "\t\tThese are a few words :) ...  ";
        $this->assertEquals(ltrim2(" \t.", $a), \ltrim($a, " \t."));
        $this->assertEquals(ltrim2(" \t.", $a), "These are a few words :) ...  ");
    }

    public function testMd5File()
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

    public function testMd5File2()
    {
        $this->assertEquals(
            md5_file2(true, realpath(dirname(__FILE__)) . '/fixtures/config.json'),
            \md5_file(realpath(dirname(__FILE__)) . '/fixtures/config.json', true)
        );
    }

    public function testMd5()
    {
        $str = 'apple';
        $expected = '1f3870be274f6c49b3e31a0c6728957f';

        $this->assertEquals(md5($str), \md5($str));
        $this->assertEquals(md5($str), $expected);
    }

    public function testMd52()
    {
        $str = 'apple';
        $this->assertEquals(md52(true, $str), \md5($str, true));
    }

    public function testMetaphone()
    {
        $this->assertEquals(metaphone('programming'), \metaphone('programming'));
        $this->assertEquals(metaphone('programming'), 'PRKRMNK');
    }

    public function testMetaphone2()
    {
        $this->assertEquals(metaphone2(5, 'programming'), \metaphone('programming', 5));
        $this->assertEquals(metaphone2(5, 'programming'), 'PRKRM');

        $metaphone5Phonemes = metaphone2(5);
        $this->assertEquals($metaphone5Phonemes('programming'), 'PRKRM');
    }

    public function testMoneyFormat()
    {
        $num = 1234.56;
        setlocale(LC_MONETARY, 'en_IN');
        $this->assertEquals(money_format('%i', $num), \money_format('%i', $num));
        $this->assertEquals(money_format('%i', $num), 'INR 1,234.56');
    }

    public function testNLLangInfo()
    {
        $this->assertEquals(nl_langinfo(MON_1), \nl_langinfo(MON_1));
        $this->assertEquals(nl_langinfo(MON_1), "January");

        $nlLangInfo = nl_langinfo();
        $this->assertEquals($nlLangInfo(MON_1), "January");
    }

    public function testNL2BR()
    {
        $str = "foo isn't\n bar";
        $this->assertEquals(nl2br($str), \nl2br($str));
        $this->assertEquals(nl2br($str), "foo isn't<br />\n bar");
    }

    public function testNL2BR2()
    {
        $str = "Welcome\r\nThis is my HTML document";
        $expected = "Welcome<br>\r\nThis is my HTML document";
        $this->assertEquals(nl2br2(false, $str), \nl2br($str, false));

        $this->assertEquals(nl2br2(false, $str), $expected);

        $nl2brNotXHtml = nl2br2(false);
        $this->assertEquals($nl2brNotXHtml($str), $expected);
    }

    public function testNumberFormat()
    {
        $num = 1234.56;
        $this->assertEquals(number_format($num), \number_format($num));
        $this->assertEquals(number_format($num), '1,235');

        $numFormat = number_format();
        $this->assertEquals($numFormat($num), '1,235');
    }

    public function testNumberFormat2()
    {
        $num = 1234.56;
        $this->assertEquals(number_format2(2, $num), \number_format($num, 2));
        $this->assertEquals(number_format2(2, $num), '1,234.56');

        $formatTo2Decimals = number_format2(2);
        $this->assertEquals($formatTo2Decimals($num), '1,234.56');
    }

    public function testNumberFormat4()
    {
        $num = 1234.56;
        $this->assertEquals(number_format4(2, ',', ' ', $num), \number_format($num, 2, ',', ' '));
        $this->assertEquals(number_format4(2, ',', ' ', $num), '1 234,56');

        $frNumberFormat = number_format4(2, ',', ' ');
        $this->assertEquals($frNumberFormat($num), '1 234,56');
    }

    public function testOrd()
    {
        $str = "\n";
        $this->assertEquals(ord($str), \ord($str));
        $this->assertEquals(ord($str), 10);

        $ord = ord();
        $this->assertEquals($ord($str), 10);
    }

    public function testParseStr()
    {
        $str = "first=value&arr[]=foo+bar&arr[]=baz";
        $output = parse_str($str);
        \parse_str($str, $expected);
        $this->assertEquals($output, $expected);
    }

    public function testQuotemeta()
    {
        $str = "Hello world. (can you hear me?)";
        $expected = "Hello world\. \(can you hear me\?\)";
        $this->assertEquals(quotemeta($str), \quotemeta($str));
        $this->assertEquals(quotemeta($str), $expected);

        $quotemeta = quotemeta();
        $this->assertEquals($quotemeta($str), $expected);
    }

    public function testRTrim()
    {
        $this->assertEquals(rtrim("test\n"), \rtrim("test\n"));
        $this->assertEquals(rtrim("test\n"), "test");

        $rtrim = rtrim();
        $this->assertEquals($rtrim("test\n"), "test");
    }

    public function testRTrim2()
    {
        $this->assertEquals(rtrim2("World!", "Hello World!"), \rtrim("Hello World!", "World!"));
        $this->assertEquals(rtrim2("World!", "Hello World!"), "Hello ");

        $rtrimWorld = rtrim2("World!");
        $this->assertEquals($rtrimWorld("Hello World!"), "Hello ");
    }

    public function testSHA1File()
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

    public function testSHA1File2()
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

    public function testSha1()
    {
        $str = 'apple';
        $expected = 'd0be2dc421be4fcd0172e5afceea3970e2f3d940';

        $this->assertEquals(sha1($str), \sha1($str));
        $this->assertEquals(sha1($str), $expected);

        $sha1 = sha1();
        $this->assertEquals($sha1($str), $expected);
    }

    public function testSha12()
    {
        $str = 'apple';
        $this->assertEquals(sha12(true, $str), \sha1($str, true));
    }

    public function testSimilarText()
    {
        $a = 'This is a test!';
        $b = 'A test, this is!';
        $this->assertEquals(similar_text($a, $b), \similar_text($a, $b));
        $this->assertEquals(similar_text($a, $b), 7);

        $similarToA = similar_text($a);
        $this->assertEquals($similarToA($b), 7);
    }

    public function testSimilarTextPct()
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

    public function testSoundex()
    {
        $str = 'Euler';
        $expected = 'E460';
        $this->assertEquals(soundex($str), \soundex($str));
        $this->assertEquals(soundex($str), $expected);

        $soundex = soundex();
        $this->assertEquals($soundex($str), $expected);
    }

    public function testStrGetCSV()
    {
        $str = 'a,b';
        $this->assertEquals(str_getcsv($str), \str_getcsv($str));
        $this->assertEquals(str_getcsv($str), ['a', 'b']);

        $strGetCSV = str_getcsv();
        $this->assertEquals($strGetCSV($str), ['a', 'b']);
    }

    public function testStrGetCSV2()
    {
        $str = 'a.b';
        $this->assertEquals(str_getcsv2('.', $str), \str_getcsv($str, '.'));
        $this->assertEquals(str_getcsv2('.', $str), ['a', 'b']);

        $getCSVDotSep = str_getcsv2('.');
        $this->assertEquals($getCSVDotSep($str), ['a', 'b']);
    }

    public function testStrGetCSV3()
    {
        $str = '&a&.&b&';
        $this->assertEquals(str_getcsv3('.', '&', $str), \str_getcsv($str, '.', '&'));
        $this->assertEquals(str_getcsv3('.', '&', $str), ['a', 'b']);

        $getCSVDotSep = str_getcsv3('.');
        $getCSVDotSepAmpEnclosure = $getCSVDotSep('&');
        $this->assertEquals($getCSVDotSepAmpEnclosure($str), ['a', 'b']);
    }

    public function testStrGetCSV4()
    {
        $str = '&^a&.&^b&';
        $this->assertEquals(str_getcsv4('.', '&', '^', $str), \str_getcsv($str, '.', '&', '^'));
        $this->assertEquals(str_getcsv4('.', '&', '^', $str), ['^a', '^b']);

        $getCSVDotSepAmpEnclosureCaretEscape = str_getcsv4('.', '&', '^');
        $this->assertEquals($getCSVDotSepAmpEnclosureCaretEscape($str), ['^a', '^b']);
    }

    public function testStrIReplace()
    {
        $str = 'This is a test!';
        $this->assertEquals(str_ireplace('!', '.', $str), \str_ireplace('!', '.', $str));
        $this->assertEquals(str_ireplace('!', '.', $str), 'This is a test.');

        $quiet = str_ireplace('!', '.');
        $this->assertEquals($quiet($str), 'This is a test.');
    }

    public function testStrIReplaceCount()
    {
        $str = 'This is a test!';
        \str_ireplace('!', '.', $str, $count);
        $this->assertEquals(str_ireplace_count('!', '.', $str), $count);
        $this->assertEquals(str_ireplace_count('!', '.', $str), 1);

        $quiet = str_ireplace_count('!', '.');
        $this->assertEquals($quiet($str), 1);
    }

    public function testStrPad()
    {
        $str = 'Alien';
        $this->assertEquals(str_pad(6, $str), \str_pad($str, 6));
        $this->assertEquals(str_pad(6, $str), 'Alien ');

        $padTo6 = str_pad(6);
        $this->assertEquals($padTo6($str), 'Alien ');
    }

    public function testStrPad3()
    {
        $str = 'Alien';
        $this->assertEquals(str_pad3(6, '-', $str), \str_pad($str, 6, '-'));
        $this->assertEquals(str_pad3(6, '-', $str), 'Alien-');

        $padTo6 = str_pad3(6);
        $padTo6WithDash = $padTo6('-');
        $this->assertEquals($padTo6WithDash($str), 'Alien-');
    }

    public function testStrPad4()
    {
        $str = 'Alien';
        $this->assertEquals(str_pad4(7, '-', STR_PAD_LEFT, $str), \str_pad($str, 7, '-', STR_PAD_LEFT));
        $this->assertEquals(str_pad4(7, '-', STR_PAD_LEFT, $str), '--Alien');

        $padTo7 = str_pad4(7);
        $padTo7WithDashOnTheLeft = $padTo7('-', STR_PAD_LEFT);
        $this->assertEquals($padTo7WithDashOnTheLeft($str), '--Alien');
    }

    public function testStrRepeat()
    {
        $this->assertEquals(str_repeat(2, 'a'), \str_repeat('a', 2));
        $this->assertEquals(str_repeat(2, 'a'), 'aa');

        $repeatTwice = str_repeat(2);
        $this->assertEquals($repeatTwice('a'), 'aa');
    }

    public function testStrReplace()
    {
        $str = 'This is a test!';
        $this->assertEquals(str_replace('!', '.', $str), \str_replace('!', '.', $str));
        $this->assertEquals(str_replace('!', '.', $str), 'This is a test.');

        $quiet = str_replace('!', '.');
        $this->assertEquals($quiet($str), 'This is a test.');
    }

    public function testStrReplaceCount()
    {
        $str = 'This is a test!';
        \str_replace('!', '.', $str, $count);
        $this->assertEquals(str_replace_count('!', '.', $str), $count);
        $this->assertEquals(str_replace_count('!', '.', $str), 1);

        $quiet = str_replace_count('!', '.');
        $this->assertEquals($quiet($str), 1);
    }

    public function testStrRot13()
    {
        $str = 'PHP 4.3.0';
        $expected = 'CUC 4.3.0';
        $this->assertEquals(str_rot13($str), \str_rot13($str));
        $this->assertEquals(str_rot13($str), $expected);

        $strRot13 = str_rot13();
        $this->assertEquals($strRot13($str), $expected);
    }

    public function testStrShuffle()
    {
        $str = 'abcdefg';
        $this->assertEquals(strlen(str_shuffle($str)), strlen(\str_shuffle($str)));

        $shuffle = str_shuffle();
        $this->assertEquals(strlen($shuffle($str)), strlen(\str_shuffle($str)));
    }

    public function testStrSplit()
    {
        $str = 'abc';
        $this->assertEquals(str_split($str), \str_split($str));
        $this->assertEquals(str_split($str), ['a', 'b', 'c']);

        $strSplit = str_split();
        $this->assertEquals($strSplit($str), ['a', 'b', 'c']);
    }

    public function testStrSplit2()
    {
        $str = 'abc';
        $this->assertEquals(str_split2(2, $str), \str_split($str, 2));
        $this->assertEquals(str_split2(2, $str), ['ab', 'c']);

        $strSplit2 = str_split2(2);
        $this->assertEquals($strSplit2($str), ['ab', 'c']);
    }

    public function testStrWordCount()
    {
        $str = 'This is a test.';
        $this->assertEquals(str_word_count($str), \str_word_count($str));
        $this->assertEquals(str_word_count($str), 4);

        $wc = str_word_count();
        $this->assertEquals($wc($str), 4);
    }

    public function testStrWordCount2()
    {
        $str = 'This is a test.';
        $this->assertEquals(str_word_count2(1, $str), \str_word_count($str, 1));
        $this->assertEquals(str_word_count2(1, $str), [ 'This', 'is', 'a', 'test' ]);

        $words = str_word_count2(1);
        $this->assertEquals($words($str), ['This', 'is', 'a', 'test']);
    }

    public function testStrWordCount3()
    {
        $str = 'This is a test.';
        $this->assertEquals(str_word_count3(0, "\ ", $str), \str_word_count($str, 0, "\ "));
        $this->assertEquals(str_word_count3(0, "\ ", $str), 1);

        $wc = str_word_count3(0, "\ ");
        $this->assertEquals($wc($str), 1);
    }

    public function testStrCaseCmp()
    {
        $a = 'a';
        $b = 'b';
        $this->assertEquals(strcasecmp($a, $b), \strcasecmp($a, $b));
        $this->assertEquals(strcasecmp($a, $b), -1);

        $cmpWitha = strcasecmp($a);
        $this->assertEquals($cmpWitha($b), -1);
    }

    public function testStrChr()
    {
        $email  = 'name@example.com';
        $this->assertEquals(strchr('@', $email), \strchr($email, '@'));
        $this->assertEquals(strchr('@', $email), '@example.com');

        $searchForAt = strchr('@');
        $this->assertEquals($searchForAt($email), '@example.com');
    }

    public function testStrChr3()
    {
        $email  = 'name@example.com';
        $this->assertEquals(strchr3(true, '@', $email), \strchr($email, '@', true));
        $this->assertEquals(strchr3(true, '@', $email), 'name');

        $searchForAt = strchr3(true, '@');
        $this->assertEquals($searchForAt($email), 'name');
    }

    public function testStrCmp()
    {
        $a = 'a';
        $b = 'b';
        $this->assertEquals(strcmp($a, $b), \strcmp($a, $b));
        $this->assertEquals(strcmp($a, $b), -1);

        $cmpWitha = strcmp($a);
        $this->assertEquals($cmpWitha($b), -1);
    }

    public function testStrColl()
    {
        $a = 'a';
        $b = 'b';
        $this->assertEquals(strcoll($a, $b), \strcoll($a, $b));
        $this->assertEquals(strcoll($a, $b), -1);

        $cmpWitha = strcoll($a);
        $this->assertEquals($cmpWitha($b), -1);
    }

    public function testStrcspn()
    {
        $a = 'abcd';
        $b = 'apple';

        $this->assertEquals(strcspn($b, $a), \strcspn($a, $b));

        $strcspnB = strcspn($b);
        $this->assertEquals($strcspnB($a), \strcspn($a, $b));
    }

    public function testStrcspn3()
    {
        $a = 'abcdhelloabcd';
        $b = 'abcd';
        $this->assertEquals(strcspn3(-9, $b, $a), \strcspn($a, $b, -9));
        $this->assertEquals(strcspn3(-9, $b, $a), 5);

        $f = strcspn3(-9);
        $this->assertEquals($f($b, $a), \strcspn($a, $b, -9));
    }

    public function testStrcspn4()
    {
        $a = 'abcdhelloabcd';
        $b = 'abcd';
        $this->assertEquals(strcspn4(-9, -5, $b, $a), \strcspn($a, $b, -9, -5));
        $this->assertEquals(strcspn4(-9, -5, $b, $a), 4);

        $f = strcspn4(-9, -5);
        $this->assertEquals($f($b, $a), \strcspn($a, $b, -9, -5));
    }

    public function testStripTags()
    {
        $text = '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>';
        $this->assertEquals(strip_tags($text), \strip_tags($text));

        $stripTags = strip_tags();
        $this->assertEquals($stripTags($text), \strip_tags($text));
    }

    public function testStripTags2()
    {
        $text = '<p>Test paragraph.</p><!-- Comment --> <a href="#fragment">Other text</a>';
        $this->assertEquals(strip_tags2('<p><a>', $text), \strip_tags($text, '<p><a>'));

        $stripTagsKeepPAndA = strip_tags2('<p><a>');
        $this->assertEquals($stripTagsKeepPAndA($text), \strip_tags($text, '<p><a>'));
    }

    public function testStripCSlashes()
    {
        $str = '\f\o\o\[ \]';
        $this->assertEquals(stripcslashes($str), \stripcslashes($str));

        $stripcslashes = stripcslashes();
        $this->assertEquals($stripcslashes($str), \stripcslashes($str));
    }

    public function testStripos()
    {
        $findme    = 'a';
        $mystring1 = 'xyz';
        $mystring2 = 'ABC';

        $pos1 = stripos($findme, $mystring1);
        $pos2 = stripos($findme, $mystring2);

        $this->assertEquals($pos1, \stripos($mystring1, $findme));
        $this->assertEquals($pos2, \stripos($mystring2, $findme));

        $findA = stripos($findme);
        $this->assertEquals($findA($mystring1), \stripos($mystring1, $findme));
        $this->assertEquals($findA($mystring2), \stripos($mystring2, $findme));
    }

    public function testStripos3()
    {
        $findme    = 'a';
        $mystring1 = 'xyz';
        $mystring2 = 'ABC';

        $pos1 = stripos3(1, $findme, $mystring1);
        $pos2 = stripos3(1, $findme, $mystring2);

        $this->assertEquals($pos1, \stripos($mystring1, $findme, 1));
        $this->assertEquals($pos2, \stripos($mystring2, $findme, 1));

        $findA = stripos3(1, $findme);
        $this->assertEquals($findA($mystring1), \stripos($mystring1, $findme, 1));
        $this->assertEquals($findA($mystring2), \stripos($mystring2, $findme, 1));
    }

    public function testStripslashes()
    {
        $str = "Is your name O\'reilly?";
        $this->assertEquals(stripslashes($str), \stripslashes($str));

        $stripslashes = stripslashes();
        $this->assertEquals($stripslashes($str), \stripslashes($str));
    }

    public function testStristr()
    {
        $str = 'This is a test foo';
        $this->assertEquals(stristr('test', $str), \stristr($str, 'test'));

        $afterTest = stristr('test');
        $this->assertEquals($afterTest($str), \stristr($str, 'test'));
    }

    public function testStristr3()
    {
        $str = 'This is a test foo';
        $this->assertEquals(stristr3(true, 'test', $str), \stristr($str, 'test', true));

        $beforeTest = stristr3(true, 'test');
        $this->assertEquals($beforeTest($str), \stristr($str, 'test', true));
    }

    public function testStrlen()
    {
        $str = 'This is a test';
        $this->assertEquals(strlen($str), \strlen($str));

        $strlen = strlen();
        $this->assertEquals($strlen($str), \strlen($str));
    }

    public function testStrnatcasecmp()
    {
        $a = 'A';
        $b = 'B';

        $this->assertEquals(strnatcasecmp($a, $b), \strnatcasecmp($a, $b));

        $cmpA = strnatcasecmp($a);
        $this->assertEquals($cmpA($b), \strnatcasecmp($a, $b));
    }

    public function testStrnatcmp()
    {
        $a = 'A';
        $b = 'B';
        $this->assertEquals(strnatcmp($a, $b), \strnatcmp($a, $b));

        $cmpA = strnatcmp($a);
        $this->assertEquals($cmpA($b), \strnatcmp($a, $b));
    }

    public function testStrncasecmp()
    {
        $a = 'This is a foo.';
        $b = 'This is a bar.';

        $this->assertEquals(strncasecmp(5, $a, $b), \strncasecmp($a, $b, 5));

        $cmpFirst5 = strncasecmp(5);
        $this->assertEquals($cmpFirst5($a, $b), \strncasecmp($a, $b, 5));
    }

    public function testStrncmp()
    {
        $a = 'This is a foo.';
        $b = 'This is a bar.';

        $this->assertEquals(strncmp(5, $a, $b), \strncmp($a, $b, 5));

        $cmpFirst5 = strncmp(5);
        $this->assertEquals($cmpFirst5($a, $b), \strncmp($a, $b, 5));
    }

    public function testStrpbrk()
    {
        $text = 'This is a Simple text.';
        $this->assertEquals(strpbrk('mi', $text), \strpbrk($text, 'mi'));

        $stripmi = strpbrk('mi');
        $this->assertEquals($stripmi($text), \strpbrk($text, 'mi'));
    }

    public function testStrpos()
    {
        $mystring = 'abc';
        $findme   = 'a';
        $this->assertEquals(strpos($findme, $mystring), \strpos($mystring, $findme));

        $findA = strpos($findme);
        $this->assertEquals($findA($mystring), \strpos($mystring, $findme));
    }

    public function testStrpos3()
    {
        $mystring = 'abc';
        $findme   = 'a';
        $this->assertEquals(strpos3(1, $findme, $mystring), \strpos($mystring, $findme, 1));

        $findAAfterFirst = strpos3(1, $findme);
        $this->assertEquals($findAAfterFirst($mystring), \strpos($mystring, $findme, 1));
    }

    public function testStrrchr()
    {
        $mystring = 'abc';
        $findme   = 'a';
        $this->assertEquals(strrchr($findme, $mystring), \strrchr($mystring, $findme));

        $findA = strrchr($findme);
        $this->assertEquals($findA($mystring), \strrchr($mystring, $findme));
    }

    public function testStrrev()
    {
        $a = 'abc';
        $this->assertEquals(strrev($a), \strrev($a));
        $strrev = strrev();
        $this->assertEquals($strrev($a), \strrev($a));
    }

    public function testStrripos()
    {
        $haystack = 'ababcd';
        $needle   = 'aB';

        $this->assertEquals(strripos($needle, $haystack), \strripos($haystack, $needle));

        $searchForNeedle = strripos($needle);
        $this->assertEquals($searchForNeedle($haystack), \strripos($haystack, $needle));
    }

    public function testStrripos3()
    {
        $haystack = 'ababcd';
        $needle   = 'aB';

        $this->assertEquals(strripos3(1, $needle, $haystack), \strripos($haystack, $needle, 1));

        $searchForNeedleAfter1 = strripos3(1, $needle);
        $this->assertEquals($searchForNeedleAfter1($haystack), \strripos($haystack, $needle, 1));
    }

    public function testStrrpos()
    {
        $haystack = 'ababcd';
        $needle   = 'aB';

        $this->assertEquals(strrpos($needle, $haystack), \strrpos($haystack, $needle));

        $searchForNeedle = strrpos($needle);
        $this->assertEquals($searchForNeedle($haystack), \strrpos($haystack, $needle));
    }

    public function testStrrpos3()
    {
        $haystack = 'ababcd';
        $needle   = 'aB';

        $this->assertEquals(strrpos3(1, $needle, $haystack), \strrpos($haystack, $needle, 1));

        $searchForNeedleAfter1 = strrpos3(1, $needle);
        $this->assertEquals($searchForNeedleAfter1($haystack), \strrpos($haystack, $needle, 1));
    }

    public function testStrspn()
    {
        $this->assertEquals(strspn("o", "foo"), \strspn("foo", "o"));
        $strspnOMask = strspn("o");
        $this->assertEquals($strspnOMask("foo"), \strspn("foo", "o"));
    }

    public function testStrspn3()
    {
        $this->assertEquals(strspn3(1, "o", "foo"), \strspn("foo", "o", 1));
        $strspnOMaskAfter1 = strspn3(1, "o");
        $this->assertEquals($strspnOMaskAfter1("foo"), \strspn("foo", "o", 1));
    }

    public function testStrspn4()
    {
        $this->assertEquals(strspn4(1, 2, "o", "foo"), \strspn("foo", "o", 1, 2));
        $strspnOMask1To2 = strspn4(1, 2, "o");
        $this->assertEquals($strspnOMask1To2("foo"), \strspn("foo", "o", 1, 2));
    }

    public function testStrstr()
    {
        $email  = 'name@example.com';
        $this->assertEquals(strstr('@', $email), \strstr($email, '@'));

        $searchAt = strstr('@');
        $this->assertEquals($searchAt($email), \strstr($email, '@'));
    }

    public function testStrstr3()
    {
        $email  = 'name@example.com';
        $this->assertEquals(strstr3(true, '@', $email), \strstr($email, '@', true));

        $searchBeforeAt = strstr3(true, '@');
        $this->assertEquals($searchBeforeAt($email), \strstr($email, '@', true));
    }

    public function testStrtok()
    {
        $str = 'This is a test';

        $tok = \strtok($str, ' ');
        $arr = [$tok];
        while ($tok !== false) {
            $tok = \strtok(' ');
            $arr[] = $tok;
        }

        $tok2 = strtok(' ', $str);
        $arr2 = [$tok2];
        while ($tok2 !== false) {
            $tok2 = strtok1(' ');
            $arr2[] = $tok2;
        }
        $this->assertEquals($arr, $arr2);
    }

    public function testStrtolower()
    {
        $str = 'This has been a fine Test!';
        $this->assertEquals(strtolower($str), \strtolower($str));
        $strtolower = strtolower();
        $this->assertEquals($strtolower($str), \strtolower($str));
    }

    public function testStrtoupper()
    {
        $str = 'This has been a fine Test!';
        $this->assertEquals(strtoupper($str), \strtoupper($str));
        $strtoupper = strtoupper();
        $this->assertEquals($strtoupper($str), \strtoupper($str));
    }

    public function testStrtr3Params()
    {
        $this->assertEquals(strtr("ab", "01", "baab"), \strtr("baab", "ab", "01"));

        $strtr = strtr();
        $strtrSwapabWith01 = $strtr("ab", "01");
        $this->assertEquals($strtrSwapabWith01("baab"), \strtr("baab", "ab", "01"));
    }

    public function testStrtr2Params()
    {
        $trans = ["h" => "-", "hello" => "hi", "hi" => "hello"];
        $str = "hi all, I said hello";
        $this->assertEquals(strtr($trans, $str), \strtr($str, $trans));

        $strtr = strtr();
        $strTranslateSwapHiHello = $strtr($trans);
        $this->assertEquals($strTranslateSwapHiHello($str), \strtr($str, $trans));
    }

    public function testSubstrCompare()
    {
        $a = "abcde";
        $b = "bc";

        $this->assertEquals(substr_compare(1, $b, $a), \substr_compare($a, $b, 1));

        $cmp1 = substr_compare(1);
        $this->assertEquals($cmp1($b, $a), \substr_compare($a, $b, 1));
    }

    public function testSubstrCompare4()
    {
        $a = "abcde";
        $b = "bc";

        $this->assertEquals(substr_compare4(2, 1, $b, $a), \substr_compare($a, $b, 1, 2));
        $cmp1 = substr_compare4(2, 1);
        $this->assertEquals($cmp1($b, $a), \substr_compare($a, $b, 1, 2));
    }

    public function testSubstrCompare5()
    {
        $a = "abcde";
        $b = "BC";

        $this->assertEquals(substr_compare5(true, 2, 1, $b, $a), \substr_compare($a, $b, 1, 2, true));

        $cmp = substr_compare5(true, 2, 1);
        $this->assertEquals($cmp($b, $a), \substr_compare($a, $b, 1, 2, true));
    }

    public function testSubstrCount()
    {
        $text = 'This is a test';
        $this->assertEquals(substr_count('is', $text), \substr_count($text, 'is'));

        $countIs = substr_count('is');
        $this->assertEquals($countIs($text), \substr_count($text, 'is'));
    }

    public function testSubstrCount3()
    {
        $text = 'This is a test';
        $this->assertEquals(substr_count3(3, 'is', $text), \substr_count($text, 'is', 3));

        $countIsAfter3 = substr_count3(3, 'is');
        $this->assertEquals($countIsAfter3($text), \substr_count($text, 'is', 3));
    }

    public function testSubstrCount4()
    {
        $text = 'This is a test';

        $this->assertEquals(substr_count4(3, 3, 'is', $text), \substr_count($text, 'is', 3, 3)); // 2
        $countIsFrom3To6 = substr_count4(3, 3, 'is');
        $this->assertEquals($countIsFrom3To6($text), \substr_count($text, 'is', 3, 3));
    }

    public function testSubstrReplace()
    {
        $str = 'ABCDEFGH:/MNRPQR/';
        $this->assertEquals(substr_replace(0, 'bob', $str), \substr_replace($str, 'bob', 0));

        $replaceStrWithBob = substr_replace(0, 'bob');
        $this->assertEquals($replaceStrWithBob($str), \substr_replace($str, 'bob', 0));
    }

    public function testSubstrReplace4()
    {
        $str = 'ABCDEFGH:/MNRPQR/';
        $this->assertEquals(
            substr_replace4(strlen($str), 0, 'bob', $str),
            \substr_replace($str, 'bob', 0, strlen($str))
        );

        $replaceStrWithBob = substr_replace4(strlen($str), 0, 'bob');
        $this->assertEquals(
            $replaceStrWithBob($str),
            \substr_replace($str, 'bob', 0, strlen($str))
        );
    }

    public function testSubstr()
    {
        $this->assertEquals(substr(-1, "abcdef"), \substr("abcdef", -1));

        $takeLast = substr(-1);
        $this->assertEquals($takeLast("abcdef"), \substr("abcdef", -1));
    }

    public function testSubstr3()
    {
        $this->assertEquals(substr3(1, -3, "abcdef"), \substr("abcdef", -3, 1));
        $takeThirdFromEnd = substr3(1, -3);
        $this->assertEquals($takeThirdFromEnd("abcdef"), \substr("abcdef", -3, 1));
    }

    public function testTrim()
    {
        $str = " abcd \n";
        $this->assertEquals(trim($str), \trim($str));

        $trim = trim();
        $this->assertEquals($trim($str), \trim($str));
    }

    public function testTrim2()
    {
        $str = " abcdx";
        $this->assertEquals(trim2("x", $str), \trim($str, "x"));

        $trimX = trim2("x");
        $this->assertEquals($trimX($str), \trim($str, "x"));
    }

    public function testUcFirst()
    {
        $str = "abcd";
        $this->assertEquals(ucfirst($str), \ucfirst($str));
        $ucfirst = ucfirst();
        $this->assertEquals($ucfirst($str), \ucfirst($str));
    }

    public function testUcWords()
    {
        $str = "hello world!";
        $this->assertEquals(ucwords($str), \ucwords($str));
        $ucwords = ucwords();
        $this->assertEquals($ucwords($str), \ucwords($str));
    }

    public function testUcWords2()
    {
        $str = "hello|world!";
        $this->assertEquals(ucwords2("|", $str), \ucwords($str, "|"));
        $ucWordsSplitByPipe = ucwords2("|");
        $this->assertEquals($ucWordsSplitByPipe($str), \ucwords($str, "|"));
    }

    public function testWordwrap()
    {
        $str = "The quick brown fox jumps over the lazy dog.";
        $this->assertEquals(wordwrap($str), \wordwrap($str));
        $wordwrap = wordwrap();

        $this->assertEquals($wordwrap($str), \wordwrap($str));
    }

    public function testWordwrap2()
    {
        $str = "The quick brown fox jumps over the lazy dog.";
        $this->assertEquals(wordwrap2(10, $str), \wordwrap($str, 10));
        $wordwrap10 = wordwrap2(10);

        $this->assertEquals($wordwrap10($str), \wordwrap($str, 10));
    }

    public function testWordwrap3()
    {
        $str = "The quick brown fox jumps over the lazy dog.";
        $this->assertEquals(wordwrap3('<br/>', 10, $str), \wordwrap($str, 10, '<br/>'));
        $wordwrap10 = wordwrap3('<br/>', 10);

        $this->assertEquals($wordwrap10($str), \wordwrap($str, 10, '<br/>'));
    }

    public function testWordwrap4()
    {
        $str = "The quick brown fox jumps over the lazy dog.";
        $this->assertEquals(wordwrap4(true, '<br/>', 10, $str), \wordwrap($str, 10, '<br/>', true));
        $wordwrap10 = wordwrap4(true, '<br/>', 10);

        $this->assertEquals($wordwrap10($str), \wordwrap($str, 10, '<br/>', true));
    }
}
