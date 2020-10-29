<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'autoload.php';

use dtp\DailyStatsPoster;

$parser = new DailyStatsPoster(false);
$parser->process();
