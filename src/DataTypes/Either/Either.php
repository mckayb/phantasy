<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Either;

use Phantasy\DataTypes\Maybe\Maybe;
use Phantasy\DataTypes\Validation\Validation;
use Phantasy\Traits\CurryNonPublicMethods;

abstract class Either
{
    use CurryNonPublicMethods;

    abstract protected function equals(Either $e) : bool;
    abstract protected function concat(Either $e) : Either;
    abstract protected function map(callable $f) : Either;
    abstract protected function ap(Either $eitherWithFunction) : Either;
    abstract protected function chain(callable $f) : Either;
    abstract protected function bind(callable $f) : Either;
    abstract protected function flatMap(callable $f) : Either;
    abstract protected function extend(callable $f) : Either;
    abstract protected function fold(callable $f, callable $g);
    abstract protected function cata(callable $f, callable $g);
    abstract protected function bimap(callable $f, callable $g) : Either;
    abstract protected function alt(Either $e) : Either;
    abstract protected function reduce(callable $f, $acc);
    abstract protected function traverse(callable $of, callable $f);
    abstract protected function sequence(callable $of);
    abstract public function toMaybe() : Maybe;
    abstract public function toValidation() : Validation;
    abstract public function __toString() : string;
    abstract public function __construct($x);

    final private static function of($val) : Either
    {
        return new Right($val);
    }

    final private static function fromFalse($failVal, $val) : Either
    {
        return $val === false ? new Left($failVal) : new Right($val);
    }

    final private static function fromNullable($failVal, $val) : Either
    {
        return is_null($val) ? new Left($failVal) : new Right($val);
    }

    final private static function tryCatch(callable $f) : Either
    {
        try {
            return new Right($f());
        } catch (\Exception $e) {
            return new Left($e);
        }
    }

    final public static function zero() : Either
    {
        return new Left(null);
    }
}
