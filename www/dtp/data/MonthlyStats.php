<?php

namespace dtp\data;

class MonthlyStats {
  /**
   * @var int
   */
  public $accidents;

  /**
   * @var int
   */
  public $deaths;

  /**
   * @var int
   */
  public $child_deaths;

  /**
   * @var int
   */
  public $injured;

  /**
   * @var int
   */
  public $child_injured;

  /**
   * @var int
   */
  public $month;

  /**
   * @var int
   */
  public $year;

  public function __construct(int $accidents, int $deaths, int $child_deaths, int $injured, int $child_injured, int $date, int $year) {
    $this->accidents = $accidents;
    $this->deaths = $deaths;
    $this->child_deaths = $child_deaths;
    $this->injured = $injured;
    $this->child_injured = $child_injured;
    $this->month = $date;
    $this->year = $year;
  }
}