<?php

require_once __DIR__ . '/www/vendor/autoload.php';
require_once 'www/autoload.php';

use dtp\DailyStatsPoster;

$parser = new DailyStatsPoster(false);
$parser->process();
