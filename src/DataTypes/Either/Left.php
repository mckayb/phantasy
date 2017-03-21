<?php

namespace PHPFP\DataTypes\Either;

use PHPFP\DataTypes\Maybe\Nothing;

class Left {
  private $__value = null;

  public function __construct($val) {
    $this->__value = $val;
  }

  public function map($f) {
    return $this;
  }

  public function ap($eitherWithFunction) {
    return $this;
  }

  public function chain($f) {
    return $this;
  }

  public function fold($f, $g) {
    return $f($this->__value);
  }

  public function bimap($f, $g) {
    return new Left($f($this->__value));
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
    return new Nothing();
  }
}
