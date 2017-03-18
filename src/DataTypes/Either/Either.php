<?php

namespace PHPFP\DataTypes\Either;

class Either {
  public static function of($val) {
    return new Right($val);
  }

  public static function fromNullable($val) {
    return is_null($val) ? new Left($val) : new Right($val);
  }

  public static function tryCatch($f) {
    try {
      return new Right($f());
    } catch (\Exception $e) {
      return new Left($e->getMessage());
    }
  }
}
