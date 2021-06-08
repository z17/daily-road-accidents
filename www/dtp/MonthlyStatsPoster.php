<?php

namespace dtp;

use dtp\data\MonthlyStats;

class MonthlyStatsPoster {

  /** @var int */
  private $month;

  /** @var int */
  private $year;

  public function __construct() {
    $dt = date_create('last month');
    $this->month = (int)$dt->format('m');
    $this->year = (int)$dt->format('Y');
  }

  public function process(): bool {
    $base = new Base();
    $monthly_data = $base->getMonthStats($this->month, $this->year);

    if (!$monthly_data) {
      return false;
    }

    $accidents = 0;
    $deaths = 0;
    $child_deaths = 0;
    $injured = 0;
    $child_injured = 0;

    foreach ($monthly_data as $record) {
      $accidents += $record->accidents;
      $deaths += $record->deaths;
      $child_deaths += $record->child_deaths;
      $injured += $record->injured;
      $child_injured += $record->child_injured;
    }

    $monthly_stats = new MonthlyStats($accidents, $deaths, $child_deaths, $injured, $child_injured, $this->month, $this->year);

    $posting = new Posting();
    return $posting->postMonthlyStats($monthly_stats);
  }
}
