<?php

namespace PHPFP\DataTypes\Validation;

use PHPFP\DataTypes\Either\Left;
use PHPFP\DataTypes\Maybe\Nothing;
use function PHPFP\Core\semigroupConcat;

class Failure {
  private $__value = null;

  public function __construct($val) {
    $this->__value = $val;
  }

  public function map($f) {
    return $this;
  }

  public function ap($v) {
    return $v instanceof Success
      ? $this
      : new Failure(semigroupConcat($this->__value, $v->__value));
  }

  public function fold($f, $g) {
    return $f($this->__value);
  }

  public function bimap($f, $g) {
    return new Failure($f($this->__value));
  }

  // Aliases
  public function cata($f, $g) {
    return $this->fold($f, $g);
  }

  // Transformations
  public function toEither() {
    return new Left($this->__value);
  }

  public function toMaybe() {
    return new Nothing();
  }
}
