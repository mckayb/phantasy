<?php

namespace PHPFP\DataTypes\Validation;

use PHPFP\DataTypes\Maybe\Just;
use PHPFP\DataTypes\Either\Right;

class Success {
  private $__value = null;

  public function __construct($val) {
    $this->__value = $val;
  }

  public function map($f) {
    return new Success($f($this->__value));
  }

  public function ap($validationWithFunc) {
    $val = $this->__value;
    return $validationWithFunc instanceof Failure
      ? $validationWithFunc
      : $validationWithFunc->map(function($fn) use ($val) {
        return $fn($val);
      });
  }

  public function fold($f, $g) {
    return $g($this->__value);
  }

  public function bimap($f, $g) {
    return new Success($g($this->__value));
  }

  // Aliases
  public function cata($f, $g) {
    return $this->fold($f, $g);
  }

  public function toEither() {
    return new Right($this->__value);
  }

  public function toMaybe() {
    return new Just($this->__value);
  }
}
