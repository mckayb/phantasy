<?php

namespace PHPFP\DataTypes\Maybe;

use PHPFP\DataTypes\Either\Left;

class Nothing {
  public function map($f) {
    return $this;
  }

  public function ap($maybeWithFunction) {
    return $this;
  }

  public function chain($f) {
    return $this;
  }

  public function getOrElse($d) {
    return $d;
  }

  // Aliases
  public function bind($f) {
    return $this->chain($f);
  }

  public function flatMap($f) {
    return $this->chain($f);
  }

  // Conversions
  public function toEither($val) {
    return new Left($val);
  }
}
