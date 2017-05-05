# PHP Functions

## String Functions

### explode
#### Usage

```php
use function Phantasy\Core\PHP\explode;
```

#### Examples
```php
// Uncurried
explode(' ', 'foo bar');
// ['foo', 'bar']

// Curried
$splitOnSpace = explode(' ');
$splitOnSpace('foo bar');
// ['foo', 'bar']
```

### implode
#### Usage

```php
use function Phantasy\Core\PHP\implode;
```

#### Examples
```php
// Uncurried
implode(',', ['one', two']);
// 'one,two'

// Curried
$joinByComma = implode(',');
$joinByComma(['one', 'two']);
// 'one,two'
```

### addcslashes

#### Usage

```php
use function Phantasy\Core\PHP\addcslashes;
```

#### Examples
```php
// Uncurried
addcslashes('A..z', 'foo[ ]');
// "\f\o\o\[ \]"

// Curried
$addSlashesAToZ = addcslashes('A..z');
$addSlashesAToZ('foo[ ]');
// "\f\o\o\[ \]"

```

### addslashes
#### Usage

```php
use function Phantasy\Core\PHP\addslashes;
```

#### Examples
```php
$str = "Is your name O'Reilly?";

addslashes(str);
// "Is your name O\'Reilly?"

// Or call it with no params,
// which makes it easy to pass into other
// functions, without using 'addslashes'
$addslashes = addslashes();
$addslashes($str);
// "Is your name O\'Reilly?"
```

### bin2hex
#### Usage

```php
use function Phantasy\Core\PHP\bin2hex;
```

#### Examples
```php
bin2hex('test');
// '74657374'

$bin2Hex = bin2hex();
$bin2Hex('test');
// '74657374'
```

### chop
#### Usage

```php
use function Phantasy\Core\PHP\chop;
```

#### Examples
```php
chop("foo\n");
// "test"

$chop = chop();
$chop("foo\n");
// "test"
```

### chop2
#### Usage

```php
use function Phantasy\Core\PHP\chop2;
```

#### Examples
```php
chop2("World!", "Hello World!");
// "Hello "

$chopWorld = chop2("World!");
$chopWorld("Hello World!");
// "Hello "
```

### chr
#### Usage

```php
use function Phantasy\Core\PHP\chr;
```

#### Examples
```php
chr(046)
// "&"

$chr = chr();
$chr(046)
// "&"
```

### chunk_split
#### Usage

```php
use function Phantasy\Core\PHP\chunk_split;
```

#### Examples
```php
chunk_split("test");
// "test\r\n"

$chunkSplit = chunk_split();
$chunkSplit("test");
// "test\r\n"

```

### chunk_split2
#### Usage

```php
use function Phantasy\Core\PHP\chunk_split2;
```

#### Examples
```php
chunk_split2(2, "test");
// "te\r\nst\r\n"

$chunkSplitLen2 = chunk_split2(2);
$chunkSplitLen2("test");
// "te\r\nst\r\n"
```

### chunk_split3
#### Usage

```php
use function Phantasy\Core\PHP\chunk_split3;
```

#### Examples
```php
chunk_split3(2, '.', 'test');
// "te.st."

// Don't have to pass all of the arguments
// at the same time.
$chunkSplitLen2 = chunk_split3(2);
$chunkSplitLen2('.', "test");
// "te.st."

$chunkSplitLen2Dot = $chunkSplitLen2('.');
$chunkSplitLen2Dot("test");
// "te.st."
```

### convert_cyr_string
#### Usage

```php
use function Phantasy\Core\PHP\convert_cyr_string;
```

#### Examples
```php
convert_cyr_string('w', 'k', "Good Morning..");
// "Good Morning.."

$convertWinToKoi8 = convert_cyr_string('w', 'k');
$convertWinToKoi8("Good Morning..");
// "Good Morning.."
```

### convert_uudecode
#### Usage

```php
use function Phantasy\Core\PHP\convert_uudecode;
```

#### Examples
```php
$str = "+22!L;W9E(%!(4\"$`\n`";
convert_uudecode($str);
// "I love PHP!"

$convertUUDecode = convert_uudecode();
$convertUUDecode($str);
// "I love PHP!"

```

### convert_uuencode
#### Usage

```php
use function Phantasy\Core\PHP\convert_uuencode;
```

#### Examples
```php
convert_uuencode('I love PHP!');
// "+22!L;W9E(%!(4\"$`\n`\n"

$convertUUEncode = convert_uuencode();
$convertUUEncode('I love PHP!');
// "+22!L;W9E(%!(4\"$`\n`\n"
```

### count_chars
#### Usage

```php
use function Phantasy\Core\PHP\count_chars;
```

#### Examples
```php
count_chars('Test');
// [ ..., '84' => 1, '101' => 1, '115' => 1, '116 => 1, ...]

$countChars = count_chars();
$countChar('Test');
// [ ..., '84' => 1, '101' => 1, '115' => 1, '116 => 1, ...]
```

### count_chars2
#### Usage

```php
use function Phantasy\Core\PHP\count_chars2;
```

#### Examples
```php
count_chars2(1, 'Test');
// ['84' => 1, '101' => 1, '115' => 1, '116' => 1]

$countCharsMode1 = count_chars2(1);
$countCharsMode1('Test');
// ['84' => 1, '101' => 1, '115' => 1, '116' => 1]
```

### crc32
#### Usage

```php
use function Phantasy\Core\PHP\crc32;
```

#### Examples
```php
crc32('test');
// 3632233996

$crc32 = crc32();
$crc32('test');
// 3632233996

```

### crypt
#### Usage

```php
use function Phantasy\Core\PHP\crypt;
```

#### Examples
```php
crypt('salt', 'str');
// "saEr9QCiBv2PE"

$salted = crypt('salt');
$salted('str');
// "saEr9QCiBv2PE"
```

### hex2bin
#### Usage

```php
use function Phantasy\Core\PHP\hex2bin;
```

#### Examples
```php
hex2bin('6578616d706c65206865782064617461');
// "example hex data"

$hex2bin = hex2bin();
$hex2bin('6578616d706c65206865782064617461');
// "example hex data"
```

### htmlspecialchars_decode
#### Usage

```php
use function Phantasy\Core\PHP\htmlspecialchars_decode;
```

#### Examples
```php
$str = '<p>this -&gt; &quot;</p>\n';
htmlspecialchars_decode($str);
// '<p>this -> "</p>\n'

$decode = htmlspecialchars_decode();
$decode($str);
// '<p>this -> "</p>\n'
```

### htmlspecialchars_decode2
#### Usage

```php
use function Phantasy\Core\PHP\htmlspecialchars_decode2;
```

#### Examples
```php
$str = '<p>this -&gt; &quot;</p>\n';
htmlspecialchars_decode2(ENT_NOQUOTES, $str);
// '<p>this -> &quot;</p>\n'

$remoteWithoutQuotes = htmlspecialchars_decode2(ENT_NOQUOTES);
$removeWithoutQuotes($str);
// '<p>this -> &quot;</p>\n'

```

### join
#### Usage

```php
use function Phantasy\Core\PHP\join;
```

#### Examples
```php
join(',', ['one', 'two', 'three']);
// 'one,two,three'

$joinByComma = join(',');
$joinByComma(['one', 'two', 'three']);
// 'one,two,three'
```

### lcfirst
#### Usage

```php
use function Phantasy\Core\PHP\lcfirst;
```

#### Examples
```php
lcfirst('HelloWorld');
// 'helloWorld'

$lcFirst = lcfirst();
$lcFirst('HelloWorld');
// 'helloWorld'
```

### levenshtein
#### Usage

```php
use function Phantasy\Core\PHP\levenshtein;
```

#### Examples
```php
levenshtein('carrot', 'carrrot');
// 1

$cmpWithCarrot = levenshtein('carrot');
$cmpWithCarrot('carrrot');
// 1
```

### levenshtein5
#### Usage

```php
use function Phantasy\Core\PHP\levenshtein5;
```

#### Examples
```php
$a = '1 apple';
$b = '2 apples';

levenshtein5(1, 0, 0, $a, $b);
// 1

$levenshteinInsCost = levenshtein5(1, 0, 0);
$levenshteinInsCost($a, $b);
// 1

```

### ltrim
#### Usage

```php
use function Phantasy\Core\PHP\ltrim;
```

#### Examples
```php
$a = "\t\tThese are a few words... ";
ltrim($a);
// "These are a few words... "

$ltrim = ltrim();
$ltrim($a);
// "These are a few words... "
```

### ltrim2
#### Usage

```php
use function Phantasy\Core\PHP\ltrim2;
```

#### Examples
```php
$a = "\t\tThese are a few words... ";
ltrim2(" \t.", $a);
// "These are a few words... "

$ltrimTabs = ltrim(" \t.");
$ltrimTabs($a);
// "These are a few words... "

```

### md5_file
#### Usage

```php
use function Phantasy\Core\PHP\md5_file;
```

#### Examples
```php
// Assuming $filepath is set...
md5_file($filepath);
// '625b7290ee67ba3bb84ebe1fa5c32fbe'

$md5File = md5_file();
$md5File($filepath);
// '625b7290ee67ba3bb84ebe1fa5c32fbe'
```

### md5_file2
#### Usage

```php
use function Phantasy\Core\PHP\md5_file2;
```

#### Examples
```php
// Assuming $filepath is set...
md5_file2(true, $filepath);
// "b[r��g�;�N���/�"

$md5WithRawOutput = md5_file2(true);
$md5WithRawOutput($filepath);
// "b[r��g�;�N���/�"
```

### md5
#### Usage

```php
use function Phantasy\Core\PHP\md5;
```

#### Examples
```php
$str = 'apple';
md5($str);
// "1f3870be274f6c49b3e31a0c6728957f"

$md5 = md5();
$md5($str);
// "1f3870be274f6c49b3e31a0c6728957f"
```

### md52
#### Usage

```php
use function Phantasy\Core\PHP\md52;
```

#### Examples
```php
$str = 'apple';
md52(true, $str);
// "8p�'OlI��\ng("

$md5RawOutput = md52(true);
$md5RawOutput($str);
// "8p�'OlI��\ng("
```

### metaphone
#### Usage

```php
use function Phantasy\Core\PHP\metaphone;
```

#### Examples
```php
$str = 'programming';
metaphone($str);
// 'PRKRMNK'

$metaphone = metaphone();
$metaphone($str);
// 'PRKRMNK'
```

### metaphone2
#### Usage

```php
use function Phantasy\Core\PHP\metaphone2;
```

#### Examples
```php
$str = 'programming';
metaphone2(5, $str);
// 'PRKRM'

$metaphone5Phonemes = metaphone2(5);
$metaphone5Phonemes($str);
// 'PRKRM'
```

### money_format
#### Usage

```php
use function Phantasy\Core\PHP\money_format;
```

#### Examples
```php
$num = 1234.56;
setlocale(LC_MONETARY, 'en_IN');

money_format('%i', $num);
// 'INR 1,234.56'

$moneyFormat = money_format('%i');
$moneyFormat($num);
// 'INR 1,234.56'
```

### nl_langinfo
#### Usage

```php
use function Phantasy\Core\PHP\nl_langinfo;
```

#### Examples
```php
nl_langinfo(MON_1);
// "January"

$nlLangInfo = nl_langinfo();
$nlLangInfo(MON_1);
// "January"
```

### nl2br
#### Usage

```php
use function Phantasy\Core\PHP\nl2br;
```

#### Examples
```php
$str = "foo isn't\n bar";

nl2br($str);
// "foo isn't<br />\n bar"

$nl2br = nl2br();
$nl2br($str);
// "foo isn't<br />\n bar"
```

### nl2br2
#### Usage

```php
use function Phantasy\Core\PHP\nl2br2;
```

#### Examples
```php
$str = "Welcome\r\nThis is my HTML document";

nl2br2(false, $str);
// "Welcome<br>\r\nThis is my HTML document"

$nl2brNotXHtml = nl2br2(false);
$nl2brNotXHtml($str);
// "Welcome<br>\r\nThis is my HTML document"
```

### number_format
#### Usage

```php
use function Phantasy\Core\PHP\number_format;
```

#### Examples
```php
$num = 1234.56;
number_format($num);
// '1,235'

$numFormat = number_format();
$numFormat($num);
// '1,235'
```

### number_format2
#### Usage

```php
use function Phantasy\Core\PHP\number_format2;
```

#### Examples
```php
$num = 1234.56;
number_format2(2, $num);
// '1,234.56'

$formatToTwoDecimals = number_format2(2);
$formatToTwoDecimals($num);
// '1,234.56'
```

### number_format4
#### Usage

```php
use function Phantasy\Core\PHP\number_format4;
```

#### Examples
```php
$num = 1234.56;
number_format4(2, ',', ' ', $num);
// '1 234,56'

$frNumFormat = number_format4(2, ',', ' ');
$frNumFormat($num);
// '1 234,56'

```

### ord
#### Usage
```php
use function Phantasy\Core\PHP\ord;
```

#### Examples
```php
ord("\n");
// 10

$ord = ord();
$ord("\n");
// 10

```

### parse_str
#### Usage

```php
use function Phantasy\Core\PHP\parse_str;
```

#### Examples
```php
$str = "first=value&arr[]=foo+bar&arr[]=baz";

parse_str($str);
// ["first" => "value", "arr" => ["foo bar", "baz"]]

$parseStr = parse_str();
$parseStr($str);
// ["first" => "value", "arr" => ["foo bar", "baz"]]

```

### quotemeta
#### Usage

```php
use function Phantasy\Core\PHP\quotemeta;
```

#### Examples
```php
$str = "Hello world. (can you hear me?)";
quotemeta($str);
// "Hello world\. \(can you hear me\?\)"

$quotemeta = quotemeta();
$quotemeta($str);
// "Hello world\. \(can you hear me\?\)"

```

### rtrim
#### Usage

```php
use function Phantasy\Core\PHP\rtrim;
```

#### Examples
```php
rtrim("test\n");
// "test"

$rtrim = rtrim();
$trim("test\n");
// "test"

```

### rtrim2
#### Usage

```php
use function Phantasy\Core\PHP\rtrim2;
```

#### Examples
```php
rtrim2("World!", "Hello World!");
// "Hello "

$trimWorld = rtrim2("World!");
$trimWorld("Hello World!");
// "Hello "
```

### sha1_file
#### Usage

```php
use function Phantasy\Core\PHP\sha1_file;
```

#### Examples
```php
// Assuming $filepath exists...
sha1_file($filepath);
// '7b25c66924c90da0ffd45fa1e78f858d37cae7bc'

$sha1File = sha1_file();
$sha1File($filepath);
// '7b25c66924c90da0ffd45fa1e78f858d37cae7bc'
```

### sha1_file2
#### Usage

```php
use function Phantasy\Core\PHP\sha1_file2;
```

#### Examples
```php
// Assuming $filepath exists...
sha1_file2(false, $filepath);
// '7b25c66924c90da0ffd45fa1e78f858d37cae7bc'

$sha1FileNoRaw = sha1_file2(false);
$sha1FileNoRaw($filepath);
// '7b25c66924c90da0ffd45fa1e78f858d37cae7bc'
```

### sha1
#### Usage

```php
use function Phantasy\Core\PHP\sha1;
```

#### Examples
```php
$str = 'apple';
sha1($str);
// 'd0be2dc421be4fcd0172e5afceea3970e2f3d940'

$sha1 = sha1();
$sha1($str);
// 'd0be2dc421be4fcd0172e5afceea3970e2f3d940'
```

### sha12
#### Usage

```php
use function Phantasy\Core\PHP\sha12;
```

#### Examples
```php
$str = 'apple';
sha12(false, $str);
// 'd0be2dc421be4fcd0172e5afceea3970e2f3d940'

$sha1NoRaw = sha12(false);
$sha1NoRaw($str);
// 'd0be2dc421be4fcd0172e5afceea3970e2f3d940'
```

### similar_text
#### Usage

```php
use function Phantasy\Core\PHP\similar_text;
```

#### Examples
```php
$a = 'This is a test!';
$b = 'A test, this is!';
similar_text($a, $b);
// 7

$similarToA = similar_text($a);
$similarToA($b);
// 7
```

### similar_text_pct
#### Usage

```php
use function Phantasy\Core\PHP\similar_text_pct;
```

#### Examples
```php
$a = 'This is a test!';
$b = 'A test, this is!';
similar_text_pct($a, $b);
// 45.16129032258065

$similarToA = similar_text_pct($a);
$similarToA($b);
// 45.16129032258065
```

### soundex
#### Usage

```php
use function Phantasy\Core\PHP\soundex;
```

#### Examples
```php
$str = 'Euler';
soundex($str);
// 'E460'

$soundex = soundex();
$soundex($str);
// 'E460'
```

### str_getcsv
#### Usage

```php
use function Phantasy\Core\PHP\str_getcsv;
```

#### Examples
```php
$str = 'a,b';
str_getcsv($str);
// ['a', 'b']

$getCSV = str_getcsv();
$getCSV($str);
// ['a', 'b']
```

### str_getcsv2
#### Usage

```php
use function Phantasy\Core\PHP\str_getcsv2;
```

#### Examples
```php
$str = 'a.b';
str_getcsv2('.', $str);
// ['a', 'b']

$getCSVDotSep = str_getcsv2('.');
$getCSVDotSep($str);
// ['a', 'b']
```

### str_getcsv3
#### Usage

```php
use function Phantasy\Core\PHP\str_getcsv3;
```

#### Examples
```php
$str = '&a&.&b&';
str_getcsv3('.', '&', $str);
// ['a', 'b']

$getCSVDotSep = str_getcsv3('.');
$getCSVDotSepAmpEnclosure = $getCSVDotSep('&');
$getCSVDotSepAmpEnclosure($str);
// ['a', 'b']
```

### str_getcsv4
#### Usage

```php
use function Phantasy\Core\PHP\str_getcsv4;
```

#### Examples
```php
$str =' &^a&.&^b&';
str_getcsv4('.', '&', '^', $str);
// ['^a', '^b']

$getCSVDotSepAmpEncCaretEsc = str_getcsv4('.', '&', '^');
$getCSVDotSepAmpEncCaretEsc($str);
// ['^a', '^b']

```

### str_ireplace
#### Usage

```php
use function Phantasy\Core\PHP\str_ireplace;
```

#### Examples
```php
$str = 'This is a test!';
str_ireplace('!', '.', $str);
// 'This is a test.'

$quiet = str_ireplace('!', '.');
$quiet($str);
// 'This is a test.'
```

### str_ireplace_count
#### Usage

```php
use function Phantasy\Core\PHP\str_ireplace_count;
```

#### Examples
```php
$str = 'This is a test!';
str_ireplace_count('!', '.', $str);
// 1

$quiet = str_ireplace_count('!', '.');
$quiet($str);
// 1
```

### str_pad
#### Usage

```php
use function Phantasy\Core\PHP\str_pad;
```

#### Examples
```php
$str = 'Alien';
str_pad(6, $str);
// 'Alien '

$padTo6 = str_pad(6);
$padTo6($str);
// 'Alien '
```

### str_pad3
#### Usage

```php
use function Phantasy\Core\PHP\str_pad3;
```

#### Examples
```php
str_pad3(6, '-', $str);
// 'Alien-'

$padTo6 = str_pad3(6);
$padTo6WithDash = $padTo6('-');
$padTo6WithDash($str);
// 'Alien-'
```

### str_pad4
#### Usage

```php
use function Phantasy\Core\PHP\str_pad4;
```

#### Examples
```php
$str = 'Alien';
str_pad4(STR_PAD_LEFT, 7, '-', $str);
// '--Alien'

$padLeft = str_pad4(STR_PAD_LEFT);
$padTo7WithDashOnLeft = $padLeft(7, '-');
$padTo7WithDashOnLeft($str);
// '--Alien'
```

### str_repeat
#### Usage

```php
use function Phantasy\Core\PHP\str_repeat;
```

#### Examples
```php
str_repeat(2, 'a');
// 'aa'

$repeatTwice = str_repeat(2);
$repeatTwice('a');
// 'aa'
```

### str_replace
#### Usage
```php
use function Phantasy\Core\PHP\str_replace;
```

#### Examples
```php
$str = 'This is a test!';
str_replace('!', '.', $str);
// 'This is a test.'

$quiet = str_replace('!', '.');
$quiet($str);
// 'This is a test'
```

### str_replace_count
#### Usage

```php
use function Phantasy\Core\PHP\str_replace_count;
```

#### Examples
```php
$str = 'This is a test!';
str_replace_count('!', '.', $str);
// 1

$quiet = str_replace_count('!', '.');
$quiet($str);
// 1
```

### str_rot13
#### Usage

```php
use function Phantasy\Core\PHP\str_rot13;
```

#### Examples
```php
$str = 'PHP 4.3.0';
str_rot13($str);
// 'CUC 4.3.0'

$strRot13 = str_rot13();
$strRot13($str);
// 'CUC 4.3.0'
```

### str_shuffle
#### Usage

```php
use function Phantasy\Core\PHP\str_shuffle;
```

#### Examples
```php
$str = 'abcdefg';
str_shuffle($str);
// 'beafgdc'

$shuffle = str_shuffle();
$shuffle($str);
// 'beafgdc'
```

### str_split
#### Usage

```php
use function Phantasy\Core\PHP\str_split;
```

#### Examples
```php
$str = 'abc';
str_split($str);
// ['a', 'b', 'c']

$strSplit = str_split();
$strSplit($str);
// ['a', 'b', 'c']
```

### str_split2
#### Usage

```php
use function Phantasy\Core\PHP\str_split2;
```

#### Examples
```php
$str = 'abc';
str_split2(2, $str);
// ['ab', 'c']

$splitEveryTwo = str_split2(2);
$splitEveryTwo($str);
// ['ab', 'c']
```

### str_word_count
#### Usage

```php
use function Phantasy\Core\PHP\str_word_count;
```

#### Examples
```php
$str = 'This is a test.';
str_word_count($str);
// 4

$wc = str_word_count();
$wc($str);
// 4
```

### str_word_count2
#### Usage

```php
use function Phantasy\Core\PHP\str_word_count2;
```

#### Examples
```php
$str = 'This is a test.';
str_word_count2(1, $str);
// ['This', 'is', 'a', 'test']

$words = str_word_count2(1);
$words($str);
// ['This', 'is', 'a', 'test']

```

### str_word_count3
#### Usage

```php
use function Phantasy\Core\PHP\str_word_count3;
```

#### Examples
```php
$str = 'This is a test.';
$str_word_count3(0, "\ ", $str);
// 1

$wc = str_word_count3(0, "\ ");
$wc($str);
// 1
```

### strcasecmp
#### Usage

```php
use function Phantasy\Core\PHP\strcasecmp;
```

#### Examples
```php
$a = 'a';
$b = 'b';
strcasecmp($a, $b);
// -1

$cmpWithA = strcasecmp($a):
$cmpWithA($b);
// -1
```

### strchr
#### Usage

```php
use function Phantasy\Core\PHP\strchr;
```

#### Examples
```php
$email = 'name@example.com';
strchr('@', $email);
// '@example.com'

$searchForAt = strchr('@');
$searchForAt($email);
// '@example.com'

```

### strchr3
#### Usage

```php
use function Phantasy\Core\PHP\strchr3;
```

#### Examples
```php
$email = 'name@example.com';
strchr3(true, '@', $email);
// 'name'

$searchForAt = strchr3(true, '@');
$searchForAt($email);
// 'name'
```

### strcmp
#### Usage

```php
use function Phantasy\Core\PHP\strcmp;
```

#### Examples
```php
$a = 'a';
$b = 'b';
strcmp($a, $b);
// -1

$cmpWithA = strcmp($a);
$cmpWithA($b);
// -1
```

### strcoll
#### Usage

```php
use function Phantasy\Core\PHP\strcoll;
```

#### Examples
```php
$a = 'a';
$b = 'b';
strcoll($a, $b);
// -1

$cmpWithA = strcoll($a);
$cmpWithA($b);
// -1
```

### strcspn
#### Usage

```php
use function Phantasy\Core\PHP\strcspn;
```

#### Examples
```php
$mask = 'abcd';
$subject = 'apple';

strcspn($mask, $subject);
// 0

$lengthNotMatchingMask = strcspn($mask);
$lengthNotMatchingMask($subject);
// 0
```

### strcspn3
#### Usage

```php
use function Phantasy\Core\PHP\strcspn3;
```

#### Examples
```php
$mask = 'abcdhelloabcd';
$subject = 'abcd';
strcspn3(-9, $mask, $subject);
// 5

$f = strcspn3(-9);
$f($mask, $subject);
// 5

```

### strcspn4
#### Usage

```php
use function Phantasy\Core\PHP\strcspn4;
```

#### Examples
```php
$mask = 'abcdhelloabcd';
$subject = 'abcd';
strcspn4(-9, -5, $mask, $subject);
// 4

$f = strcspn4(-9, -5);
$f($mask, $subject);
// 4
```

### strip_tags
#### Usage

```php
use function Phantasy\Core\PHP\strip_tags;
```

#### Examples
```php
$text = '<p>Test paragraph </p><!-- Comment --> <a href="#fragment">Other text</a>';

strip_tags($text);
// "Test paragraph. Other text"

$stripTags = strip_tags($text);
$stripTags($text);
// "Test paragraph. Other text"
```

### strip_tags2
#### Usage

```php
use function Phantasy\Core\PHP\strip_tags2;
```

#### Examples
```php
$text = '<p>Test paragraph </p><!-- Comment --> <a href="#fragment">Other text</a>';

strip_tags2('<p><a>', $text);
// '<p>Test paragraph.</p> <a href="#fragment">Other text</a>'

$stripTagsKeepPAndA = strip_tags2('<p><a>');
$stripTagsKeepPAndA($text);
// '<p>Test paragraph.</p> <a href="#fragment">Other text</a>'
```

### stripcslashes
#### Usage

```php
use function Phantasy\Core\PHP\stripcslashes;
```

#### Examples
```php
$str = '\f\o\o\[ \]';
stripcslashes($str);
// "\r\noo[ ]"

$stripcslashes = stripcslashes();
$stripcslashes($str):
// "\r\noo[ ]"
```

### stripos
#### Usage

```php
use function Phantasy\Core\PHP\stripos;
```

#### Examples
```php
$findme = 'a';
$mystring = 'ABC';

stripos($findme, $mystring);
// 0

$findA = stripos($findme);
$findA($mystring);
// 0
```

### stripos3
#### Usage

```php
use function Phantasy\Core\PHP\stripos3;
```

#### Examples
```php
$findme = 'a';
$mystring = 'ABC';

stripos3(1, $findme, $mystring);
// false

$findA = stripos3(1, $findme);
$findA($mystring);
// false
```

### stripslashes
#### Usage

```php
use function Phantasy\Core\PHP\stripslashes;
```

#### Examples
```php
$str = "Is your name O\'reilly?"
stripslashes($str);
// "Is your name O'reilly?"

$stripslashes = stripslashes();
$stripslashes($str);
// "Is your name O'reilly?"
```

### stristr
#### Usage

```php
use function Phantasy\Core\PHP\stristr;
```

#### Examples
```php
$str = 'This is a test foo';

stristr('test', $str);
// 'test foo'

$afterTest = stristr('test');
$afterTest($str);
// 'test foo'
```

### stristr3
#### Usage
```php
use function Phantasy\Core\PHP\stristr3;
```
#### Examples
```php
$str = 'This is a test foo';

stristr3(true, 'test', $str);
// "This is a "

$beforeTest = stristr3(true, 'test');
$beforeTest($str);
// "This is a "
```

### strlen
#### Usage

```php
use function Phantasy\Core\PHP\strlen;
```

#### Examples
```php
$str = "This is a test";
strlen($str);
// 14

$strlen = strlen();
$strlen($str);
// 14
```

### strnatcasecmp
#### Usage
```php
use function Phantasy\Core\PHP\strnatcasecmp;
```

#### Examples
```php
$a = 'A';
$b = 'B';
strnatcasecmp($a, $b);
// -1

$cmpA = strnatcasecmp($a);
$cmpA($b);
// -1
```

### strnatcmp
#### Usage

```php
use function Phantasy\Core\PHP\strnatcmp;
```

#### Examples
```php
$a = 'A';
$b = 'B';
strnatcmp($a, $b);
// -1

$cmpA = strnatcmp($a);
$cmpA($b);
// -1
```

### strncasecmp
#### Usage

```php
use function Phantasy\Core\PHP\strncasecmp;
```

#### Examples
```php
$a = 'This is a foo!';
$b = 'This is a bar!';
strncasecmp(5, $a, $b);
// 0

$cmpFirst5 = strncasecmp(5);
$cmpFirst5($a, $b);
// 0
```

### strncmp
#### Usage

```php
use function Phantasy\Core\PHP\strncmp;
```

#### Examples
```php
$a = 'This is a foo!';
$b = 'This is a bar!';
strncmp(5, $a, $b);
// 0

$cmpFirst5 = strncmp(5);
$cmpFirst5($a, $b);
// 0
```

### strpbrk
#### Usage

```php
use function Phantasy\Core\PHP\strpbrk;
```

#### Examples
```php
$text = 'This is a Simple text.';

strpbrk('mi', $text);
// 'is is a Simple text.'

$stripMi = strpbrk('mi');
$stripMi($text);
// 'is is a Simple text.'
```

### strpos
#### Usage

```php
use function Phantasy\Core\PHP\strpos;
```

#### Examples
```php
$mystring = 'abc';
$findme = 'a';
strpos($findme, $mystring);
// 0

$findA = strpos($findme);
$findA($mystring);
// 0
```

### strpos3
#### Usage

```php
use function Phantasy\Core\PHP\strpos3;
```

#### Examples
```php
$mystring = 'abc';
$findme = 'a';
strpos3(1, $findme, $mystring);
// false

$findA = strpos3(1, $findme);
$findA($mystring);
// false
```

### strrchr
#### Usage

```php
use function Phantasy\Core\PHP\strrchr;
```

#### Examples
```php
$mystring = 'abc';
$findme = 'a';
strrchr($findme, $mystring);
// "abc"

$findA = strrchr($findme);
$findA($mystring):
// "abc"
```

### strrev
#### Usage

```php
use function Phantasy\Core\PHP\strrev;
```

#### Examples
```php
$str = 'abc';

strrev($str);
// 'cba'

$revStr = strrev();
$revStr($str);
// 'cba'
```

### strripos
#### Usage

```php
use function Phantasy\Core\PHP\strripos;
```

#### Examples
```php
$haystack = 'ababcd';
$needle = 'aB';

strripos($needle, $haystack);
// 2

$searchForNeedle = strripos($needle);
$searchForNeedle($haystack);
// 2
```

### strripos3
#### Usage

```php
use function Phantasy\Core\PHP\strripos3;
```

#### Examples
```php
$haystack = 'ababcd';
$needle = 'aB';

strripos3(1, $needle, $haystack);
// 2

$searchForNeedleAfter1 = strripos3(1, $needle);
$searchForNeedleAfter1($haystack);
// 2
```

### strrpos
#### Usage

```php
use function Phantasy\Core\PHP\strrpos;
```

#### Examples
```php
$haystack = 'ababcd';
$needle = 'aB';

strrpos($needle, $haystack);
// false

$searchForNeedle = strrpos($needle);
$searchForNeedle($haystack);
// false
```

### strrpos3
#### Usage

```php
use function Phantasy\Core\PHP\strrpos3;
```

#### Examples
```php
$haystack = 'ababcd';
$needle = 'aB';

strrpos3(1, $needle, $haystack);
// false

$searchForNeedleAfter1 = strrpos3(1, $needle);
$searchForNeedleAfter1($haystack);
// false
```

### strspn
#### Usage

```php
use function Phantasy\Core\PHP\strspn;
```

#### Examples
```php
$subject = "foo";
$mask = "o";
strspn($mask, $subject);
// 0

$strspnOMask = strspn($mask);
$strspnOMask($subject);
// 0
```

### strspn3
#### Usage

```php
use function Phantasy\Core\PHP\strspn3;
```

#### Examples
```php
$subject = "foo";
$start = 1;
$mask = "o";
strspn3($start, $mask, $subject);
// 2

$strspnOMaskAfter1 = strspn3(1, "o");
$strspnOMaskAfter1($subject);
// 2
```

### strspn4
#### Usage

```php
use function Phantasy\Core\PHP\strspn4;
```

#### Examples
```php
$subject = "foo";
$start = 1;
$length = 2;
$mask = "o";
strspn4($start, $length, $mask, $subject);
// 2

$strspnOMask1To2 = strspn4(1, 2, "o");
$strspnOMask1To2("foo");
// 2
```

### strstr
#### Usage
```php
use function Phantasy\Core\PHP\strstr;
```

#### Examples
```php
$email = 'name@example.com';
strstr('@', $email);
// '@example.com'

$searchForAt = strstr('@');
$searchForAt($email);
// '@example.com'
```

### strstr3
#### Usage

```php
use function Phantasy\Core\PHP\strstr3;
```

#### Examples
```php
$email = 'name@example.com';
strstr3(true, '@', $email);
// 'name'

$beforeAt = strstr3(true, '@');
$beforeAt($email);
// 'name' 
```

### strtok
#### Usage

```php
use function Phantasy\Core\PHP\strtok;
```

#### Examples
```php
$str = 'This is a test';
strtok(' ', $str);
// 'This'

$strtok = strtok(' ');
$strtok($str);
// 'This'

```

### strtok1
#### Usage

```php
use function Phantasy\Core\PHP\strtok1;
```

#### Examples
```php
$str = 'This is a test';
$tok = strtok(' ', $str);
$arr = [$tok];
while ($tok !== false) {
    $tok = strtok1(' ');
    $arr[] = $tok;
}
// $arr = ['This', 'is', 'a', 'test', false];
```

### strtolower
#### Usage

```php
use function Phantasy\Core\PHP\strtolower;
```

#### Examples
```php
$str = 'AbCdE';
strtolower($str);
// 'abcde'

$lower = strtolower();
$lower($str);
// 'abcde'
```

### strtoupper
#### Usage

```php
use function Phantasy\Core\PHP\strtoupper;
```

#### Examples
```php
$str = 'AbCdE';
strtoupper($str);
// 'ABCDE'

$upper = strtoupper();
$upper($str);
// 'ABCDE'
```

### strtr
#### Usage

```php
use function Phantasy\Core\PHP\strtr;
```

#### Examples
```php
// Can call this with 3 string params
strtr("ab", "01", "baab");
// "1001"

$swapabWith01 = strtr("ab", "01");
$swapabWith01("baab");
// "1001"

// Or 2 params, with the first an array
$trans = ["h" => "-", "hello" => "hi", "hi" => "hello"];
$str = "hi all, I said hello";
strtr($trans, $str);
// "hello all, I said hi"

$swapHiHello = strtr($trans);
$swapHiHello($str);
// "hello all, I said hi"
```

### substr_compare
#### Usage

```php
use function Phantasy\Core\PHP\substr_compare;
```

#### Examples
```php
$a = "abcde";
$b = "bc";

substr_compare(1, $b, $a);
// 2

$cmp1 = substr_compare(1);
$cmp1($b, $a);
// 2
```

### substr_compare4
#### Usage

```php
use function Phantasy\Core\PHP\substr_compare4;
```

#### Examples
```php
$main_str = "abcde";
$str = "bc";
$length = 2;
$offset = 1;

substr_compare4($length, $offset, $str, $main_str);
// 0

$cmpAfter1Length2 = substr_compare4(2, 1);
$cmpAfter1Length2($str, $main_str);
// 0
```

### substr_compare5
#### Usage

```php
use function Phantasy\Core\PHP\substr_compare5;
```

#### Examples
```php
$main_str = "abcde";
$str = "BC";
$length = 2;
$offset = 1;
$caseInsensitivity = true;

substr_compare5(
    $caseInsensitivity,
    $length,
    $offset,
    $str,
    $main_str
);
// 0

$icmp1To2 = substr_compare5(
    $caseInsensitivity,
    $length,
    $offset
);

$icmp1To2($str, $main_str);
// 0
```

### substr_count
#### Usage

```php
use function Phantasy\Core\PHP\substr_count;
```

#### Examples
```php
$text = 'This is a test';

substr_count('is', $text);
// 2

$countIs = substr_count('is');
$countIs($text);
// 2
```

### substr_count3
#### Usage

```php
use function Phantasy\Core\PHP\substr_count3;
```

#### Examples
```php
$text = 'This is a test';

substr_count3(3, 'is', $text);
// 1

$countIsAfter3 = substr_count3(3, 'is');
$countIsAfter3($text);
// 1
```

### substr_count4
#### Usage

```php
use function Phantasy\Core\PHP\substr_count4;
```

#### Examples
```php
$text = 'This is a test';

substr_count4(4, 3, 'is', $text);
// 1

$countIsFrom3To7 = substr_count4(4, 3, 'is');
$countIsFrom3To7($text);
// 1
```

### substr_replace
#### Usage

```php
use function Phantasy\Core\PHP\substr_replace;
```

#### Examples
```php
$str = 'ABCDEFGH:/MNRPQR/';

substr_replace(0, 'bob', $str);
// 'bob'

$replaceStrWithBob = substr_replace(0, 'bob');
$replaceStrWithBob($str);
// 'bob'
```

### substr_replace4
#### Usage

```php
use function Phantasy\Core\PHP\substr_replace4;
```

#### Examples
```php
$str = 'ABCDEFGH:/MNRPQR/';

substr_replace4(strlen($str), 0, 'bob', $str);
// 'bob'

$replaceStrWithBob = substr_replace4(strlen($str), 0, 'bob');
$replaceStrWithBob($str);
// 'bob'
```

### substr
#### Usage

```php
use function Phantasy\Core\PHP\substr;
```

#### Examples
```php
substr(-1, "abcdef");
// "f"

$takeLast = substr(-1);
$takeLast("abcdef");
// "f"
```

### substr3
#### Usage

```php
use function Phantasy\Core\PHP\substr3;
```

#### Examples
```php
substr3(1, -3, "abcdef");
// "d"

$takeThirdFromEnd = substr3(1, -3);
$takeThirdFromEnd("abcdef");
// "d"
```

### trim
#### Usage

```php
use function Phantasy\Core\PHP\trim;
```

#### Examples
```php
$str = " abcd \n";
trim($str);
// "abcd"

$trim = trim();
$trim($str);
// "abcd"
```

### trim2
#### Usage

```php
use function Phantasy\Core\PHP\trim2;
```

#### Examples
```php
$str = "abcdx";

trim2("x", $str);
// "abcd"

$trimX = trim2("x");
$trimX($str);
// "abcd"
```

### ucfirst
#### Usage

```php
use function Phantasy\Core\PHP\ucfirst;
```

#### Examples
```php
$str = "abcd";
ucfirst($str);
// "Abcd"

$ucfirst = ucfirst();
$ucfirst($str);
// "Abcd"
```

### ucwords
#### Usage

```php
use function Phantasy\Core\PHP\ucwords;
```

#### Examples
```php
$str = "hello world!";

ucwords($str):
// "Hello World!"

$ucwords = ucwords();
$ucwords($str);
// "Hello World!"
```

### ucwords2
#### Usage

```php
use function Phantasy\Core\PHP\ucwords2;
```

#### Examples
```php
$str = "hello|world!";

ucwords2("|", $str);
// "Hello|World!"

$ucwordsSplitByPipe = ucwords2("|");
$ucwordsSplitByPipe($str);
// "Hello|World!"
```

### wordwrap
#### Usage

```php
use function Phantasy\Core\PHP\wordwrap;
```

#### Examples
```php
$str = "This needs to be quite a long string in order to trigger the default wordwrap.";
wordwrap($str);
// "This needs to be quite a long string in order to trigger the default \nwordwrap"

$wordwrap = wordwrap();
$wordwrap($str);
// "This needs to be quite a long string in order to trigger the default \nwordwrap"
```

### wordwrap2
#### Usage

```php
use function Phantasy\Core\PHP\wordwrap2;
```

#### Examples
```php
$str = "The quick brown fox jumps over the lazy dog";

wordwrap2(10, $str);
// "The quick\nbrown fox\njumps over\nthe lazy\ndog."

$wrapEvery10 = wordwrap2(10);
$wrapEvery10($str);
// "The quick\nbrown fox\njumps over\nthe lazy\ndog."
```

### wordwrap3
#### Usage

```php
use function Phantasy\Core\PHP\wordwrap3;
```

#### Examples
```php
$str = "The quick brown fox jumps over the lazy dog";

wordwrap3("<br/>", 10, $str);
// "The quick<br/>brown fox</br>jumps over<br/>the lazy</br>dog."

$wrapEvery10 = wordwrap3("<br/>", 10);
$wrapEvery10($str);
// "The quick<br/>brown fox</br>jumps over<br/>the lazy</br>dog."
```

### wordwrap4
#### Usage

```php
use function Phantasy\Core\PHP\wordwrap4;
```

#### Examples
```php
$text = "A very long woooooooooooord.";
wordwrap4(true, "\n", 8, $text);
// "A very\nlong\nwooooooo\nooooord."

$wrap8 = wordwrap4(true, "\n", 8);
$wrap8($text);
// "A very\nlong\nwooooooo\nooooord."
```

## Array Functions

### array_change_key_case
#### Usage

```php
use function Phantasy\Core\PHP\array_change_key_case;
```

#### Examples
```php
$arr = ["FirSt" => 1, "SecOnd" => 4];
array_change_key_case($arr);
// ["first" => 1, "second" => 4]

$changeKeyCase = array_change_key_case();
$changeKeyCase($arr);
// ["first" => 1, "second" => 4]
```

### array_change_key_case2
#### Usage

```php
use function Phantasy\Core\PHP\array_change_key_case2;
```

#### Examples
```php
$arr = ["FirSt" => 1, "SecOnd" => 4];
array_change_key_case2(CASE_UPPER, $arr);
// ["FIRST" => 1, "SECOND" => 4]

$upperKeyCase = array_change_key_case2(CASE_UPPER);
$upperKeyCase($arr);
// ["FIRST" => 1, "SECOND" => 4]
```

### array_chunk
#### Usage

```php
use function Phantasy\Core\PHP\array_chunk;
```

#### Examples
```php
$arr = ['a', 'b', 'c', 'd', 'e'];

array_chunk(2, $arr);
// [ ['a', 'b'], ['c', 'd'], ['e'] ]

$chunkEvery2 = array_chunk(2);
$chunkEvery2($arr);
// [ ['a', 'b'], ['c', 'd'], ['e'] ]
```

### array_chunk3
#### Usage

```php
use function Phantasy\Core\PHP\array_chunk3;
```

#### Examples
```php
$arr = ['a', 'b', 'c', 'd', 'e'];

array_chunk3(true, 2, $arr);
// [ [0 => 'a', 1 => 'b'], [2 => 'c', 3 => 'd'], [4 => 'e'] ]

$chunkEvery2KeepKeys = array_chunk3(true, 2);
$chunkEvery2($arr);
// [ [0 => 'a', 1 => 'b'], [2 => 'c', 3 => 'd'], [4 => 'e'] ]
```

### array_column
#### Usage

```php
use function Phantasy\Core\PHP\array_column;
```

#### Examples
```php
$records = [
    [
        'id' => 2135,
        'first_name' => 'John',
        'last_name' = 'Doe'
    ],
    [
        'id' => 3245,
        'first_name' => 'Sally',
        'last_name' => 'Smith'
    ],
    [
        'id' => 5342,
        'first_name' => 'Jane',
        'last_name' => 'Jones'
    ]
];

array_column('first_name', $records);
// ['John', 'Sally', 'Jane']

$firstNames = array_column('first_name');
$firstNames($records);
// ['John', 'Sally', 'Jane']
```

### array_column3
#### Usage

```php
use function Phantasy\Core\PHP\array_column3;
```

#### Examples
```php
$records = [
    [
        'id' => 2135,
        'first_name' => 'John',
        'last_name' = 'Doe'
    ],
    [
        'id' => 3245,
        'first_name' => 'Sally',
        'last_name' => 'Smith'
    ],
    [
        'id' => 5342,
        'first_name' => 'Jane',
        'last_name' => 'Jones'
    ]
];

array_column3('id', 'first_name', $records);
// [2135 => 'John', 3245 => 'Sally', 5342 => 'Jane']

$firstNamesById = array_column3('id', 'first_name');
$firstNamesById($records);
// [2135 => 'John', 3245 => 'Sally', 5342 => 'Jane']
```

### array_combine
#### Usage

```php
use function Phantasy\Core\PHP\array_combine;
```

#### Examples
```php
$a = ['green', 'red', 'yellow'];
$b = ['avocado', 'apple', 'banana'];

array_combine($a, $b);
// ['green' => 'avocado', 'red' => 'apple', 'yellow' => 'banana']

$keyByA = array_combine($a);
$keyByA($b);
// ['green' => 'avocado', 'red' => 'apple', 'yellow' => 'banana']
```

### array_count_values
#### Usage

```php
use function Phantasy\Core\PHP\array_count_values;
```

#### Examples
```php
$arr = [1, "hello", 1, "world", "hello"];

array_count_values($arr);
// [1 => 2, "hello" => 2, "world" => 2]

$countVals = array_count_values();
$countVals($arr);
// [1 => 2, "hello" => 2, "world" => 2]
```

### array_diff_assoc
#### Usage

```php
use function Phantasy\Core\PHP\array_diff_assoc;
```

#### Examples
```php
$a = ['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'];
$b = ['a' => 'green', 'yellow', 'red'];

array_diff_assoc($a, $b);
// ['b' => 'brown', 'c' => 'blue', 0 => 'red']

$diffA = array_diff_assoc($a);
$diffA($b);
// ['b' => 'brown', 'c' => 'blue', 0 => 'red']
```

### array_diff_key
#### Usage

```php
use function Phantasy\Core\PHP\array_diff_key;
```

#### Examples
```php
$a = ['blue' => 1, 'red' => 2, 'green' => 3, 'purple' => 4];
$b = ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan' => 8];

array_diff_key($a, $b);
// [ 'red' => 2, 'purple' => 4 ]

$diffKeyA = array_diff_key($a);
$diffKeyA($b);
// [ 'red' => 2, 'purple' => 4 ]
```

### array_diff_uassoc
#### Usage

```php
use function Phantasy\Core\PHP\array_diff_uassoc;
```

#### Examples
```php
$a = ['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'];
$b = ['a' => 'green', 'yellow', 'red'];

$keyComp = function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return $a > $b ? 1 : -1;
};
array_diff_uassoc($keyComp, $a, $b);
// [ 'b' => 'brown', 'c' => 'blue', 0 => 'red' ]

$diffKey = array_diff_uassoc($keyComp);
$diffKey($a, $b);
// [ 'b' => 'brown', 'c' => 'blue', 0 => 'red' ]
```

### array_diff_ukey
#### Usage

```php
use function Phantasy\Core\PHP\array_diff_ukey;
```

#### Examples
```php
$a = ['blue' => 1, 'red' => 2, 'green' => 3, 'purple' => 4];
$b = ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan' => 8];

$keyComp = function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return $a > $b ? 1 : -1;
};
array_diff_ukey($keyComp, $a, $b);
// ['red' => 2, 'purple' => 4]

$diffUKeyComp = array_diff_ukey($keyComp);
$diffUKeyComp($a, $b);
// ['red' => 2, 'purple' => 4]
```

### array_diff
#### Usage

```php
use function Phantasy\Core\PHP\array_diff;
```

#### Examples
```php
$arr = ['a' => 'green', 'red', 'blue', 'red'];
$arr2 = ['b' => 'green', 'yellow', 'red'];

array_diff($arr, $arr2);
// [1 => 'blue']

$diffArr = array_diff($arr);
$diffArr($arr2);
// [1 => 'blue']
```

### array_fill_keys
#### Usage

```php
use function Phantasy\Core\PHP\array_fill_keys;
```

#### Examples
```php
$keys = ['foo', 5, 10, 'bar'];
array_fill_keys('banana', $keys);
// ['foo' => 'banana', 5 => 'banana', 10 => 'banana', 'bar' => 'banana']

$fillKeysWithBanana = array_fill_keys('banana');
$fillKeysWithBanana($keys);
// ['foo' => 'banana', 5 => 'banana', 10 => 'banana', 'bar' => 'banana']
```

### array_fill
#### Usage

```php
use function Phantasy\Core\PHP\array_fill;
```

#### Examples
```php
array_fill(3, 5, 'banana');
// [5 => 'banana', 6 => 'banana', 7 => 'banana']
```

### array_filter
#### Usage
```php
use function Phantasy\Core\PHP\array_filter;
```
#### Examples
```php
$arr = ['foo', false, -1, null, ''];
array_filter($arr);
// [0 => 'foo', 2 => -1]

$filter = array_filter();
$filter($arr);
// [0 => 'foo', 2 => -1]
```

### array_filter2
#### Usage

```php
use function Phantasy\Core\PHP\array_filter2;
```

#### Examples
```php
$even = function($x) {
    return $x % 2 === 0;
};

$arr = [6, 7, 8, 9, 10, 11, 12];
array_filter2($even, $arr);
// [0 => 6, 2 => 8, 4 => 10, 6, => 12]

$odd = function($x) {
    return $x % 2 === 1;
};

$arr2 = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5];
array_filter2($odd, $arr);
// ['a' => 1, 'c' => 3, 'e' => 5]
```

### array_filter3
#### Usage

```php
use function Phantasy\Core\PHP\array_filter3;
```

#### Examples
```php
$arr = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
$isB = function($k) {
    return $k === 'b';
};

array_filter3(ARRAY_FILTER_USE_KEY, $isB, $arr);
// ['b' => 2]

$keyFilter = array_filter3(ARRAY_FILTER_USE_KEY);
$bKey = $keyFilter($isB);
$bKey($arr);
// ['b' => 2]

$keyIsBOrValIs4 = function($v, $k) {
    return $k === 'b' || $v === '4';
};

array_filter3(ARRAY_FILTER_USE_BOTH, $keyIsBOrValIs4, $arr);
// ['b' => 2, 'd' => 4]

$keyOrValFilter = array_filter3(ARRAY_FILTER_USE_BOTH);
$filterBKeyOr4Val = $keyOrValFilter($keyIsBOrValIs4);
$filterBKeyOr4Val($arr);
// ['b' => 2, 'd' => 4]
```

### array_flip
#### Usage

```php
use function Phantasy\Core\PHP\array_flip;
```

#### Examples
```php
$arr = ['oranges', 'apples', 'pears'];
array_flip($arr);
// ['oranges' => 0, 'apples' => 1, 'pears' => 2];

$flip = array_flip();
$flip($arr);
// ['oranges' => 0, 'apples' => 1, 'pears' => 2];

$collisionArr = ['a' => 1, 'b' => 1, 'c' => 2];
array_flip($collisionArr);
// [1 => 'b', 2 => 'c']
```

### array_intersect_assoc
#### Usage

```php
use function Phantasy\Core\PHP\array_intersect_assoc;
```

#### Examples
```php
$a = ['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'];
$b = ['a' => 'green', 'b' => 'yellow', 'blue', 'red'];
array_intersect_assoc($a, $b);
// ['a' => 'green']

$intersectA = array_intersect_assoc($a);
$intersectA($b);
// ['a' => 'green']
```

### array_intersect_key
#### Usage

```php
use function Phantasy\Core\PHP\array_intersect_key;
```

#### Examples
```php
$a = ['blue' => 1, 'red' => 2, 'green' => 3, 'purple' => 4];
$b = ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan' => 8];

array_intersect_key($a, $b);
// ['blue' => 1, 'green' => 3]

$keyIntersectA = array_key_intersect($a);
$keyIntersectA($b);
// ['blue' => 1, 'green' => 3]
```

### array_intersect_uassoc
#### Usage

```php
use function Phantasy\Core\PHP\array_intersect_uassoc;
```

#### Examples
```php
use function Phantasy\Core\PHP\{
    strcasecmp,
    array_intersect_uassoc
};

$a = ['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'];
$b = ['a' => 'GREEN', 'B' => 'brown', 'yellow', 'red'];

array_intersect(strcasecmp(), $a, $b);
// ['b' => 'brown']

$intersectOnCaseCmp = array_intersect(strcasecmp());
$intersectOnCaseCmp($a, $b);
// ['b' => 'brown']
```

### array_intersect_ukey
#### Usage

```php
use function Phantasy\Core\PHP\array_intersect_ukey;
```

#### Examples
```php
$a = ['blue' => 1, 'red' => 2, 'green' => 3, 'purple' => 4];
$b = ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan' => 8];

$keyComp = function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return $a > $b ? 1 : -1;
};

array_intersect_ukey($keyComp, $a, $b);
// ['blue' => 1, 'green' => 3]

$keyIntersect = array_intersect_ukey($keyComp);
$keyIntersect($a, $b);
// ['blue' => 1, 'green' => 3]
```

### array_intersect
#### Usage

```php
use function Phantasy\Core\PHP\array_intersect;
```

#### Examples
```php
$a = ['a' => 'green', 'red', 'blue'];
$b = ['b' => 'green', 'yellow', 'red'];

array_intersect($a, $b);
// ['a' => 'green', 0 => 'red']

$intersectA = array_intersect($a);
$intersectA($b);
// ['a' => 'green', 0 => 'red']
```

### array_key_exists
#### Usage

```php
use function Phantasy\Core\PHP\array_key_exists;
```

#### Examples
```php
$arr = ['first' => 1, 'second' => 4];
array_key_exists('first', $arr);
// true

$searchForFirst = array_key_exists('first');
$searchForFirst($arr);
// true

$arr2 = ['first' => null];
isset($arr2['first']);
// false
array_key_exists('first', $arr2);
// true
```

### key_exists
#### Usage

```php
use function Phantasy\Core\PHP\key_exists;
```

#### Examples
```php
$arr = ['first' => 1, 'second' => 4];
key_exists('first', $arr);
// true

$searchForFirst = key_exists('first');
$searchForFirst($arr);
// true

$arr2 = ['first' => null];
isset($arr2['first']);
// false
key_exists('first', $arr2);
// true
```

### array_keys
#### Usage

```php
use function Phantasy\Core\PHP\array_keys;
```

#### Examples
```php
$arr = [0 => 100, 'color' => 'red'];
array_keys($arr);
// [0 => 0, 1 => 'color']

$arr2 = ['color' => ['blue', 'red', 'green']];
array_keys($arr2);
// ['color']

$keys = array_keys();
$keys($arr2);
// ['color']
```

### array_keys2
#### Usage

```php
use function Phantasy\Core\PHP\array_keys_2;
```

#### Examples
```php
$arr = ['blue', 'red', 'green', 'blue', 'blue'];
array_keys2('blue', $arr);
// [0, 3, 4]

$keysThatHaveBlue = array_keys2('blue');
$keysThatHaveBlue($arr);
// [0, 3, 4]
```

### array_keys3
#### Usage

```php
use function Phantasy\Core\PHP\array_keys3;
```

#### Examples
```php
$arr = ['blue', 'red', 'green', 'blue', 'blue'];
array_keys3(true, 'blue', $arr);
// [0, 3, 4]

$keysThatHaveStrictlyBlue = array_keys3(true, 'blue');
$keysThatHaveStrictlyBlue($arr);
// [0, 3, 4]
```

### array_map
#### Usage

```php
use function Phantasy\Core\PHP\array_map;
```

#### Examples
```php
$arr = [1, 2, 3];
$f = function($x) {
    return $x + 1;
};
array_map($f, $arr);
// [2, 3, 4]

$mapAddOne = array_map($f);
$mapAddOne($arr);
// [2, 3, 4]
```

### array_merge_recursive
#### Usage

```php
use function Phantasy\Core\PHP\array_merge_recursive;
```

#### Examples
```php
$a = ['color' => ['favorite' => 'red'], 5];
$b = [10, 'color' => ['favorite' => 'green', 'blue']];

array_merge_recursive($a, $b);
// ['color' => ['favorite' => ['red', 'green'], 0 => 'blue'], 0 => 5, 1 => 10]

$mergeA = array_merge_recursive($a);
$mergeA($b);
// ['color' => ['favorite' => ['red', 'green'], 0 => 'blue'], 0 => 5, 1 => 10]
```

### array_merge
#### Usage

```php
use function Phantasy\Core\PHP\array_merge;
```

#### Examples
```php
$arr = ['color' => 'red', 2, 4];
$arr2 = ['a', 'b', 'color' => 'green', 'shape' => 'trapezoid', 4];
array_merge($arr, $arr2);
// ['color' => 'green', 0 => 2, 1 => 4, 2 => 'a', 3 => 'b', 'shape' => 'trapezoid', 4 => 4];

$mergeArr = array_merge($arr);
$mergeArr($arr2);
// ['color' => 'green', 0 => 2, 1 => 4, 2 => 'a', 3 => 'b', 'shape' => 'trapezoid', 4 => 4];
```

### array_pad
#### Usage

```php
use function Phantasy\Core\PHP\array_pad;
```

#### Examples
```php
$arr = [12, 10, 9];

array_pad(5, 0, $arr);
// [12, 10, 9, 0, 0]

$padTo5 = array_pad(5);
$padTo5WithZero = $padTo5(0);
$padTo5WithZero($arr);
// [12, 10, 9, 0, 0]
```

### array_product
#### Usage

```php
use function Phantasy\Core\PHP\array_product;
```

#### Examples
```php
$a = [2, 4, 6, 8];
array_product($a);
// 384

$prod = array_product();
$prod($a);
// 384
```

### array_rand
#### Usage

```php
use function Phantasy\Core\PHP\array_rand;
```

#### Examples
```php
$arr = ['Neo', 'Morpheus', 'Trinity', 'Cypher', 'Tank'];
array_rand($arr);
// A possible output:
// 1

$rand = array_rand();
$rand($arr);
// A possible output:
// 3
```

### array_rand2
#### Usage

```php
use function Phantasy\Core\PHP\array_rand2;
```

#### Examples
```php
$arr = ['Neo', 'Morpheus', 'Trinity', 'Cypher', 'Tank'];
array_rand2(2, $arr);
// A possible output:
// [2, 1]

$twoKeys = array_rand(2);
$rand($arr);
// A possible output:
// [4, 3]
```

### array_replace_recursive
#### Usage

```php
use function Phantasy\Core\PHP\array_replace_recursive;
```

#### Examples
```php
$base = ['citrus' => ['orange'], 'berries' => ['blackberry', 'raspberry']];
$replacements = ['citrus' => ['pineapple'], 'berries' => ['blueberry']];

array_replace_recursive($replacements, $base);
// ['citrus' => ['pineapple'], 'berries' => ['blueberry', 'raspberry']]

$replaceWith = array_replace_recursive($replacements);
$replaceWith($base);
// ['citrus' => ['pineapple'], 'berries' => ['blueberry', 'raspberry']]
```

### array_replace
#### Usage

```php
use function Phantasy\Core\PHP\array_replace;
```

#### Examples
```php
$base = ['citrus' => ['orange'], 'berries' => ['blackberry', 'raspberry']];
$replacements = ['citrus' => ['pineapple'], 'berries' => ['blueberry']];

array_replace($replacements, $base);
// ['citrus' => ['pineapple'], 'berries' => ['blueberry']]

$replaceWith = array_replace($replacements);
$replaceWith($base);
// ['citrus' => ['pineapple'], 'berries' => ['blueberry']]
```

### array_reverse
#### Usage

```php
use function Phantasy\Core\PHP\array_reverse;
```

#### Examples
```php
$arr = ['php', 4.0, ['green', 'red']];
array_reverse($arr);
// [['green', 'red'], 4.0, 'php']

$reverse = array_reverse();
$reverse($arr);
// [['green', 'red'], 4.0, 'php']
```

### array_reverse2
#### Usage

```php
use function Phantasy\Core\PHP\array_reverse2;
```

#### Examples
```php
$arr = ['php', 4.0, ['green', 'red']];
array_reverse($arr);
// [['green', 'red'], 4.0, 'php']

$reverseWithKeys = array_reverse2(true);
$reverseWithKeys($arr);
// [2 => ['green', 'red'], 1 => 4.0, 0 => 'php']
```

### array_search
#### Usage

```php
use function Phantasy\Core\PHP\array_search;
```

#### Examples
```php
$arr = ['blue', 'red', 'green', 'red'];
array_search('green', $arr);
// 2
array_search('red', $arr);
// 1

$searchGreen = array_search('green');
$searchGreen($arr);
// 2
```

### array_search3
#### Usage

```php
use function Phantasy\Core\PHP\array_search3;
```

#### Examples
```php
$arr = ['blue', 'red', 'green', 'red'];
array_search3(true, 'green', $arr);
// 2
array_search(true, 'red', $arr);
// 1

$searchStrictGreen = array_search(true, 'green');
$searchStrictGreen($arr);
// 2
```

### array_slice
#### Usage

```php
use function Phantasy\Core\PHP\array_slice;
```

#### Examples
```php
$arr = ['a', 'b', 'c', 'd', 'e'];

array_slice(2, $arr);
// ['c', 'd', 'e']

$slice2 = array_slice(2);
$slice2($arr);
// ['c', 'd', 'e']
```

### array_slice3
#### Usage

```php
use function Phantasy\Core\PHP\array_slice3;
```

#### Examples
```php
$arr = ['a', 'b', 'c', 'd', 'e'];

array_slice3(-1, 2, $arr);
// ['c', 'd']

$slice = array_slice3(-1, 2);
$slice($arr);
// ['c', 'd']
```

### array_slice4
#### Usage

```php
use function Phantasy\Core\PHP\array_slice4;
```

#### Examples
```php
$arr = ['a', 'b', 'c', 'd', 'e'];

array_slice4(true, -1, 2, $arr);
// [2 => 'c', 3 => 'd']

$strictSlice = array_slice4(true, -1, 2);
$strictSlice($arr);
// [2 => 'c', 3 => 'd']
```

### array_sum
#### Usage

```php
use function Phantasy\Core\PHP\array_sum;
```

#### Examples
```php
$a = [2, 4, 6, 8];

array_sum($a);
// 20

$sum = array_sum();
$sum($a);
// 20
```

### array_udiff_assoc
#### Usage

```php
use function Phantasy\Core\PHP\array_udiff_assoc;
```

#### Examples
```php
$a = [5, 4, 3, 2, 1, 0];
$b = [6 => 1, 5 => 2, 4 => 3, 3 => 4, 2 => 7, 1 => 6];

$f = function($x, $y) {
    return $x - $y;
};
array_udiff_assoc($f, $a, $b);
// [5, 4, 3, 2, 1, 0]

$diff = array_udiff_assoc($f);
$diff($a, $b);
// [5, 4, 3, 2, 1, 0]
```

### array_udiff_uassoc
#### Usage

```php
use function Phantasy\Core\PHP\array_udiff_uassoc;
```

#### Examples
```php
$a = ['a' => 'red', 'b' => 'green', 'c' => 'blue'];
$b = ['a' => 'red', 'b' => 'green', 'c' => 'green'];

$keyCmp = function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return $b > $a ? -1 : 1;
};

$valueCmp = function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return $a > $b ? -1 : 1;
};

array_udiff_uassoc($keyCmp, $valueCmp, $a, $b);
// ['c' => 'blue']

$diff = array_udiff_uassoc($keyCmp, $valueCmp);
$diff($a, $b);
// ['c' => 'blue']
```

### array_udiff
#### Usage

```php
use function Phantasy\Core\PHP\array_udiff;
```

#### Examples
```php
$a = ['a' => 'red', 'b' => 'green', 'c' => 'blue'];
$b = ['a' => 'red', 'b' => 'green', 'c' => 'green'];

$cmp = function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return $b > $a ? -1 : 1;
};

array_udiff($cmp, $a, $b);
// ['c' => 'blue']

$diff = array_udiff($cmp):
$diff($a, $b);
// ['c' => 'blue']
```

### array_uintersect_assoc
#### Usage

```php
use function Phantasy\Core\PHP\array_uintersect_assoc;
```

#### Examples
```php
use function Phantasy\Core\PHP\{
    strcasecmp,
    array_uintersect_assoc
};
$a = ['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'];
$b = ['a' => 'GREEN', 'B' => 'brown', 'yellow', 'red'];

array_uintersect_assoc(strcasecmp(), $a, $b);
// ['a' => 'green']

$uintCaseCmp = array_uintersect_assoc(strcasecmp());
$uintCaseCmp($a, $b);
// ['a' => 'green']
```

### array_uintersect_uassoc
#### Usage

```php
use function Phantasy\Core\PHP\array_uintersect_uassoc;
```

#### Examples
```php
use function Phantasy\Core\PHP\{
    strcasecmp,
    strcmp,
    array_uintersect_uassoc
};
$a = ['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'];
$b = ['a' => 'GREEN', 'B' => 'brown', 'yellow', 'red'];

array_uintersect_uassoc(strcasecmp(), strcmp(), $a, $b);
// ['a' => 'green']

$uintUAssoc = array_uintersect_uassoc(strcasecmp(), strcmp());
$uintUAssoc($a, $b);
// ['a' => 'green']
```

### array_uintersect
#### Usage

```php
use function Phantasy\Core\PHP\array_uintersect;
```

#### Examples
```php
use function Phantasy\Core\PHP\{
    strcasecmp,
    array_uintersect
};
$a = ['a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red'];
$b = ['a' => 'GREEN', 'B' => 'brown', 'yellow', 'red'];

array_uintersect(strcasecmp(), $a, $b);
// ['a' => 'green', 'b' => 'brown', 0 => 'red']

$uIntCaseCmp = array_uintersect(strcasecmp());
$uIntCaseCmp($a, $b);
// ['a' => 'green', 'b' => 'brown', 0 => 'red']
```

### array_unique
#### Usage

```php
use function Phantasy\Core\PHP\array_unique;
```

#### Examples
```php
$arr = [1, 2, 3, 4, 1, 2, 3, 4];
array_unique($arr);
// [1, 2, 3, 4]

$uniq = array_unique();
$uniq($arr);
// [1, 2, 3, 4]
```

### array_unique2
#### Usage

```php
use function Phantasy\Core\PHP\array_unique2;
```

#### Examples
```php
$arr = ['1' ,'2', '3', '1', '2', '3', '4'];

array_unique2(SORT_NUMERIC, $arr);
// ['1', '2', '3', '4']

$uniqueSortNumeric = array_unique2(SORT_NUMERIC);
$uniqueSortNumeric($arr);
// ['1', '2', '3', '4']
```

### count
#### Usage

```php
use function Phantasy\Core\PHP\count;
```

#### Examples
```php
$arr = [1, 2, 3, 4];
count($arr);
// 4

$count = count();
$count($arr);
// 4
```

### count2
#### Usage

```php
use function Phantasy\Core\PHP\count2;
```

#### Examples
```php
$arr = [[1, 2], [3, 4]];
count2(COUNT_RECURSIVE, $arr);
// 6

$deepCount = count2(COUNT_RECURSIVE);
$deepCount($arr);
// 6
```

### sizeof
#### Usage

```php
use function Phantasy\Core\PHP\sizeof;
```

#### Examples
```php
$arr = [1, 2, 3, 4];
sizeof($arr);
// 4

$sizeof = sizeof();
$sizeof($arr);
// 4
```

### sizeof2
#### Usage

```php
use function Phantasy\Core\PHP\sizeof2;
```

#### Examples
```php
$arr = [[1, 2], [3, 4]];
sizeof2(COUNT_RECURSIVE, $arr);
// 6

$deepCount = sizeof2(COUNT_RECURSIVE);
$deepCount($arr);
// 6
```

### in_array
#### Usage

```php
use function Phantasy\Core\PHP\in_array;
```

#### Examples
```php
$arr = ['foo', 'bar'];
in_array('foo', $arr);
// true

$fooInArr = in_array('foo');
$fooInArr($arr);
// true
```

### in_array3
#### Usage

```php
use function Phantasy\Core\PHP\in_array3;
```

#### Examples
```php
$arr = ['foo', 'bar'];
in_array3(true, 'foo', $arr);
// true

$fooInArrStrict = in_array3(true, 'foo');
$fooInArrStrict($arr);
// true
```

### range
#### Usage

```php
use function Phantasy\Core\PHP\range;
```

#### Examples
```php
range(2, 5);
// [2, 3, 4, 5]

$rangeStartAt2 = range(2);
$rangeStartAt2(5);
// [2, 3, 4, 5]
```

### range3
#### Usage

```php
use function Phantasy\Core\PHP\range3;
```

#### Examples
```php
range3(10, 2, 30);
// [2, 12, 22]

$step10 = range3(10);
$step10(2, 30);
// [2, 12, 22]
```

### shuffle
#### Usage

```php
use function Phantasy\Core\PHP\shuffle;
```

#### Examples
```php
$arr = [1, 2, 3, 4, 5];

$shuffled = shuffle($arr);

// $arr is still [1, 2, 3, 4, 5]
// $shuffled could be [2, 5, 4, 3, 1]

$shuffle = shuffle();
$shuffle($arr);
// [3, 4, 1, 2, 5]
```

### rsort
#### Usage

```php
use function Phantasy\Core\PHP\rsort;
```

#### Examples
```php
$arr = [1, 2, 3];

$sorted = rsort($arr);
// $arr is still [1, 2, 3]
// $sorted is [3, 2, 1]

$rsort = rsort();
$rsort($arr);
// [3, 2, 1]
```

### rsort2
#### Usage

```php
use function Phantasy\Core\PHP\rsort2;
```

#### Examples
```php
$arr = ['1', '2', '3'];

$sorted = rsort2(SORT_NUMERIC, $arr);
// $arr = ['1', '2', '3']
// $sorted = ['3', '2', '1']

$rsortNumeric = rsort2(SORT_NUMERIC);
$rsortNumeric($arr);
// ['3', '2', '1']
```

### krsort
#### Usage

```php
use function Phantasy\Core\PHP\krsort;
```

#### Examples
```php
$arr = ['1' => 3, '2' => 2, '3' => 1];
$sorted = krsort($arr);
// $arr = ['1' => 3, '2' => 2, '3' => 1]
// $sorted = ['3' => 1, '2' => 2, '1' => 3]

$keyReverseSort = krsort();
$keyReverseSort($arr);
// ['3' => 1, '2' => 2, '1' => 3]
```

### krsort2
#### Usage

```php
use function Phantasy\Core\PHP\krsort2;
```

#### Examples
```php
$arr = ['1' => 3, '2' => 2, '3' => 1];
$sorted = krsort2(SORT_NUMERIC, $arr);
// $arr = ['1' => 3, '2' => 2, '3' => 1]
// $sorted = ['3' => 1, '2' => 2, '1' => 3]

$numKeyReverseSort = krsort2(SORT_NUMERIC);
$numKeyReverseSort($arr);
// ['3' => 1, '2' => 2, '1' => 3]
```

### ksort
#### Usage

```php
use function Phantasy\Core\PHP\ksort;
```

#### Examples
```php
$fruits = ['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple'];

$sorted = ksort($fruits);
// $sorted = ['a' => 'orange', 'b' => 'banana', 'c' => 'apple', 'd' => 'lemon']
// $fruits = ['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple']

$ksort = ksort();
$sorted2 = $ksort($fruits);
// $sorted2 = ['a' => 'orange', 'b' => 'banana', 'c' => 'apple', 'd' => 'lemon']
// $fruits = ['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple']
```

### ksort2
#### Usage

```php
use function Phantasy\Core\PHP\ksort2;
```

#### Examples
```php
$fruits = ['1' => 'lemon', '2' => 'banana', '3' => 'orange', '0' => 'apple']

$sorted = ksort2(SORT_NUMERIC, $fruits);
// $sorted = ['0' => 'apple', '1' => 'lemon', '2' => 'banana', '3' => 'orange']
// $fruits = ['1' => 'lemon', '2' => 'banana', '3' => 'orange', '0' => 'apple']

$ksortNumeric = ksort2(SORT_NUMERIC);
$ksortNumeric($fruits);
// ['0' => 'apple', '1' => 'lemon', '2' => 'banana', '3' => 'orange']
```

### natcasesort
#### Usage

```php
use function Phantasy\Core\PHP\natcasesort;
```

#### Examples
```php
$arr = ['IMG0.png', 'img12.png', 'img10.png', 'img2.png', 'img1.png', 'IMG3.png'];

$sorted = natcasesort($arr);
/*
$sorted = [
    0 => 'IMG0.png',
    4 => 'img1.png',
    3 => 'img2.png',
    5 => 'IMG3.png',
    2 => 'img10.png',
    1 => 'img12.png'
]
$arr is unchanged.
*/

$natCaseSort = natcasesort();
$natCaseSort($arr);
/*
$sorted = [
    0 => 'IMG0.png',
    4 => 'img1.png',
    3 => 'img2.png',
    5 => 'IMG3.png',
    2 => 'img10.png',
    1 => 'img12.png'
]
$arr is unchanged.
*/
```

### natsort
#### Usage

```php
use function Phantasy\Core\PHP\natsort;
```

#### Examples
```php
$arr = ['img12.png', 'img10.png', 'img2.png', 'img1.png'];
$sorted = natsort($arr);
/*
$sorted = [
    3 => 'img1.png',
    2 => 'img2.png',
    1 => 'img10.png',
    0 => 'img12.png'
];
$arr is unchanged
*/

$natSort = natsort();
$sorted = $natSort($arr);
/*
$sorted = [
    3 => 'img1.png',
    2 => 'img2.png',
    1 => 'img10.png',
    0 => 'img12.png'
];
$arr is unchanged
*/
```

### arsort
#### Usage

```php
use function Phantasy\Core\PHP\arsort;
```

#### Examples
```php
$fruits = ['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple'];

$sorted = arsort($fruits);
// $sorted = ['a' => 'orange', 'd' => 'lemon', 'b' => 'banana', 'c' => 'apple']
// $fruits is unchanged.

$arsort = arsort();
$sorted = $arsort($fruits);
// $sorted = ['a' => 'orange', 'd' => 'lemon', 'b' => 'banana', 'c' => 'apple']
// $fruits is unchanged.
```

### arsort2
#### Usage

```php
use function Phantasy\Core\PHP\arsort2;
```

#### Examples
```php
$arr = ['d' => '3', 'a' => '4', 'b' => '2', 'c' => '1'];

$sorted = arsort2(SORT_NUMERIC, $arr);
// ['a' => '4', 'd' => '3', 'b' => '2', 'c' => '1']
// $arr is unchanged.

$arsortNum = arsort2(SORT_NUMERIC);
$sorted = $arsortNum($arr);
// ['a' => '4', 'd' => '3', 'b' => '2', 'c' => '1']
// $arr is unchanged.
```

### asort
#### Usage

```php
use function Phantasy\Core\PHP\asort;
```

#### Examples
```php
$arr = ['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'apple'];

$sorted = asort($arr);
// $sorted = ['c' => 'apple', 'b' => 'banana', 'd' => 'lemon', 'a' => 'orange']
// $arr is unchanged

$asort = asort();
$sorted = asort($arr);
// $sorted = ['c' => 'apple', 'b' => 'banana', 'd' => 'lemon', 'a' => 'orange']
// $arr is unchanged
```

### asort2
#### Usage

```php
use function Phantasy\Core\PHP\asort2;
```

#### Examples
```php
$arr = ['d' => '3', 'a' => '4', 'b' => '2', 'c' => '1'];

$sorted = asort2(SORT_NUMERIC, $arr);
// ['c' => '1', 'b' => '2', 'd' => '3', 'a' => '4']
// $arr is unchanged.

$asortNum = asort2(SORT_NUMERIC);
$sorted = $asortNum($arr);
// ['c' => '1', 'b' => '2', 'd' => '3', 'a' => '4']
// $arr is unchanged.
```

### sort
#### Usage

```php
use function Phantasy\Core\PHP\sort;
```

#### Examples
```php
$arr = [3, 2, 4, 1];

$sorted = sort($arr);
// $sorted = [1, 2, 3, 4]
// $arr is unchanged

$sort = sort();
$sorted = $sort($arr);
// $sorted = [1, 2, 3, 4]
// $arr is unchanged
```

### sort2
#### Usage

```php
use function Phantasy\Core\PHP\sort2;
```

#### Examples
```php
$arr = ['3', '2', '4', '1'];

$sorted = sort2(SORT_NUMERIC, $arr);
// $sorted = ['1', '2', '3', '4']
// $arr is unchanged

$sortNum = sort2(SORT_NUMERIC);
$sorted = $sortNum($arr);
// $sorted = ['1', '2', '3', '4']
// $arr is unchanged
```

### uasort
#### Usage

```php
use function Phantasy\Core\PHP\uasort;
```

#### Examples
```php
$arr = [
    'a' => 4,
    'b' => 8,
    'c' => -1,
    'd' => -9,
    'e' => 2,
    'f' => 5,
    'g' => 3,
    'h' => -4
];
$f = function($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
};
$sorted = uasort($f, $arr);
/*
$sorted = [
    'd' => -9,
    'h' => -4,
    'c' => -1,
    'e' => 2,
    'g' => 3,
    'a' => 4,
    'f' => 5,
    'b' => 8
];
$arr is unchanged
*/

$uaSort = uasort($f);
$uaSort($arr);
/*
$sorted = [
    'd' => -9,
    'h' => -4,
    'c' => -1,
    'e' => 2,
    'g' => 3,
    'a' => 4,
    'f' => 5,
    'b' => 8
];
$arr is unchanged
*/
```

### uksort
#### Usage

```php
use function Phantasy\Core\PHP\uksort;
```

#### Examples
```php
use function Phantasy\Core\PHP\{
    uksort,
    strcasecmp
};

$arr = [
    "John" => 1,
    "the Earth" => 2,
    "an apple" => 3,
    "a banana" => 4
];
$cmp = function ($a, $b) {
    $a = preg_replace('@^(a|an|the) @', '', $a);
    $b = preg_replace('@^(a|an|the) @', '', $b);
    return strcasecmp($a, $b);
};

$sorted = uksort($cmp, $arr);
/*
$sorted = [
    'an apple' => 3,
    'a banana' => 4,
    'the Earth' => 2,
    'John' => 1
];
$arr is unchanged
*/

$keySort = uksort($cmp);
$sorted = $keySort($arr);
/*
$sorted = [
    'an apple' => 3,
    'a banana' => 4,
    'the Earth' => 2,
    'John' => 1
];
$arr is unchanged
*/
```

### usort
#### Usage

```php
use function Phantasy\Core\PHP\usort;
```

#### Examples
```php
$a = [3, 2, 5, 6, 1];
$cmp = function ($a, $b) {
    if ($a === $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
};
$sorted = usort($cmp, $a);
// $sorted = [1, 2, 3, 5, 6]
// $a is unchanged

$usortCmp = usort($cmp);
$sorted = $usortCmp($a);
// $sorted = [1, 2, 3, 5, 6]
// $a is unchanged
```

### array_push
#### Usage

```php
use function Phantasy\Core\PHP\array_push;
```

#### Examples
```php
$arr = [1, 2];
$newArr = array_push(3, $arr);
// $newArr = [1, 2, 3]
// $arr = [1, 2]

$push3 = array_push(3);
$newArr = $push3($arr);
// $newArr = [1, 2, 3]
// $arr = [1, 2]
```

### array_pop
#### Usage

```php
use function Phantasy\Core\PHP\array_pop;
```

#### Examples
```php
$arr = [1, 2];
$x = array_pop($arr);
// $x = 2
// $arr = [1, 2]

$pop = array_pop();
$x = $pop($arr);
// $x = 2
// $arr = [1, 2]
```

### array_shift
#### Usage

```php
use function Phantasy\Core\PHP\array_shift;
```

#### Examples
```php
$arr = [1, 2];
$x = array_shift($arr);
// $x = 1
// $arr = [1, 2]

$shift = array_shift();
$x = $shift($arr);
// $x = 1
// $arr = [1, 2]
```

### array_unshift
#### Usage

```php
use function Phantasy\Core\PHP\array_unshift;
```

#### Examples
```php
$arr = [1, 2];

$x = array_unshift($arr);
// 1
// $arr = [1, 2]

$unshift = array_unshift();
$x = $unshift($arr);
// 1
// $arr = [1, 2]
```

### array_splice
#### Usage

```php
use function Phantasy\Core\PHP\array_splice;
```

#### Examples
```php
$arr = ['red', 'green', 'blue', 'yellow'];
$res = array_splice(2, $arr);
// $res = ['red', 'green']
// $arr = ['red', 'green', 'blue', 'yellow']

$splice2 = array_splice(2);
$res = $splice2($arr);
// $res = ['red', 'green']
// $arr = ['red', 'green', 'blue', 'yellow']
```

### array_splice3
#### Usage

```php
use function Phantasy\Core\PHP\array_splice3;
```

#### Examples
```php
$arr = ['red', 'green', 'blue', 'yellow'];
$res = array_splice3(1, -1, $arr);
// $res = ['red', 'yellow']
// $arr = ['red', 'green', 'blue', 'yellow']

$splice = array_splice3(1, -1);
$res = $splice($arr);
// $res = ['red', 'yellow']
// $arr = ['red', 'green', 'blue', 'yellow']
```

### array_splice4
#### Usage

```php
use function Phantasy\Core\PHP\array_splice4;
```

#### Examples
```php
$arr = ['red', 'green', 'blue', 'yellow'];
$res = array_splice4(1, -1, ['black', 'maroon'], $arr);
// $res = ['red', 'black', 'maroon', 'yellow']
// $arr = ['red', 'green', 'blue', 'yellow']

$splice = array_splice4(1, -1);
$spliceWithReplacements = $splice(['black', 'maroon']);
$res = $spliceWithReplacements($arr);
// $res = ['red', 'black', 'maroon', 'yellow']
// $arr = ['red', 'green', 'blue', 'yellow']
```

## Date/Time Functions

### checkdate
#### Usage
```php
use function Phantasy\Core\PHP\checkdate;
```
#### Examples
```php
checkdate(12, 21, 2000);
// true

$checkFebDay = checkdate(2);
$checkLeapDay = $checkFebDay(29);
$checkLeapDay(2001);
// false
```

### date_add
#### Usage
```php
use function Phantasy\Core\PHP\date_add;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_add,
    date_create1
};
date_add(
    new DateInterval('P10D'),
    date_create1('2000-01-01')
);
/*
object(DateTime) {
    ["date"] => "2000-02-20 00:00:00",
    ...
}
*/

$addP10D = date_add(new DateInterval('P10D'));
$addP10D(date_create1('2000-01-01'));
/*
object(DateTime) {
    ["date"] => "2000-02-20 00:00:00",
    ...
}
*/
```

### date_create1
#### Usage
```php
use function Phantasy\Core\PHP\date_create1;
```

#### Examples
```php
date_create1('2000-01-01');
/*
object(DateTime) {
    ["date"] => "2000-01-01 00:00:00"
    ...
}
*/

$dateCreate = date_create1();
$dateCreate('2000-01-01');
/*
object(DateTime) {
    ["date"] => "2000-01-01 00:00:00"
    ...
}
*/
```

### date_create2
#### Usage
```php
use function Phantasy\Core\PHP\date_create2;
```
#### Examples
```php
date_create2(new DateTimeZone('Pacific/Nauru'), '2017-01-01');
/*
object(DateTime) {
    ["date"] => "2017-01-01 00:00:00",
    ["timezone"] => "Pacific/Nauru"
    ...
}
*/

$createPacificNauruDate = date_create2(new DateTimeZone('Pacific/Nauru'));
$createPacificNauruDate('2017-01-01');
/*
object(DateTime) {
    ["date"] => "2017-01-01 00:00:00",
    ["timezone"] => "Pacific/Nauru"
    ...
}
*/
```

### date_create_immutable1
#### Usage
```php
use function Phantasy\Core\PHP\date_create_immutable1;
```

#### Examples
```php
date_create_immutable1('2000-01-01');
/*
object(DateTimeImmutable) {
    ["date"] => "2000-01-01 00:00:00"
    ...
}
*/

$dateCreate = date_create_immutable1();
$dateCreate('2000-01-01');
/*
object(DateTimeImmutable) {
    ["date"] => "2000-01-01 00:00:00"
    ...
}
*/
```

### date_create_immutable2
#### Usage
```php
use function Phantasy\Core\PHP\date_create_immutable2;
```
#### Examples
```php
date_create_immutable2(new DateTimeZone('Pacific/Nauru'), '2017-01-01');
/*
object(DateTimeImmutable) {
    ["date"] => "2017-01-01 00:00:00",
    ["timezone"] => "Pacific/Nauru"
    ...
}
*/

$createPacificNauruDate = date_create_immutable2(new DateTimeZone('Pacific/Nauru'));
$createPacificNauruDate('2017-01-01');
/*
object(DateTimeImmutable) {
    ["date"] => "2017-01-01 00:00:00",
    ["timezone"] => "Pacific/Nauru"
    ...
}
*/
```

### date_create_from_format
#### Usage
```php
use function Phantasy\Core\PHP\date_create_from_format;
```
#### Examples
```php
date_create_from_format('j-M-Y', '15-Feb-2009');
/*
object(DateTime) {
    ["date"] => "2009-02-15 02:39:57",
    ...
}
*/

$createFromjMY = date_create_from_format('j-M-Y');
$createFromjMY('15-Feb-2009');
/*
object(DateTime) {
    ["date"] => "2009-02-15 02:39:57",
    ...
}
*/
```

### date_create_from_format3
#### Usage
```php
use function Phantasy\Core\PHP\date_create_from_format3;
```
#### Examples
```php
date_create_from_format3(
    new DateTimeZone('Pacific/Nauru'),
    'j-M-Y',
    '15-Feb-2009'
);
/*
object(DateTime) {
    ["date"] => "2009-02-15 02:39:57",
    ["timezone" => "Pacific/Nauru",
    ...
}
*/

$nauruDateFromFormat = date_create_from_format3(new DateTimeZone('Pacific/Nauru'));
$jMYNauruDate = $nauruDateFromFormat('j-M-Y');
$jMYNauruDate('15-Feb-2009');
/*
object(DateTime) {
    ["date"] => "2009-02-15 02:39:57",
    ["timezone" => "Pacific/Nauru",
    ...
}
*/
```

### date_create_immutable_from_format
#### Usage
```php
use function Phantasy\Core\PHP\date_create_immutable_from_format;
```
#### Examples
```php
date_create_immutable_from_format('j-M-Y', '15-Feb-2009');
/*
object(DateTimeImmutable) {
    ["date"] => "2009-02-15 02:39:57",
    ...
}
*/

$createFromjMY = date_create_immutable_from_format('j-M-Y');
$createFromjMY('15-Feb-2009');
/*
object(DateTimeImmutable) {
    ["date"] => "2009-02-15 02:39:57",
    ...
}
*/
```

### date_create_immutable_from_format3
#### Usage
```php
use function Phantasy\Core\PHP\date_create_immutable_from_format3;
```
#### Examples
```php
date_create_immutable_from_format3(
    new DateTimeZone('Pacific/Nauru'),
    'j-M-Y',
    '15-Feb-2009'
);
/*
object(DateTimeImmutable) {
    ["date"] => "2009-02-15 02:39:57",
    ["timezone" => "Pacific/Nauru",
    ...
}
*/

$nauruDateFromFormat = date_create_immutable_from_format3(new DateTimeZone('Pacific/Nauru'));
$jMYNauruDate = $nauruDateFromFormat('j-M-Y');
$jMYNauruDate('15-Feb-2009');
/*
object(DateTimeImmutable) {
    ["date"] => "2009-02-15 02:39:57",
    ["timezone" => "Pacific/Nauru",
    ...
}
*/
```

### date_date_set
#### Usage
```php
use function Phantasy\Core\PHP\date_date_set;
```
#### Examples
```php
date_date_set(2001, 2, 3, date_create());
/*
object(DateTime) {
    ["date"] => "2001-02-03 03:03:59",
    ...
*/

$setToFeb32001 = date_date_set(2001, 2, 3);
$setToFeb32001(date_create());
/*
object(DateTime) {
    ["date"] => "2001-02-03 03:04:12",
    ...
*/
```

### date_default_timezone_set
#### Usage
```php
use function Phantasy\Core\PHP\date_default_timezone_set;
```
#### Examples
```php
date_default_timezone_set('America/Los_Angeles');
// true

$setTimezone = date_default_timezone_set();
$setTimezone('America/Los_Angeles');
// true
```

### date_diff
#### Usage
```php
use function Phantasy\Core\PHP\date_diff;
```
#### Examples
```php
$a = new DateTime('2009-10-11');
$b = new DateTime('2009-10-13');

$c = date_diff($a, $b);
/*
object(DateInterval) {
...
["d"] => 2,
...
}
*/

$diffA = date_diff($a);
$d = $diffA($b);
/*
object(DateInterval) {
...
["d"] => 2,
...
}
*/
```

### date_diff3
#### Usage
```php
use function Phantasy\Core\PHP\date_diff3;
```
#### Examples
```php
$time1 = new DateTime('2009-10-13');
$time2 = new DateTime('2009-10-11');

$a = date_diff3(true, $time1, $time2);
/*
object(DateInterval) {
...
["d"] => 2,
...
}
*/

$diffAbsolute = date_diff3(true);
$b = $diffAbsolute($time1, $time2);
/*
object(DateInterval) {
...
["d"] => 2,
...
}
*/
```

### date_format
#### Usage
```php
use function Phantasy\Core\PHP\date_format;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_format,
    date_create1
};

$a = date_create1('2000-01-01');
$b = date_format('Y-m-d H:i:s', $a);
// 2001-01-01 00:00:00

$ymdHisDate = date_format('Y-m-d H:i:s');
$c = $ymdHisDate($a);
// 2001-01-01 00:00:00
```

### date_interval_create_from_date_string
#### Usage
```php
use function Phantasy\Core\PHP\date_interval_create_from_date_string;
```
#### Examples
```php
$a = date_interval_create_from_date_string('1 day');
/*
object(DateInterval) {
    ...
    ["d"] => 1,
    ...
}
*/

$intervalFromDateStr = date_interval_create_from_date_string();
$b = $intervalFromDateStr('1 day');
/*
object(DateInterval) {
    ...
    ["d"] => 1,
    ...
}
*/
```

### date_interval_format
#### Usage
```php
use function Phantasy\Core\PHP\date_interval_format;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_diff,
    date_interval_format
};
$a = new DateTime('2010-01-01');
$b = new DateTime('2010-02-01');
$interval = date_diff($a, $b);

$c = date_interval_format('%a total days', $interval);
// "31 total days"

$formatTotalDays = date_interval_format('%a total days');
$d = $formatTotalDays($interval);
// "31 total days"
```

### date_isodate_set
#### Usage
```php
use function Phantasy\Core\PHP\date_isodate_set;
```
#### Examples
```php
$a = new DateTime('2010-01-01');
$b = date_isodate_set(2012, 1, $a);
/*object(DateTime) {
    ["date"] => "2012-01-02 00:00:00"
    ...
}
*/

$setToJan2012 = date_isodate_set(2012, 1);
$c = $setToJan2012($a);
/*object(DateTime) {
    ["date"] => "2012-01-02 00:00:00"
    ...
}
*/
```

### date_isodate_set4
#### Usage
```php
use function Phantasy\Core\PHP\date_isodate_set4;
```
#### Examples
```php
$a = new DateTime('2010-01-01');
$b = date_isodate_set4(2012, 1, 1, $a);
/*object(DateTime) {
    ["date"] => "2012-01-02 00:00:00"
    ...
}
*/

$setToJan2012 = date_isodate_set4(2012, 1, 1);
$c = $setToJan2012($a);
/*object(DateTime) {
    ["date"] => "2012-01-02 00:00:00"
    ...
}
*/
```

### date_modify
#### Usage
```php
use function Phantasy\Core\PHP\date_modify;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_modify,
    date_create1
};

$a = date_create1('2006-12-12');
$b = date_modify('+1 day', $a);

$addADay = date_modify('+1 day');
$c = $addADay($a);
```

### date_offset_get
#### Usage
```php
use function Phantasy\Core\PHP\date_offset_get;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    timezone_open,
    date_create2
    date_offset_get
};

$a = date_create2(timezone_open('America/New_York'), '2010-12-21');

$offsetA = date_offset_get($a);
// -18000

$offsetGet = date_offset_get();
$offsetB = $offsetGet($a);
// -18000
```

### date_parse_from_format
#### Usage
```php
use function Phantasy\Core\PHP\date_parse_from_format;
```
#### Examples
```php
$date = '6.1.2009 13:00+01:00';

$res = date_parse_from_format('j.n.Y H:iP', $date);
/*
array(15) {
    ["year"] => 2009,
    ["month"] => 1,
    ["day" => 6,
    ...
}
*/

$parseDate = date_parse_from_format('j.n.Y H:iP');
$res2 = $parseDate($date);
/*
array(15) {
    ["year"] => 2009,
    ["month"] => 1,
    ["day" => 6,
    ...
}
*/
```

### date_parse
#### Usage
```php
use function Phantasy\Core\PHP\date_parse;
```
#### Examples
```php
$date = "2006-12-12 10:00:00.5";
$res = date_parse($date);
/*
array(12) {
    ["year"] => 2006,
    ["month"] => 12,
    ["day"] => 12
    ...
}
*/

$parse = date_parse();
$res2 = $parse($date);
/*
array(12) {
    ["year"] => 2006,
    ["month"] => 12,
    ["day"] => 12
    ...
}
*/
```

### date_sub
#### Usage
```php
use function Phantasy\Core\PHP\date_sub;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_create1,
    date_sub,
    date_interval_create_from_date_string
};

$date = date_create1('2000-01-20');
$interval = date_interval_create_from_date_string('10 days');
$res = date_sub($interval, $date);
/*
object(DateTime) {
    ["date"] => "2000-01-10',
    ...
}
*/

$sub10Days = date_sub($interval);
$res2 = $sub10Days($date);
/*
object(DateTime) {
    ["date"] => "2000-01-10',
    ...
}
*/
```

### date_sun_info
#### Usage
```php
use function Phantasy\Core\PHP\date_sun_info;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_sun_info,
    strtotime
};

$latitude = 31.7667;
$longitude = 35.2333;
$time = strtotime('2006-12-12');

$res = date_sun_info($lat, $long, $time);
/*
array(9) {
    ["sunrise"] => 1165897794,
    ["sunset"] => 1165934172,
    ["transit"] => 1165915983,
    ...
}
*/

$sunForFixedLatAndLong = date_sun_info($lat, $long);
$res2 = $sunForFixedLatAndLong($time);
/*
array(9) {
    ["sunrise"] => 1165897794,
    ["sunset"] => 1165934172,
    ["transit"] => 1165915983,
    ...
}
*/
```

### date_sunrise
#### Usage
```php
use function Phantasy\Core\PHP\date_sunrise;
```
#### Examples
```php
$res = date_sunrise(time());
// "20:00"

$sunrise = date_sunrise();
$res2 = $sunrise(time());
// "20:00"
```

### date_sunrise2
#### Usage
```php
use function Phantasy\Core\PHP\date_sunrise2;
```
#### Examples
```php
$time = time();
$res = date_sunrise2(SUNFUNCS_RET_STRING, $time);
// "20:00"

$stringSunrise = date_sunrise2(SUNFUNCS_RET_STRING);
$res2 = $stringSunrise($time);
// "20:00"
```

### date_sunset
#### Usage
```php
use function Phantasy\Core\PHP\date_sunset;
```
#### Examples
```php
$time = time();
$res = date_sunset($time);
// "06:00"

$sunset = date_sunset();
$sunset($time);
// "06:00"

```

### date_sunset2
#### Usage
```php
use function Phantasy\Core\PHP\date_sunset2;
```
#### Examples
```php
$time = time();
$res = date_sunset2(SUNFUNCS_RET_STRING, $time);
// "06:00"

$stringSunset = date_sunset2(SUNFUNCS_RET_STRING);
$res2 = $stringSunset($time);
// "06:00"
```

### date_time_set
#### Usage
```php
use function Phantasy\Core\PHP\date_time_set;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_create1,
    date_time_set
};

$date = date_create1('2001-01-01');
$res = date_time_set(14, 55, $date);
/*
object(DateTime) {
    ["date"] => "2001-01-01 14:55:00",
    ...
}
*/

$setTo255 = date_time_set(14, 55);
$res2 = $setTo255($date);
/*
object(DateTime) {
    ["date"] => "2001-01-01 14:55:00",
    ...
}
*/
```

### date_time_set4
#### Usage
```php
use function Phantasy\Core\PHP\date_time_set4;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_create1,
    date_time_set4
};

$date = date_create1('2001-01-01');
$res = date_time_set4(14, 55, 12, $date);
/*
object(DateTime) {
    ["date"] => "2001-01-01 14:55:12",
    ...
}
*/

$setTo25512 = date_time_set4(14, 55, 12);
$res2 = $setTo255($date);
/*
object(DateTime) {
    ["date"] => "2001-01-01 14:55:12",
    ...
}
*/
```

### date_timestamp_get
#### Usage
```php
use function Phantasy\Core\PHP\date_timestamp_get;
```
#### Examples
```php
$date = date_create();
$res = date_timestamp_get($date);
// 1492998985

$getTimestamp = date_timestamp_get();
$res2 = $getTimestamp($date);
// 1492998985
```

### date_timestamp_set
#### Usage
```php
use function Phantasy\Core\PHP\date_timestamp_set;
```
#### Examples
```php
$date = date_create();
$res = date_timestamp_set(1171502725, $date);
/*
object(DateTime) {
    ["date"] => "2007-02-14 18:25:25",
    ...
}
*/

$setTimestamp = date_timestamp_set(1171502725);
$res2 = $setTimestamp($date);
/*
object(DateTime) {
    ["date"] => "2007-02-14 18:25:25",
    ...
}
*/
```

### date_timezone_get
#### Usage
```php
use function Phantasy\Core\PHP\date_timezone_get;
```
#### Examples
```php

```

### date_timezone_set
#### Usage
```php
use function Phantasy\Core\PHP\date_timezone_set;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_create2,
    date_timezone_set,
    timezone_open
};

$date = date_create2(timezone_open('Europe/London'), null);

$res = date_timezone_get($date);
/*
    ...,
    ["timezone"] => "Europe/London"
}
*/

$getTimezone = date_timezone_get();
$res2 = $getTimezone($date);
/*
    ...,
    ["timezone"] => "Europe/London"
}
*/
```

### date
#### Usage
```php
use function Phantasy\Core\PHP\date;
```
#### Examples
```php
$res = date('l');
// 'Sunday'

$date = date();
$res2 = $date('l');
// 'Sunday'
```

### date2
#### Usage
```php
use function Phantasy\Core\PHP\date2;
```
#### Examples
```php
$time = time();
$res = date2('l', $time);
// 'Sunday'

$getDay = date2('l');
$res2 = $getDay($time);
// 'Sunday'
```

### getdate1
#### Usage
```php
use function Phantasy\Core\PHP\getdate1;
```
#### Examples
```php
getdate1(time());
/*
    ["seconds"] => 32,
    ["minutes"] => 4,
    ["hours"] => 20,
    ["mday"] => 23,
    ["wday"] => 0,
    ...
*/

$getDate = getdate1();
$getDate(time());
/*
    ["seconds"] => 32,
    ["minutes"] => 4,
    ["hours"] => 20,
    ["mday"] => 23,
    ["wday"] => 0,
    ...
*/
```

### gettimeofday1
#### Usage
```php
use function Phantasy\Core\PHP\gettimeofday1;
```
#### Examples
```php
$res = gettimeofday1(true);
// float(1492999565.9816)

$getTime = gettimeofday1();
$res2 = $getTime(true);
// float(1492999565.9816)
```

### gmdate
#### Usage
```php
use function Phantasy\Core\PHP\gmdate;
```
#### Examples
```php
$format = 'Y-m-d';
$res = gmdate($format);
// '2017-04-24'

$gmdate = gmdate();
$res2 = $gmdate($format);
// '2017-04-24'
```

### gmdate2
#### Usage
```php
use function Phantasy\Core\PHP\gmdate2;
```
#### Examples
```php
$res = gmdate2('l', time());
// 'Monday'

$gmDay = gmdate2('l');
$res2 = $gmDay(time());
// 'Monday'
```

### gmstrftime
#### Usage
```php
use function Phantasy\Core\PHP\gmstrftime;
```
#### Examples
```php
$format = '%b %d %Y';

$res = gmstrftime($format);
// "Apr 12 2017"

$gmstrftime = gmstrftime();
$res2 = $gmstrftime($format);
// "Apr 12 2017"
```

### gmstrftime2
#### Usage
```php
use function Phantasy\Core\PHP\gmstrftime2;
```
#### Examples
```php
$format = '%b %d %Y';
$res = gmstrftime2($format, time());
// "Apr 12 2017"

$gmstrftime = gmstrftime2($format);
$res2 = $gmstrftime(time());
// "Apr 12 2017"
```

### idate
#### Usage
```php
use function Phantasy\Core\PHP\idate;
```
#### Examples
```php
$res = idate('U');
// 1493000021

$idate = idate();
$res2 = $idate('U');
// 1493000021
```

### idate2
#### Usage
```php
use function Phantasy\Core\PHP\idate2;
```
#### Examples
```php
$res = idate2('U', time());
// 1493000021

$unixIDate = idate2('U');
$res2 = $unixIDate(time());
// 1493000021
```

### localtime1
#### Usage
```php
use function Phantasy\Core\PHP\localtime1;
```
#### Examples
```php
$res = localtime1(time());
/*
array(9) {
    [0] => 1,
    [1] => 15,
    [2] => 20,
    [3] => 23,
    [4] => 3,
    ...
}
*/
```

### localtime2
#### Usage
```php
use function Phantasy\Core\PHP\localtime2;
```
#### Examples
```php
$res = localtime2(true, time());
/*
array(9) {
    ["tm_sec"] => 18,
    ["tm_min"] => 16,
    ["tm_hour"] => 20,
    ["tm_mday"] => 23,
    ["tm_mon"] => 3,
    ...
}
*/

$localtimeAssoc = localtime2(true);
$res2 = $localtimeAssoc(time());
/*
array(9) {
    ["tm_sec"] => 18,
    ["tm_min"] => 16,
    ["tm_hour"] => 20,
    ["tm_mday"] => 23,
    ["tm_mon"] => 3,
    ...
}
*/
```

### microtime1
#### Usage
```php
use function Phantasy\Core\PHP\microtime1;
```
#### Examples
```php
$res = microtime1(true);
// float(1493000249.088)

$microtime = microtime1();
$res2 = $microtime(true);
// float(1493000249.088)
```

### strftime
#### Usage
```php
use function Phantasy\Core\PHP\strftime;
```
#### Examples
```php
$res = strftime('%b %d %Y');
// "Apr 28 2017"

$strftime = strftime();
$res2 = $strftime('%b %d %Y');
// "Apr 28 2017"
```

### strftime2
#### Usage
```php
use function Phantasy\Core\PHP\strftime2;
```
#### Examples
```php
$res = strftime2('l', time());
// 'Sunday'

$strftimeDay = strftime2('l');
$res2 = $strftimeDay(time());
// 'Sunday'
```

### strptime
#### Usage
```php
use function Phantasy\Core\PHP\strptime;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    strftime,
    strptime
};

$format = '%d/%m/%Y %H:%M:%S';
$strf = strftime($format);

$res = strptime($format, $strf);
/*
array(9) {
    ["tm_sec"] => 13,
    ["tm_min"] => 21,
    ["tm_hour"] => 20,
    ["tm_mday"] => 23,
    ...
}
*/

$strptimeFormat = strptime($format);
$res2 = $strptimeFormat($strf);
/*
array(9) {
    ["tm_sec"] => 13,
    ["tm_min"] => 21,
    ["tm_hour"] => 20,
    ["tm_mday"] => 23,
    ...
}
*/
```

### strtotime
#### Usage
```php
use function Phantasy\Core\PHP\strtotime;
```
#### Examples
```php
$res = strtotime('+1 day');
// 1492408800

$strtotime = strtotime();
$res2 = $strtotime('+1 day');
// 1492408800
```

### strtotime2
#### Usage
```php
use function Phantasy\Core\PHP\strtotime2;
```
#### Examples
```php
$res = strtotime2(time(), '+1 day');
// 1492408800

$strtotime = strtotime2(time());
$res2 = $strtotime('-1 day');
// // 1492408800
```

### timezone_identifiers_list1
#### Usage
```php
use function Phantasy\Core\PHP\date_interval_format;
```
#### Examples
```php
$res = timezone_identifiers_list1(DateTimeZone::ALL);
/*
array(425) {
    0 => 'Africa/Abidjan',
    1 => 'Africa/Accra',
    2 => 'Africa/Addis_Ababa',
    ...
}
*/

$til = timezone_identifiers_list1();
$res2 = $til(DateTimeZone::ALL);
/*
array(425) {
    0 => 'Africa/Abidjan',
    1 => 'Africa/Accra',
    2 => 'Africa/Addis_Ababa',
    ...
}
*/
```

### timezone_identifiers_list2
#### Usage
```php
use function Phantasy\Core\PHP\timezone_identifiers_list2;
```
#### Examples
```php
$res = timezone_identifiers_list2('AF', \DateTimeZone::PER_COUNTRY);
/*
array(1) {
    0 => "Asia/Kabul"
}
*/

$tilAf = timezone_identifiers_list2('AF');
$res2 = $tilAf(DateTimeZone::PER_COUNTRY);
/*
array(1) {
    0 => "Asia/Kabul"
}
*/
```

### timezone_location_get
#### Usage
```php
use function Phantasy\Core\PHP\timezone_location_get;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    timezone_open,
    timezone_location_get
};

$res = timezone_location_get(timezone_open('Europe/Prague'));
/*
array(4) {
    ["country_code"] => "CZ",
    ["latitude"] => float(50.08333),
    ...
}
*/

$locGet = timezone_location_get();
$res2 = $locGet(timezone_open('Europe/Prague'));
/*
array(4) {
    ["country_code"] => "CZ",
    ["latitude"] => float(50.08333),
    ...
}
*/
```

### timezone_name_from_abbr
#### Usage
```php
use function Phantasy\Core\PHP\timezone_name_from_abbr;
```
#### Examples
```php
$res = timezone_name_from_abbr('CET');
// 'Europe/Berlin'

$nameFromAbbr = timezone_name_from_abbr();
$res2 = $nameFromAbbr('CET');
// 'Europe/Berlin'
```

### timezone_name_from_abbr2
#### Usage
```php
use function Phantasy\Core\PHP\timezone_name_from_abbr2;
```
#### Examples
```php
$res = timezone_name_from_abbr2(3600, 'CET');
// 'Europe/Berlin'

$nameFromAbbr = timezone_name_from_abbr2(3600);
$res2 = $nameFromAbbr('CET');
// 'Europe/Berlin'
```

### timezone_name_from_abbr3
#### Usage
```php
use function Phantasy\Core\PHP\timezone_name_from_abbr3;
```
#### Examples
```php
$res = timezone_name_from_abbr3(0, 3600, '');
// 'Europe/Paris'

$nameFromAbbr = timezone_name_from_abbr3(0, 3600);
$res2 = $nameFromAbbr('');
// 'Europe/Paris'
```

### timezone_name_get
#### Usage
```php
use function Phantasy\Core\PHP\timezone_name_get;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    timezone_name_get,
    timezone_open
};

$tz = timezone_open('Europe/Prague');
$res = timezone_name_get($tz);
// 'Europe/Prague'

$tzName = timezone_name_get();
$res2 = $tzName($tz);
// 'Europe/Prague'
```

### timezone_offset_get
#### Usage
```php
use function Phantasy\Core\PHP\timezone_offset_get;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    date_create1,
    timezone_open
};

$tz = timezone_open('Asia/Tokyo');
$time = date_create1('now');

$res = timezone_offset_get($tz, $time);
// 32400

$offset = timezone_offset_get($tz);
$res2 = $offset($time);
// 32400
```

### timezone_open
#### Usage
```php
use function Phantasy\Core\PHP\timezone_open;
```
#### Examples
```php
$tz = timezone_open('Asia/Tokyo');
/*
object(DateTimeZone) {
    ...,
    ["timezone"] => "Asia/Tokyo"
}
*/

$tzOpen = timezone_open();
$tz = $tzOpen('Asia/Tokyo');
/*
object(DateTimeZone) {
    ...,
    ["timezone"] => "Asia/Tokyo"
}
*/
```

### timezone_transitions_get
#### Usage
```php
use function Phantasy\Core\PHP\timezone_transitions_get;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    timezone_transitions_get,
    timezone_open
};

$tz = timezone_open('Europe/London');
$res = timezone_transitions_get($tz);
/*
array(243) {
    ...,
    242 => array(5) {
        'ts' => 2121901200,
        'time' => '2037-03-29T01:00:00',
        'offset' => 3600,
        'isdst' => true,
        'abbr' => 'BST'
    },
    243 => array(5) {
        'ts' => 2140045200,
        'time' => '2037-10-25T01:00:00',
        'offset' => 0,
        'isdst' => false,
        'abbr' => 'GMT'
    }
}
*/

$ttg = timezone_transitions_get();
$res2 = $ttg($tz);
/*
array(243) {
    ...,
    242 => array(5) {
        'ts' => 2121901200,
        'time' => '2037-03-29T01:00:00',
        'offset' => 3600,
        'isdst' => true,
        'abbr' => 'BST'
    },
    243 => array(5) {
        'ts' => 2140045200,
        'time' => '2037-10-25T01:00:00',
        'offset' => 0,
        'isdst' => false,
        'abbr' => 'GMT'
    }
}
*/
```

### timezone_transitions_get2
#### Usage
```php
use function Phantasy\Core\PHP\timezone_transitions_get2;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    timezone_open,
    strtotime,
    timezone_transitions_get2
};

$start = strtotime('-2 weeks');
$tz = timezone_open('Europe/London');

$res = timezone_transitions_get2($start, $tz);
/*
array(42) {
    ...,
    40 => array(5) {
        ["ts"] => 2121901200,
        ["time"] => "2037-03-29T01:00:00",
        ["offset"] => 3600,
        ["isdst"] => true,
        ["abbr"] => "BST"
    },
    41 => array(5) {
        ["ts"] => 2140045200,
        ["time"] => "2037-10-25T01:00:00",
        ["offset"] => 0,
        ["isdst"] => false,
        ["abbr"] => "GMT"
    }
}
*/

$ttg = timezone_transitions_get2($start);
$res2 = $ttg($tz);
/*
array(42) {
    ...,
    40 => array(5) {
        ["ts"] => 2121901200,
        ["time"] => "2037-03-29T01:00:00",
        ["offset"] => 3600,
        ["isdst"] => true,
        ["abbr"] => "BST"
    },
    41 => array(5) {
        ["ts"] => 2140045200,
        ["time"] => "2037-10-25T01:00:00",
        ["offset"] => 0,
        ["isdst"] => false,
        ["abbr"] => "GMT"
    }
}
*/
```

### timezone_transitions_get3
#### Usage
```php
use function Phantasy\Core\PHP\timezone_transitions_get_3;
```
#### Examples
```php
use function Phantasy\Core\PHP\{
    timezone_open,
    strtotime,
    timezone_transitions_get3
};

$start = strtotime('-2 weeks');
$end = strtotime('-1 week');
$tz = timezone_open('Europe/London');

$res = timezone_transitions_get3($start, $end, $tz);
/*
array(42) {
    ...,
    40 => array(5) {
        ["ts"] => 2121901200,
        ["time"] => "2037-03-29T01:00:00",
        ["offset"] => 3600,
        ["isdst"] => true,
        ["abbr"] => "BST"
    },
    41 => array(5) {
        ["ts"] => 2140045200,
        ["time"] => "2037-10-25T01:00:00",
        ["offset"] => 0,
        ["isdst"] => false,
        ["abbr"] => "GMT"
    }
}
*/

$ttg = timezone_transitions_get3($start, $end);
$res2 = $ttg($tz);
/*
array(42) {
    ...,
    40 => array(5) {
        ["ts"] => 2121901200,
        ["time"] => "2037-03-29T01:00:00",
        ["offset"] => 3600,
        ["isdst"] => true,
        ["abbr"] => "BST"
    },
    41 => array(5) {
        ["ts"] => 2140045200,
        ["time"] => "2037-10-25T01:00:00",
        ["offset"] => 0,
        ["isdst"] => false,
        ["abbr"] => "GMT"
    }
}
*/
```

## JSON Functions
### json_encode
#### Usage
```php
use function Phantasy\Core\PHP\json_encode;
```
#### Examples
```php
$arr = ['a' => 1, 'b' => 2, 'c' => 3];
json_encode($arr);
// '{"a":1,"b":2,"c":3}';

$jsonEncode = json_encode();
$jsonEncode($val);
// '{"a":1,"b":2,"c":3}';
```

### json_encode2
#### Usage
```php
use function Phantasy\Core\PHP\json_encode2;
```
#### Examples
```php
$arr = ['a' => '1', 'b' => '2', 'c' => '3'];
json_encode2(JSON_NUMERIC_CHECK, $arr);
// '{"a":1,"b":2,"c":3}';

$jsonEncodeNumCheck = json_encode2(JSON_NUMERIC_CHECK);
$jsonEncodeNumCheck($val);
// '{"a":1,"b":2,"c":3}';
```

### json_decode
#### Usage
```php
use function Phantasy\Core\PHP\json_decode;
```
#### Examples
```php
$json = '{"a":1,"b":2,"c":3}';
json_decode($json);
/*
object(stdClass)#1 (3) {
    ["a"] => int(1)
    ["b"] => int(2)
    ["c"] => int(3)
}
*/

$jsonDecode = json_decode();
$jsonDecode($json);
/*
object(stdClass)#1 (3) {
    ["a"] => int(1)
    ["b"] => int(2)
    ["c"] => int(3)
}
*/
```

### json_decode2
#### Usage
```php
use function Phantasy\Core\PHP\json_decode2;
```
#### Examples
```php
$json = '{"a":1,"b":2,"c":3}';
json_decode2(true, $json);
// ['a' => 3, 'b' => 2, 'c' => 3];

$jsonDecodeAssoc = json_decode(true);
$jsonDecodeAssoc($json);
// ['a' => 3, 'b' => 2, 'c' => 3];
```
