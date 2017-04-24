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
    return curry(function ($padType, $length, $padStr, $str) {
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

function array_change_key_case()
{
    return curry('\array_change_key_case')(...func_get_args());
}

function array_change_key_case2()
{
    return curry(function ($case, $arr) {
        return \array_change_key_case($arr, $case);
    })(...func_get_args());
}

function array_chunk()
{
    return curry(function ($size, $arr) {
        return \array_chunk($arr, $size);
    })(...func_get_args());
}

function array_chunk3()
{
    return curry(function ($preserveKeys, $size, $arr) {
        return \array_chunk($arr, $size, $preserveKeys);
    })(...func_get_args());
}

function array_column()
{
    return curry(function ($column_key, $input) {
        return \array_column($input, $column_key);
    })(...func_get_args());
}

function array_column3()
{
    return curry(function ($index_key, $column_key, $input) {
        return \array_column($input, $column_key, $index_key);
    })(...func_get_args());
}

function array_combine()
{
    return curry('\array_combine')(...func_get_args());
}

function array_count_values()
{
    return curry('\array_count_values')(...func_get_args());
}

function array_diff_assoc()
{
    return curry('\array_diff_assoc')(...func_get_args());
}

function array_diff_key()
{
    return curry('\array_diff_key')(...func_get_args());
}

function array_diff_uassoc()
{
    return curry(function ($f, $a, $b) {
        return \array_diff_uassoc($a, $b, $f);
    })(...func_get_args());
}

function array_diff_ukey()
{
    return curry(function ($f, $a, $b) {
        return \array_diff_ukey($a, $b, $f);
    })(...func_get_args());
}

function array_diff()
{
    return curry('\array_diff')(...func_get_args());
}

function array_fill_keys()
{
    return curry(function ($val, $arr) {
        return \array_fill_keys($arr, $val);
    })(...func_get_args());
}

function array_fill()
{
    return curry(function ($num, $startIndex, $value) {
        return \array_fill($startIndex, $num, $value);
    })(...func_get_args());
    return curry('\array_fill')(...func_get_args());
}

function array_filter()
{
    return curry('\array_filter')(...func_get_args());
}

function array_filter2()
{
    return curry(function ($f, $a) {
        return \array_filter($a, $f);
    })(...func_get_args());
}

function array_filter3()
{
    return curry(function ($flag, $f, $a) {
        return \array_filter($a, $f, $flag);
    })(...func_get_args());
}

function array_flip()
{
    return curry('\array_flip')(...func_get_args());
}

function array_intersect_assoc()
{
    return curry('\array_intersect_assoc')(...func_get_args());
}

function array_intersect_key()
{
    return curry('\array_intersect_key')(...func_get_args());
}

function array_intersect_uassoc()
{
    return curry(function ($f, $a, $b) {
        return \array_intersect_uassoc($a, $b, $f);
    })(...func_get_args());
}

function array_intersect_ukey()
{
    return curry(function ($f, $a, $b) {
        return \array_intersect_ukey($a, $b, $f);
    })(...func_get_args());
}

function array_intersect()
{
    return curry('\array_intersect')(...func_get_args());
}

function array_key_exists()
{
    return curry('\array_key_exists')(...func_get_args());
}

function key_exists()
{
    return curry('\key_exists')(...func_get_args());
}

function array_keys()
{
    return curry('\array_keys')(...func_get_args());
}

function array_keys2()
{
    return curry(function ($searchVal, $arr) {
        return \array_keys($arr, $searchVal);
    })(...func_get_args());
}

function array_keys3()
{
    return curry(function ($strict, $searchVal, $arr) {
        return \array_keys($arr, $searchVal, $strict);
    })(...func_get_args());
}

function array_map()
{
    return curry('\array_map')(...func_get_args());
}

function array_merge_recursive()
{
    return curry(function ($a, $b) {
        return \array_merge_recursive($a, $b);
    })(...func_get_args());
}

function array_merge()
{
    return curry(function ($a, $b) {
        return \array_merge($a, $b);
    })(...func_get_args());
}

function array_pad()
{
    return curry(function ($size, $value, $arr) {
        return \array_pad($arr, $size, $value);
    })(...func_get_args());
}

function array_product()
{
    return curry('\array_product')(...func_get_args());
}

function array_rand()
{
    return curry('\array_rand')(...func_get_args());
}

function array_rand2()
{
    return curry(function ($num, $arr) {
        return \array_rand($arr, $num);
    })(...func_get_args());
}

function array_reduce()
{
    return curry(function ($f, $i, $x) {
        return \array_reduce($x, $f, $i);
    })(...func_get_args());
}

function array_replace_recursive()
{
    return curry(function ($replacements, $base) {
        return \array_replace_recursive($base, $replacements);
    })(...func_get_args());
}

function array_replace()
{
    return curry(function ($replacements, $base) {
        return \array_replace($base, $replacements);
    })(...func_get_args());
}

function array_reverse()
{
    return curry('\array_reverse')(...func_get_args());
}

function array_reverse2()
{
    return curry(function ($preserveKeys, $arr) {
        return \array_reverse($arr, $preserveKeys);
    })(...func_get_args());
}

function array_search()
{
    return curry('\array_search')(...func_get_args());
}

function array_search3()
{
    return curry(function ($strict, $needle, $haystack) {
        return \array_search($needle, $haystack, $strict);
    })(...func_get_args());
}

function array_slice()
{
    return curry(function ($offset, $arr) {
        return \array_slice($arr, $offset);
    })(...func_get_args());
}

function array_slice3()
{
    return curry(function ($length, $offset, $arr) {
        return \array_slice($arr, $offset, $length);
    })(...func_get_args());
}

function array_slice4()
{
    return curry(function ($preserveKeys, $length, $offset, $arr) {
        return \array_slice($arr, $offset, $length, $preserveKeys);
    })(...func_get_args());
}

function array_sum()
{
    return curry('\array_sum')(...func_get_args());
}

function array_udiff_assoc()
{
    return curry(function ($f, $a1, $a2) {
        return \array_udiff_assoc($a1, $a2, $f);
    })(...func_get_args());
}

function array_udiff_uassoc()
{
    return curry(function ($f, $g, $a1, $a2) {
        return \array_udiff_uassoc($a1, $a2, $f, $g);
    })(...func_get_args());
}

function array_udiff()
{
    return curry(function ($f, $a1, $a2) {
        return \array_udiff($a1, $a2, $f);
    })(...func_get_args());
}

function array_uintersect_assoc()
{
    return curry(function ($f, $a1, $a2) {
        return \array_uintersect_assoc($a1, $a2, $f);
    })(...func_get_args());
}

function array_uintersect_uassoc()
{
    return curry(function ($f, $g, $a1, $a2) {
        return \array_uintersect_uassoc($a1, $a2, $f, $g);
    })(...func_get_args());
}

function array_uintersect()
{
    return curry(function ($f, $a1, $a2) {
        return \array_uintersect($a1, $a2, $f);
    })(...func_get_args());
}

function array_unique()
{
    return curry('\array_unique')(...func_get_args());
}

function array_unique2()
{
    return curry(function ($sortFlags, $arr) {
        return \array_unique($arr, $sortFlags);
    })(...func_get_args());
}

function count()
{
    return curry('\count')(...func_get_args());
}

function count2()
{
    return curry(function ($mode, $arr) {
        return \count($arr, $mode);
    })(...func_get_args());
}

function sizeof()
{
    return curry('\sizeof')(...func_get_args());
}

function sizeof2()
{
    return curry(function ($mode, $arr) {
        return \sizeof($arr, $mode);
    })(...func_get_args());
}

function in_array()
{
    return curry('\in_array')(...func_get_args());
}

function in_array3()
{
    return curry(function ($strict, $needle, $haystack) {
        return \in_array($needle, $haystack, $strict);
    })(...func_get_args());
}

function range()
{
    return curry('\range')(...func_get_args());
}

function range3()
{
    return curry(function ($step, $start, $end) {
        return \range($start, $end, $step);
    })(...func_get_args());
}

function shuffle()
{
    return curry(function ($arr) {
        \shuffle($arr);
        return $arr;
    })(...func_get_args());
}

function rsort()
{
    return curry(function ($arr) {
        \rsort($arr);
        return $arr;
    })(...func_get_args());
}

function rsort2()
{
    return curry(function ($sortFlags, $arr) {
        \rsort($arr, $sortFlags);
        return $arr;
    })(...func_get_args());
}

function krsort()
{
    return curry(function ($arr) {
        \krsort($arr);
        return $arr;
    })(...func_get_args());
}

function krsort2()
{
    return curry(function ($sortFlags, $arr) {
        \krsort($arr, $sortFlags);
        return $arr;
    })(...func_get_args());
}

function ksort()
{
    return curry(function ($arr) {
        \ksort($arr);
        return $arr;
    })(...func_get_args());
}

function ksort2()
{
    return curry(function ($sortFlags, $arr) {
        \ksort($arr, $sortFlags);
        return $arr;
    })(...func_get_args());
}

function natcasesort()
{
    return curry(function ($arr) {
        \natcasesort($arr);
        return $arr;
    })(...func_get_args());
}

function natsort()
{
    return curry(function ($arr) {
        \natsort($arr);
        return $arr;
    })(...func_get_args());
}

function arsort()
{
    return curry(function ($arr) {
        \arsort($arr);
        return $arr;
    })(...func_get_args());
}

function arsort2()
{
    return curry(function ($sortFlags, $arr) {
        \arsort($arr, $sortFlags);
        return $arr;
    })(...func_get_args());
}

function asort()
{
    return curry(function ($arr) {
        \asort($arr);
        return $arr;
    })(...func_get_args());
}

function asort2()
{
    return curry(function ($sortFlags, $arr) {
        \asort($arr, $sortFlags);
        return $arr;
    })(...func_get_args());
}

function sort()
{
    return curry(function ($arr) {
        \sort($arr);
        return $arr;
    })(...func_get_args());
}

function sort2()
{
    return curry(function ($sortFlags, $arr) {
        \sort($arr, $sortFlags);
        return $arr;
    })(...func_get_args());
}

function uasort()
{
    return curry(function ($f, $arr) {
        \uasort($arr, $f);
        return $arr;
    })(...func_get_args());
}

function uksort()
{
    return curry(function ($f, $arr) {
        \uksort($arr, $f);
        return $arr;
    })(...func_get_args());
}

function usort()
{
    return curry(function ($f, $arr) {
        \usort($arr, $f);
        return $arr;
    })(...func_get_args());
}

function array_push()
{
    return curry(function ($value, $arr) {
        return \array_merge($arr, [$value]);
    })(...func_get_args());
}

function array_pop()
{
    return curry(function ($arr) {
        return \array_pop($arr);
    })(...func_get_args());
}

function array_shift()
{
    return curry(function ($arr) {
        return \array_shift($arr);
    })(...func_get_args());
}

function array_unshift()
{
    return curry(function ($elem, $arr) {
        \array_unshift($arr, $elem);
        return $arr;
    })(...func_get_args());
}

function array_splice()
{
    return curry(function ($offset, $input) {
        \array_splice($input, $offset);
        return $input;
    })(...func_get_args());
}

function array_splice3()
{
    return curry(function ($offset, $length, $input) {
        \array_splice($input, $offset, $length);
        return $input;
    })(...func_get_args());
}

function array_splice4()
{
    return curry(function ($offset, $length, $replacement, $input) {
        \array_splice($input, $offset, $length, $replacement);
        return $input;
    })(...func_get_args());
}

function checkdate()
{
    return curry('\checkdate')(...func_get_args());
}

function date_create_from_format()
{
    return curry('\date_create_from_format')(...func_get_args());
}

function date_create_from_format3()
{
    return curry(function (\DateTimeZone $timezone, $format, $time) {
        return \date_create_from_format($format, $time, $timezone);
    })(...func_get_args());
}

function date_create_immutable_from_format()
{
    return curry('\date_create_immutable_from_format')(...func_get_args());
}

function date_create_immutable_from_format3()
{
    return curry(function (\DateTimeZone $timezone, $format, $time) {
        return \date_create_immutable_from_format($format, $time, $timezone);
    })(...func_get_args());
}

function date_create_immutable1()
{
    return curry(function ($time) {
        return \date_create_immutable($time);
    })(...func_get_args());
}

function date_create_immutable2()
{
    return curry(function (\DateTimeZone $timezone, $time) {
        return \date_create_immutable($time, $timezone);
    })(...func_get_args());
}

function date_create1()
{
    return curry(function ($time) {
        return \date_create($time);
    })(...func_get_args());
}

function date_create2()
{
    return curry(function (\DateTimeZone $timezone, $time) {
        return \date_create($time, $timezone);
    })(...func_get_args());
}

function date_add()
{
    return curry(function ($interval, $date) {
        return \date_add(clone $date, $interval);
    })(...func_get_args());
}

function date_date_set()
{
    return curry(function ($year, $month, $day, \DateTime $object) {
        return \date_date_set(clone $object, $year, $month, $day);
    })(...func_get_args());
}

function date_default_timezone_set()
{
    return curry('\date_default_timezone_set')(...func_get_args());
}

function date_diff()
{
    return curry('\date_diff')(...func_get_args());
}

function date_diff3()
{
    return curry(function ($absolute, \DateTimeInterface $d1, \DateTimeInterface $d2) {
        return \date_diff($d1, $d2, $absolute);
    })(...func_get_args());
}

function date_format()
{
    return curry(function ($format, \DateTimeInterface $object) {
        return \date_format($object, $format);
    })(...func_get_args());
}

function date_interval_create_from_date_string()
{
    return curry('\date_interval_create_from_date_string')(...func_get_args());
}

function date_interval_format()
{
    return curry(function ($format, \DateInterval $obj) {
        return \date_interval_format($obj, $format);
    })(...func_get_args());
}

function date_isodate_set()
{
    return curry(function ($year, $week, \DateTime $object) {
        return \date_isodate_set(clone $object, $year, $week);
    })(...func_get_args());
}

function date_isodate_set4()
{
    return curry(function ($year, $week, $day, \DateTime $object) {
        return \date_isodate_set(clone $object, $year, $week, $day);
    })(...func_get_args());
}

function date_modify()
{
    return curry(function ($modify, \DateTime $object) {
        return \date_modify(clone $object, $modify);
    })(...func_get_args());
}

function date_offset_get()
{
    return curry('\date_offset_get')(...func_get_args());
}

function date_parse_from_format()
{
    return curry('\date_parse_from_format')(...func_get_args());
}

function date_parse()
{
    return curry('\date_parse')(...func_get_args());
}

function date_sub()
{
    return curry(function (\DateInterval $interval, \DateTime $object) {
        return \date_sub(clone $object, $interval);
    })(...func_get_args());
}

function date_sun_info()
{
    return curry(function ($latitude, $longitude, $time) {
        return \date_sun_info($time, $latitude, $longitude);
    })(...func_get_args());
}

function date_sunrise()
{
    return curry('\date_sunrise')(...func_get_args());
}

function date_sunrise2()
{
    return curry(function ($format, $timestamp) {
        return \date_sunrise($timestamp, $format);
    })(...func_get_args());
}

function date_sunset()
{
    return curry('\date_sunset')(...func_get_args());
}

function date_sunset2()
{
    return curry(function ($format, $timestamp) {
        return \date_sunset($timestamp, $format);
    })(...func_get_args());
}

function date_time_set()
{
    return curry(function ($hour, $minute, $object) {
        return \date_time_set(clone $object, $hour, $minute);
    })(...func_get_args());
}

function date_time_set4()
{
    return curry(function ($hour, $minute, $second, $object) {
        return \date_time_set(clone $object, $hour, $minute, $second);
    })(...func_get_args());
}

function date_timestamp_get()
{
    return curry('\date_timestamp_get')(...func_get_args());
}

function date_timestamp_set()
{
    return curry(function ($unix, \DateTime $object) {
        return \date_timestamp_set(clone $object, $unix);
    })(...func_get_args());
}

function date_timezone_get()
{
    return curry('\date_timezone_get')(...func_get_args());
}

function date_timezone_set()
{
    return curry(function (\DateTimeZone $time, \DateTime $object) {
        return \date_timezone_set(clone $object, $time);
    })(...func_get_args());
}

function date()
{
    return curry('\date')(...func_get_args());
}

function date2()
{
    return curry(function ($format, $timestamp) {
        return \date($format, $timestamp);
    })(...func_get_args());
}

function getdate1()
{
    return curry(function ($timestamp) {
        return \getdate($timestamp);
    })(...func_get_args());
}

function gettimeofday1()
{
    return curry(function ($returnFloat) {
        return \gettimeofday($returnFloat);
    })(...func_get_args());
}

function gmdate()
{
    return curry('\gmdate')(...func_get_args());
}

function gmdate2()
{
    return curry(function ($format, $timestamp) {
        return \gmdate($format, $timestamp);
    })(...func_get_args());
}

function gmstrftime()
{
    return curry('\gmstrftime')(...func_get_args());
}

function gmstrftime2()
{
    return curry(function ($format, $timestamp) {
        return \gmstrftime($format, $timestamp);
    })(...func_get_args());
}

function idate()
{
    return curry('\idate')(...func_get_args());
}

function idate2()
{
    return curry(function ($format, $timestamp) {
        return \idate($format, $timestamp);
    })(...func_get_args());
}

function localtime1()
{
    return curry(function ($time) {
        return \localtime($time);
    })(...func_get_args());
}

function localtime2()
{
    return curry(function ($isAssociative, $time) {
        return \localtime($time, $isAssociative);
    })(...func_get_args());
}

function microtime1()
{
    return curry(function ($getAsFloat) {
        return \microtime($getAsFloat);
    })(...func_get_args());
}

function strftime()
{
    return curry('\strftime')(...func_get_args());
}

function strftime2()
{
    return curry(function ($format, $timestamp) {
        return \strftime($format, $timestamp);
    })(...func_get_args());
}

function strptime()
{
    return curry(function ($format, $date) {
        return \strptime($date, $format);
    })(...func_get_args());
}

function strtotime()
{
    return curry('\strtotime')(...func_get_args());
}

function strtotime2()
{
    return curry(function ($now, $time) {
        return \strtotime($time, $now);
    })(...func_get_args());
}

function timezone_identifiers_list1()
{
    return curry(function ($what) {
        return \timezone_identifiers_list($what);
    })(...func_get_args());
}

function timezone_identifiers_list2()
{
    return curry(function ($country, $what) {
        return \timezone_identifiers_list($what, $country);
    })(...func_get_args());
}

function timezone_location_get()
{
    return curry('\timezone_location_get')(...func_get_args());
}

function timezone_name_from_abbr()
{
    return curry('\timezone_name_from_abbr')(...func_get_args());
}

function timezone_name_from_abbr2()
{
    return curry(function ($gmtOffset, $abbr) {
        return \timezone_name_from_abbr($abbr, $gmtOffset);
    })(...func_get_args());
}

function timezone_name_from_abbr3()
{
    return curry(function ($isdst, $gmtOffset, $abbr) {
        return \timezone_name_from_abbr($abbr, $gmtOffset, $isdst);
    })(...func_get_args());
}

function timezone_name_get()
{
    return curry('\timezone_name_get')(...func_get_args());
}

function timezone_offset_get()
{
    return curry('\timezone_offset_get')(...func_get_args());
}

function timezone_open()
{
    return curry('\timezone_open')(...func_get_args());
}

function timezone_transitions_get()
{
    return curry('\timezone_transitions_get')(...func_get_args());
}

function timezone_transitions_get2()
{
    return curry(function ($timestampBegin, \DateTimeZone $object) {
        return \timezone_transitions_get($object, $timestampBegin);
    })(...func_get_args());
}

function timezone_transitions_get3()
{
    return curry(function ($timestampBegin, $timestampEnd, \DateTimeZone $object) {
        return \timezone_transitions_get($object, $timestampBegin, $timestampEnd);
    })(...func_get_args());
}
