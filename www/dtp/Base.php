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

  public function getLast() : ?DailyStats {
    $query = "SELECT `date`, accidents, deaths, child_deaths, injured, child_injured FROM daily_stats ORDER BY ID DESC LIMIT 1";

    $sql = $this->base->prepare($query);
    $sql->execute();
    $result = $sql->fetch();

    if (!$result) {
      return null;
    }

    return DailyStats::make((int)$result['accidents'], (int)$result['deaths'], (int)$result['child_deaths'], (int)$result['injured'], (int)$result['child_injured'], strtotime($result['date']));
  }
}
