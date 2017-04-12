<?php
namespace Phantasy\Core\PHP;

use function Phantasy\Core\curry;

function explode()
{
    return curry('\explode')(...func_get_args());
}

function implode()
{
    return curry('\implode')(...func_get_args());
}

function addcslashes()
{
    return curry(function ($charlist, $str) {
        return \addcslashes($str, $charlist);
    })(...func_get_args());
}

function addslashes()
{
    return curry('\addslashes')(...func_get_args());
}

function bin2hex()
{
    return curry('\bin2hex')(...func_get_args());
}

function chop()
{
    return curry('\chop')(...func_get_args());
}

function chop2()
{
    return curry(function ($charMask, $str) {
        return \chop($str, $charMask);
    })(...func_get_args());
}

function chr()
{
    return curry('\chr')(...func_get_args());
}

function chunk_split()
{
    return curry('\chunk_split')(...func_get_args());
}

function chunk_split2()
{
    return curry(function ($chunklen, $body) {
        return \chunk_split($body, $chunklen);
    })(...func_get_args());
}

function chunk_split3()
{
    return curry(function ($chunklen, $end, $body) {
        return \chunk_split($body, $chunklen, $end);
    })(...func_get_args());
}

function convert_cyr_string()
{
    return curry(function ($from, $to, $str) {
        return \convert_cyr_string($str, $from, $to);
    })(...func_get_args());
}

function convert_uudecode()
{
    return curry('\convert_uudecode')(...func_get_args());
}

function convert_uuencode()
{
    return curry('\convert_uuencode')(...func_get_args());
}

function count_chars()
{
    return curry('\count_chars')(...func_get_args());
}

function count_chars2()
{
    return curry(function ($mode, $string) {
        return \count_chars($string, $mode);
    })(...func_get_args());
}

function crc32()
{
    return curry('\crc32')(...func_get_args());
}

function crypt()
{
    return curry(function ($salt, $str) {
        return \crypt($str, $salt);
    })(...func_get_args());
}

function hex2bin()
{
    return curry('\hex2bin')(...func_get_args());
}

function htmlspecialchars_decode()
{
    return curry('\htmlspecialchars_decode')(...func_get_args());
}

function htmlspecialchars_decode2()
{
    return curry(function ($flags, $str) {
        return \htmlspecialchars_decode($str, $flags);
    })(...func_get_args());
}

function join()
{
    return curry('\join')(...func_get_args());
}

function lcfirst()
{
    return curry('\lcfirst')(...func_get_args());
}

function levenshtein()
{
    return curry('\levenshtein')(...func_get_args());
}

function levenshtein5()
{
    return curry(function ($cost_ins, $cost_rep, $cost_del, $str1, $str2) {
        return \levenshtein($str1, $str2, $cost_ins, $cost_rep, $cost_del);
    })(...func_get_args());
}

function ltrim()
{
    return curry('\ltrim')(...func_get_args());
}

function ltrim2()
{
    return curry(function ($charMask, $str) {
        return \ltrim($str, $charMask);
    })(...func_get_args());
}

function md5_file()
{
    return curry('\md5_file')(...func_get_args());
}

function md5_file2()
{
    return curry(function ($rawOutput, $filename) {
        return \md5_file($filename, $rawOutput);
    })(...func_get_args());
}

function md5()
{
    return curry('\md5')(...func_get_args());
}

function md52()
{
    return curry(function ($rawOutput, $str) {
        return \md5($str, $rawOutput);
    })(...func_get_args());
}

function metaphone()
{
    return curry('\metaphone')(...func_get_args());
}

function metaphone2()
{
    return curry(function ($phonemes, $str) {
        return \metaphone($str, $phonemes);
    })(...func_get_args());
}

function money_format()
{
    return curry('\money_format')(...func_get_args());
}

function nl_langinfo()
{
    return curry('\nl_langinfo')(...func_get_args());
}

function nl2br()
{
    return curry('\nl2br')(...func_get_args());
}

function nl2br2()
{
    return curry(function ($isXHTML, $str) {
        return \nl2br($str, $isXHTML);
    })(...func_get_args());
}

function number_format()
{
    return curry('\number_format')(...func_get_args());
}

function number_format2()
{
    return curry(function ($decimals, $num) {
        return \number_format($num, $decimals);
    })(...func_get_args());
}

function number_format4()
{
    return curry(function ($decimals, $decPoint, $thousandsSep, $num) {
        return \number_format($num, $decimals, $decPoint, $thousandsSep);
    })(...func_get_args());
}

function ord()
{
    return curry('\ord')(...func_get_args());
}

function parse_str()
{
    return curry(function ($str) {
        \parse_str($str, $output);
        return $output;
    })(...func_get_args());
}

function quotemeta()
{
    return curry('\quotemeta')(...func_get_args());
}

function rtrim()
{
    return curry('\rtrim')(...func_get_args());
}

function rtrim2()
{
    return curry(function ($charMask, $str) {
        return \rtrim($str, $charMask);
    })(...func_get_args());
}

function sha1_file()
{
    return curry('\sha1_file')(...func_get_args());
}

function sha1_file2()
{
    return curry(function ($rawOutput, $filename) {
        return \sha1_file($filename, $rawOutput);
    })(...func_get_args());
}

function sha1()
{
    return curry('\sha1')(...func_get_args());
}

function sha12()
{
    return curry(function ($rawOutput, $str) {
        return \sha1($str, $rawOutput);
    })(...func_get_args());
}

function similar_text()
{
    return curry('\similar_text')(...func_get_args());
}

function similar_text_pct()
{
    return curry(function ($str1, $str2) {
        \similar_text($str1, $str2, $percent);
        return $percent;
    })(...func_get_args());
}

function soundex()
{
    return curry('\soundex')(...func_get_args());
}

function str_getcsv()
{
    return curry('\str_getcsv')(...func_get_args());
}

function str_getcsv2()
{
    return curry(function ($delim, $str) {
        return \str_getcsv($str, $delim);
    })(...func_get_args());
}

function str_getcsv3()
{
    return curry(function ($delim, $enclosure, $str) {
        return \str_getcsv($str, $delim, $enclosure);
    })(...func_get_args());
}

function str_getcsv4()
{
    return curry(function ($delim, $enclosure, $escape, $str) {
        return \str_getcsv($str, $delim, $enclosure, $escape);
    })(...func_get_args());
}

function str_ireplace()
{
    return curry('\str_ireplace')(...func_get_args());
}

function str_ireplace_count()
{
    return curry(function ($search, $replace, $subject) {
        \str_ireplace($search, $replace, $subject, $count);
        return $count;
    })(...func_get_args());
}

function str_pad()
{
    return curry(function ($length, $str) {
        return \str_pad($str, $length);
    })(...func_get_args());
}

function str_pad3()
{
    return curry(function ($length, $padStr, $str) {
        return \str_pad($str, $length, $padStr);
    })(...func_get_args());
}

function str_pad4()
{
    return curry(function ($length, $padStr, $padType, $str) {
        return \str_pad($str, $length, $padStr, $padType);
    })(...func_get_args());
}

function str_repeat()
{
    return curry(function ($multiplier, $str) {
        return \str_repeat($str, $multiplier);
    })(...func_get_args());
}

function str_replace()
{
    return curry('\str_replace')(...func_get_args());
}

function str_replace_count()
{
    return curry(function ($search, $replace, $subject) {
        \str_replace($search, $replace, $subject, $count);
        return $count;
    })(...func_get_args());
}

function str_rot13()
{
    return curry('\str_rot13')(...func_get_args());
}

function str_shuffle()
{
    return curry('\str_shuffle')(...func_get_args());
}

function str_split()
{
    return curry('\str_split')(...func_get_args());
}

function str_split2()
{
    return curry(function ($len, $str) {
        return \str_split($str, $len);
    })(...func_get_args());
}

function str_word_count()
{
    return curry('\str_word_count')(...func_get_args());
}

function str_word_count2()
{
    return curry(function ($format, $str) {
        return \str_word_count($str, $format);
    })(...func_get_args());
}

function str_word_count3()
{
    return curry(function ($format, $charList, $str) {
        return \str_word_count($str, $format, $charList);
    })(...func_get_args());
}

function strcasecmp()
{
    return curry('\strcasecmp')(...func_get_args());
}

function strchr()
{
    return curry(function ($needle, $haystack) {
        return \strchr($haystack, $needle);
    })(...func_get_args());
}

function strchr3()
{
    return curry(function ($beforeNeedle, $needle, $haystack) {
        return \strchr($haystack, $needle, $beforeNeedle);
    })(...func_get_args());
}

function strcmp()
{
    return curry('\strcmp')(...func_get_args());
}

function strcoll()
{
    return curry('\strcoll')(...func_get_args());
}

function strcspn()
{
    return curry(function ($mask, $str) {
        return \strcspn($str, $mask);
    })(...func_get_args());
}

function strcspn3()
{
    return curry(function ($start, $mask, $str) {
        return \strcspn($str, $mask, $start);
    })(...func_get_args());
}

function strcspn4()
{
    return curry(function ($start, $end, $mask, $str) {
        return \strcspn($str, $mask, $start, $end);
    })(...func_get_args());
}

function strip_tags()
{
    return curry('\strip_tags')(...func_get_args());
}

function strip_tags2()
{
    return curry(function ($allowableTags, $str) {
        return \strip_tags($str, $allowableTags);
    })(...func_get_args());
}

function stripcslashes()
{
    return curry('\stripcslashes')(...func_get_args());
}

function stripos()
{
    return curry(function ($needle, $haystack) {
        return \stripos($haystack, $needle);
    })(...func_get_args());
}

function stripos3()
{
    return curry(function ($offset, $needle, $haystack) {
        return \stripos($haystack, $needle, $offset);
    })(...func_get_args());
}

function stripslashes()
{
    return curry('\stripslashes')(...func_get_args());
}

function stristr()
{
    return curry(function ($needle, $haystack) {
        return \stristr($haystack, $needle);
    })(...func_get_args());
}

function stristr3()
{
    return curry(function ($beforeNeedle, $needle, $haystack) {
        return \stristr($haystack, $needle, $beforeNeedle);
    })(...func_get_args());
}

function strlen()
{
    return curry('\strlen')(...func_get_args());
}

function strnatcasecmp()
{
    return curry('\strnatcasecmp')(...func_get_args());
}

function strnatcmp()
{
    return curry('\strnatcasecmp')(...func_get_args());
}

function strncasecmp()
{
    return curry(function ($n, $a, $b) {
        return \strncasecmp($a, $b, $n);
    })(...func_get_args());
}

function strncmp()
{
    return curry(function ($n, $a, $b) {
        return \strncmp($a, $b, $n);
    })(...func_get_args());
}

function strpbrk()
{
    return curry(function ($charList, $haystack) {
        return \strpbrk($haystack, $charList);
    })(...func_get_args());
}

function strpos()
{
    return curry(function ($needle, $haystack) {
        return \strpos($haystack, $needle);
    })(...func_get_args());
}

function strpos3()
{
    return curry(function ($offset, $needle, $haystack) {
        return \strpos($haystack, $needle, $offset);
    })(...func_get_args());
}

function strrchr()
{
    return curry(function ($needle, $haystack) {
        return \strrchr($haystack, $needle);
    })(...func_get_args());
}

function strrev()
{
    return curry('\strrev')(...func_get_args());
}

function strripos()
{
    return curry(function ($needle, $haystack) {
        return \strripos($haystack, $needle);
    })(...func_get_args());
}

function strripos3()
{
    return curry(function ($offset, $needle, $haystack) {
        return \strripos($haystack, $needle, $offset);
    })(...func_get_args());
}

function strrpos()
{
    return curry(function ($needle, $haystack) {
        return \strrpos($haystack, $needle);
    })(...func_get_args());
}

function strrpos3()
{
    return curry(function ($offset, $needle, $haystack) {
        return \strrpos($haystack, $needle, $offset);
    })(...func_get_args());
}

function strspn()
{
    return curry(function ($mask, $subject) {
        return \strspn($subject, $mask);
    })(...func_get_args());
}

function strspn3()
{
    return curry(function ($start, $mask, $subject) {
        return \strspn($subject, $mask, $start);
    })(...func_get_args());
}

function strspn4()
{
    return curry(function ($start, $length, $mask, $subject) {
        return \strspn($subject, $mask, $start, $length);
    })(...func_get_args());
}

function strstr()
{
    return curry(function ($needle, $haystack) {
        return \strstr($haystack, $needle);
    })(...func_get_args());
}

function strstr3()
{
    return curry(function ($beforeNeedle, $needle, $haystack) {
        return \strstr($haystack, $needle, $beforeNeedle);
    })(...func_get_args());
}

function strtok()
{
    return curry(function ($token, $str) {
        return \strtok($str, $token);
    })(...func_get_args());
}

function strtok1()
{
    return curry(function ($token) {
        return \strtok($token);
    })(...func_get_args());
}

function strtolower()
{
    return curry('\strtolower')(...func_get_args());
}

function strtoupper()
{
    return curry('\strtoupper')(...func_get_args());
}

function strtr()
{
    $f = function () use (&$f) {
        $args = func_get_args();
        if (count($args) > 0) {
            if (is_array($args[0])) {
                return curry(function ($replacePairs, $str) {
                    return \strtr($str, $replacePairs);
                })(...$args);
            } elseif (is_string($args[0])) {
                return curry(function ($from, $to, $str) {
                    return \strtr($str, $from, $to);
                })(...$args);
            }
        }

        return $f;
    };

    return $f(...func_get_args());
}

function substr_compare()
{
    return curry(function ($offset, $str, $mainStr) {
        return \substr_compare($mainStr, $str, $offset);
    })(...func_get_args());
}

function substr_compare4()
{
    return curry(function ($length, $offset, $str, $mainStr) {
        return \substr_compare($mainStr, $str, $offset, $length);
    })(...func_get_args());
}

function substr_compare5()
{
    return curry(function ($caseInsensitive, $length, $offset, $str, $mainStr) {
        return \substr_compare($mainStr, $str, $offset, $length, $caseInsensitive);
    })(...func_get_args());
}

function substr_count()
{
    return curry(function ($needle, $haystack) {
        return \substr_count($haystack, $needle);
    })(...func_get_args());
}

function substr_count3()
{
    return curry(function ($offset, $needle, $haystack) {
        return \substr_count($haystack, $needle, $offset);
    })(...func_get_args());
}

function substr_count4()
{
    return curry(function ($length, $offset, $needle, $haystack) {
        return \substr_count($haystack, $needle, $offset, $length);
    })(...func_get_args());
}

function substr_replace()
{
    return curry(function ($start, $replacement, $str) {
        return \substr_replace($str, $replacement, $start);
    })(...func_get_args());
}

function substr_replace4()
{
    return curry(function ($length, $start, $replacement, $str) {
        return \substr_replace($str, $replacement, $start, $length);
    })(...func_get_args());
}

function substr()
{
    return curry(function ($start, $str) {
        return \substr($str, $start);
    })(...func_get_args());
}

function substr3()
{
    return curry(function ($length, $start, $str) {
        return \substr($str, $start, $length);
    })(...func_get_args());
}

function trim()
{
    return curry('\trim')(...func_get_args());
}

function trim2()
{
    return curry(function ($charMask, $str) {
        return \trim($str, $charMask);
    })(...func_get_args());
}

function ucfirst()
{
    return curry('\ucfirst')(...func_get_args());
}

function ucwords()
{
    return curry('\ucwords')(...func_get_args());
}

function ucwords2()
{
    return curry(function ($delims, $str) {
        return \ucwords($str, $delims);
    })(...func_get_args());
}

function wordwrap()
{
    return curry('\wordwrap')(...func_get_args());
}

function wordwrap2()
{
    return curry(function ($width, $str) {
        return \wordwrap($str, $width);
    })(...func_get_args());
}

function wordwrap3()
{
    return curry(function ($break, $width, $str) {
        return \wordwrap($str, $width, $break);
    })(...func_get_args());
}

function wordwrap4()
{
    return curry(function ($cut, $break, $width, $str) {
        return \wordwrap($str, $width, $break, $cut);
    })(...func_get_args());
}
