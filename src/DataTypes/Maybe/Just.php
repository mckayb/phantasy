<?php

namespace PHPFP\DataTypes\Maybe;

use PHPFP\DataTypes\Either\Right;

class Just {
  private $__value = null;

  public function __construct($val) {
    $this->__value = $val;
  }

  public function map($f) {
    return Maybe::of($f($this->__value));
  }

  public function ap($maybeWithFunction) {
    $val = $this->__value;
    return $maybeWithFunction->map(function($fn) use ($val) {
      return $fn($val);
    });
  }

  public function chain($f) {
    return $f($this->__value);
  }

  public function getOrElse($d) {
    return $this->__value;
  }

  // Aliases
  public function bind($f) {
    return $this->chain($f);
  }

  public function flatMap($f) {
    return $this->chain($f);
  }

  public function toEither() {
    return new Right($this->__value);
  }
}
