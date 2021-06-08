<?php

namespace dtp;

use dtp\data\DailyStats;
use PDO;

class Base {
  private $base;

  public function __construct() {
    $this->base = new PDO("mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME, Config::DB_USER, Config::DB_PASSWORD);
    $this->base->query("set names utf8");
  }

  public function insert(DailyStats $stats) {
    $query = "INSERT INTO daily_stats (`date`, accidents, deaths, child_deaths, injured, child_injured) VALUES (FROM_UNIXTIME(:ddate), :accidents, :deaths, :child_deaths, :injured, :child_injured)";
    $sql = $this->base->prepare($query);
    $sql->bindParam(':ddate', $stats->date);
    $sql->bindParam(':accidents', $stats->accidents);
    $sql->bindParam(':deaths', $stats->deaths);
    $sql->bindParam(':child_deaths', $stats->child_deaths);
    $sql->bindParam(':injured', $stats->injured);
    $sql->bindParam(':child_injured', $stats->child_injured);
    $sql->execute();
  }

  public function getLast(): ?DailyStats {
    $query = "SELECT `date`, accidents, deaths, child_deaths, injured, child_injured FROM daily_stats ORDER BY ID DESC LIMIT 1";

    $sql = $this->base->prepare($query);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
      return null;
    }

    return $this->parseDailyStats($result);
  }

  /**
   * @return DailyStats[]
   */
  public function getMonthStats(int $month, int $year): array {
    $query = "SELECT `date`, accidents, deaths, child_deaths, injured, child_injured FROM daily_stats WHERE MONTH(`date`) = :month AND YEAR(`date`) = :year ORDER BY ID DESC";
    $sql = $this->base->prepare($query);
    $sql->bindParam(':month', $month);
    $sql->bindParam(':year', $year);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
      return [];
    }

    $monthly_days = [];
    foreach ($result as $record) {
      $monthly_days[] = $this->parseDailyStats($record);
    }
    return $monthly_days;
  }

  private function parseDailyStats($record): DailyStats {
    return DailyStats::make((int)$record['accidents'], (int)$record['deaths'], (int)$record['child_deaths'], (int)$record['injured'], (int)$record['child_injured'], strtotime($record['date']));
  }
}
