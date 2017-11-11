<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Maybe;

use Phantasy\DataTypes\Either\Either;
use Phantasy\DataTypes\Validation\Validation;
use Phantasy\Traits\CurryNonPublicMethods;

abstract class Maybe
{
    use CurryNonPublicMethods;
    abstract protected function getOrElse($x);
    abstract protected function equals(Maybe $e) : bool;
    abstract protected function concat(Maybe $e) : Maybe;
    abstract protected function map(callable $f) : Maybe;
    abstract protected function ap(Maybe $eitherWithFunction) : Maybe;
    abstract protected function chain(callable $f) : Maybe;
    abstract protected function bind(callable $f) : Maybe;
    abstract protected function flatMap(callable $f) : Maybe;
    abstract protected function extend(callable $f) : Maybe;
    abstract protected function fold(callable $f, callable $g);
    abstract protected function cata(callable $f, callable $g);
    abstract protected function alt(Maybe $m) : Maybe;
    abstract protected function reduce(callable $f, $acc);
    abstract protected function traverse(callable $of, callable $f);
    abstract protected function sequence(callable $of);
    abstract protected function toEither($failVal) : Either;
    abstract protected function toValidation($failVal) : Validation;
    abstract public function __toString() : string;

    final private static function of($val) : Maybe
    {
        return new Just($val);
    }

    final private static function fromFalse($val) : Maybe
    {
        return $val === false ? new Nothing() : new Just($val);
    }

    final private static function fromNullable($val) : Maybe
    {
        return is_null($val) ? new Nothing() : new Just($val);
    }

    final private static function tryCatch(callable $f) : Maybe
    {
        try {
            return new Just($f());
        } catch (\Exception $e) {
            return new Nothing();
        }
    }

    final public static function empty() : Maybe
    {
        return new Nothing();
    }

    final public static function zero() : Maybe
    {
        return new Nothing();
    }
}
