<?php declare(strict_types=1);

namespace Phantasy\DataTypes\Validation;

use Phantasy\DataTypes\Maybe\Maybe;
use Phantasy\DataTypes\Either\Either;
use Phantasy\Traits\CurryNonPublicMethods;

abstract class Validation
{
    use CurryNonPublicMethods;

    abstract protected function equals(Validation $e) : bool;
    abstract protected function concat(Validation $e) : Validation;
    abstract protected function map(callable $f) : Validation;
    abstract protected function ap(Validation $eitherWithFunction) : Validation;
    abstract protected function extend(callable $f) : Validation;
    abstract protected function fold(callable $f, callable $g);
    abstract protected function cata(callable $f, callable $g);
    abstract protected function bimap(callable $f, callable $g) : Validation;
    abstract protected function alt(Validation $e) : Validation;
    abstract protected function reduce(callable $f, $acc);
    abstract protected function traverse(callable $of, callable $f);
    abstract protected function sequence(callable $of);
    abstract public function toMaybe() : Maybe;
    abstract public function toEither() : Either;
    abstract public function __toString() : string;
    abstract public function __construct($x);

    final private static function of($x) : Validation
    {
        return new Success($x);
    }

    final private static function fromFalse($failVal, $val) : Validation
    {
        return $val === false ? new Failure($failVal) : new Success($val);
    }

    final private static function fromNullable($failVal, $val) : Validation
    {
        return is_null($val) ? new Failure($failVal) : new Success($val);
    }

    final private static function tryCatch(callable $f) : Validation
    {
        try {
            return new Success($f());
        } catch (\Exception $e) {
            return new Failure($e);
        }
    }

    final public static function zero() : Validation
    {
        return new Failure([]);
    }
}
