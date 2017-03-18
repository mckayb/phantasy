<?php

namespace PHPFP\DataTypes\Either;

use PHPFP\DataTypes\Maybe\Just;

class Right {
  private $__value = null;

  public function __construct($val) {
    $this->__value = $val;
  }

  public function map($f) {
    return Either::of($f($this->__value));
  }

  public function ap($eitherWithFunction) {
    $val = $this->__value;
    return $eitherWithFunction->map(function($fn) use ($val) {
      return $fn($val);
    });
  }

  public function chain($f) {
    return $f($this->__value);
  }

  public function fold($f, $g) {
    return $g($this->__value);
  }

  // Aliases
  public function bind($f) {
    return $this->chain($f);
  }

  public function flatMap($f) {
    return $this->chain($f);
  }

  public function cata($f, $g) {
    return $this->fold($f, $g);
  }

  // Conversions
  public function toMaybe() {
    return new Just($this->__value);
  }
}
