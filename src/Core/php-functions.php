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
    return curry(function ($char_mask, $str) {
        return \chop($str, $char_mask);
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
