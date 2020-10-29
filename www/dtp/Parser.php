<?php

namespace dtp;

use DOMDocument;
use dtp\data\DailyStats;

class Parser {

  /** @var bool */
  private $debug_mode = false;

  /**
   * @param bool $debug_mode
   */
  public function __construct(bool $debug_mode) {
    $this->debug_mode = $debug_mode;
  }

  public function parse(): ?DailyStats {
    $file = $this->loadFile();
    $matches = [];
    preg_match("/<table class=\"b-crash-stat\">(.*?)<\/div>/s", $file, $matches);
    $table = $matches[0];

    $dom = new DOMDocument;
    $dom->loadHTML($table);

    $date = self::parseDate($dom);
    [$accidents, $deaths, $child_deaths, $injured, $child_injured] = self::parseValues($dom);

    $daily_stats = DailyStats::make($accidents, $deaths, $child_deaths, $injured, $child_injured, $date);


    if (!$daily_stats) {
      return null;
    }

    return $daily_stats;
  }

  private function loadFile() {
    if ($this->debug_mode) {
      return file_get_contents('tmp/test.html');
    }

    return file_get_contents('https://xn--90adear.xn--p1ai/');
  }

  /**
   * @param DOMDocument $dom
   * @return int[]
   */
  private function parseValues(DOMDocument $dom): array {
    $accidents = null;
    $deaths = null;
    $child_deaths = null;
    $injured = null;
    $child_injured = null;

    $lines = $dom->getElementsByTagName('tr');
    foreach ($lines as $count => $line) {
      $cols = $line->getElementsByTagName('td');
      if (sizeof($cols) === 2) {
        $value_td = $cols[1];

        $value = $value_td->nodeValue;

        switch ($count) {
          case 1:
            $accidents = $value;
            break;
          case 2:
            $deaths = $value;
            break;
          case 3:
            $child_deaths = $value;
            break;
          case 4:
            $injured = $value;
            break;
          case 5:
            $child_injured = $value;
            break;
        }
      }
    }

    return [$accidents, $deaths, $child_deaths, $injured, $child_injured];
  }

  private function parseDate(DOMDocument $dom): ?int {

    $th = $dom->getElementsByTagName('th');

    if (!sizeof($th)) {
      return null;
    }
    $text = $th[0]->nodeValue;

    $matches = [];
    preg_match('/(\d\d)\.(\d\d)\.(\d\d\d\d)/', $text, $matches);

    if (!$matches) {
      return null;
    }

    $day = $matches[1];
    $month = $matches[2];
    $year = $matches[3];

    return mktime(null, null, null, $month, $day, $year);
  }
}