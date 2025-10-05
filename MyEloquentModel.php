<?php

namespace models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class MyEloquentModel extends EloquentModel {
  public static function beginTransaction() {
    self::getConnectionResolver()->connection()->beginTransaction();
  }

  public static function commit() {
    self::getConnectionResolver()->connection()->commit();
  }

  public static function rollBack() {
    self::getConnectionResolver()->connection()->rollBack();
  }
}