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
    return curry(function($charlist, $str) {
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
    return curry(function($mode, $string) {
        return \count_chars($string, $mode);
    })(...func_get_args());
}

function crc32()
{
    return curry('\crc32')(...func_get_args());
}

function crypt()
{
    return curry(function($salt, $str) {
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
    return curry(function($flags, $str) {
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
    return curry(function($charMask, $str) {
        return \ltrim($str, $charMask);
    })(...func_get_args());
}

function md5_file()
{
    return curry('\md5_file')(...func_get_args());
}

function md5_file2()
{
    return curry(function($rawOutput, $filename) {
        return \md5_file($filename, $rawOutput);
    })(...func_get_args());
}

function md5()
{
    return curry('\md5')(...func_get_args());
}

function md52()
{
    return curry(function($rawOutput, $str) {
        return \md5($str, $rawOutput);
    })(...func_get_args());
}

function metaphone()
{
    return curry('\metaphone')(...func_get_args());
}

function metaphone2()
{
    return curry(function($phonemes, $str) {
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
    return curry(function($decimals, $num) {
        return \number_format($num, $decimals);
    })(...func_get_args());
}

function number_format4()
{
    return curry(function($decimals, $decPoint, $thousandsSep, $num) {
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
    return curry(function($rawOutput, $filename) {
        return \sha1_file($filename, $rawOutput);
    })(...func_get_args());
}

function sha1()
{
    return curry('\sha1')(...func_get_args());
}

function sha12()
{
    return curry(function($rawOutput, $str) {
        return \sha1($str, $rawOutput);
    })(...func_get_args());
}

function similar_text()
{
    return curry('\similar_text')(...func_get_args());
}

function similar_text_pct()
{
    return curry(function($str1, $str2) {
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
    return curry(function($delim, $str) {
        return \str_getcsv($str, $delim);
    })(...func_get_args());
}

function str_getcsv3()
{
    return curry(function($delim, $enclosure, $str) {
        return \str_getcsv($str, $delim, $enclosure);
    })(...func_get_args());
}

function str_getcsv4()
{
    return curry(function($delim, $enclosure, $escape, $str) {
        return \str_getcsv($str, $delim, $enclosure, $escape);
    })(...func_get_args());
}

function str_ireplace()
{
    return curry('\str_ireplace')(...func_get_args());
}

function str_ireplace_count() {
    return curry(function($search, $replace, $subject) {
        \str_ireplace($search, $replace, $subject, $count);
        return $count;
    })(...func_get_args());
}

function str_pad()
{
    return curry(function($length, $str) {
        return \str_pad($str, $length);
    })(...func_get_args());
}

function str_pad3()
{
    return curry(function($length, $padStr, $str) {
        return \str_pad($str, $length, $padStr);
    })(...func_get_args());
}

function str_pad4()
{
    return curry(function($length, $padStr, $padType, $str) {
        return \str_pad($str, $length, $padStr, $padType);
    })(...func_get_args());
}

function str_repeat()
{
    return curry(function($multiplier, $str) {
        return \str_repeat($str, $multiplier);
    })(...func_get_args());
}

function str_replace()
{
    return curry('\str_replace')(...func_get_args());
}

function str_replace_count()
{
    return curry(function($search, $replace, $subject) {
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
    return curry(function($len, $str) {
        return \str_split($str, $len);
    })(...func_get_args());
}

function str_word_count()
{
    return curry('\str_word_count')(...func_get_args());
}

function str_word_count2()
{
    return curry(function($format, $str) {
        return \str_word_count($str, $format);
    })(...func_get_args());
}

function str_word_count3()
{
    return curry(function($format, $charList, $str) {
        return \str_word_count($str, $format, $charList);
    })(...func_get_args());
}

function strcasecmp()
{
    return curry('\strcasecmp')(...func_get_args());
}

function strchr()
{
    return curry(function($needle, $haystack) {
        return \strchr($haystack, $needle);
    })(...func_get_args());
}

function strchr3()
{
    return curry(function($beforeNeedle, $needle, $haystack) {
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
    return curry(function($mask, $str) {
        return \strcspn($str, $mask);
    })(...func_get_args());
}

function strcspn3()
{
    return curry(function($start, $mask, $str) {
        return \strcspn($str, $mask, $start);
    })(...func_get_args());
}

function strcspn4()
{
    return curry(function($start, $end, $mask, $str) {
        return \strcspn($str, $mask, $start, $end);
    })(...func_get_args());
}
