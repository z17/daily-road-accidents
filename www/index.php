<?php

require_once 'autoload.php';

use dtp\Stats;

header('Access-Control-Allow-Origin: *');

$path = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';

switch ($path) {
  case '/stats/':
    $stats = new Stats();
    echo json_encode($stats->getLastStats());
    exit;
  default:
    header("HTTP/1.0 404 Not Found");
    echo 404;
    exit;
}
