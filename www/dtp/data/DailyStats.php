<?php

namespace dtp\data;

class DailyStats {
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
  public $date;

  private function __construct(int $accidents, int $deaths, int $child_deaths, int $injured, int $child_injured, int $date) {
    $this->accidents = $accidents;
    $this->deaths = $deaths;
    $this->child_deaths = $child_deaths;
    $this->injured = $injured;
    $this->child_injured = $child_injured;
    $this->date = $date;
  }

  public static function make(?int $accidents, ?int $deaths, ?int $child_deaths, ?int $injured, ?int $child_injured, ?int $date): ?DailyStats {

    if ($accidents === null
      || $deaths === null
      || $child_deaths === null
      || $injured === null
      || $child_injured === null
      || $date === null
    ) {
      return null;
    }

    return new self($accidents, $deaths, $child_deaths, $injured, $child_injured, $date);
  }
}