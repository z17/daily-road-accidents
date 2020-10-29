<?php

require_once __DIR__ . '/vendor/autoload.php';

use dtp\DailyStatsPoster;

spl_autoload_register(function($name) {
  $fileParts = explode('\\', $name);
  $filePath = implode('/', $fileParts) . '.php';
  /** @noinspection PhpIncludeInspection */
  require_once $filePath;
});


$parser = new DailyStatsPoster(true);
$parser->process();