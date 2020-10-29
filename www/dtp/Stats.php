<?php

namespace dtp;

use dtp\data\DailyStats;

class Stats {

  public function __construct() {
  }

  public function getLastStats(): DailyStats {
    $base = new Base();
    return $base->getLast();
  }
}
