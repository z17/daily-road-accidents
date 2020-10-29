<?php

spl_autoload_register(function ($name) {
  $fileParts = explode('\\', $name);
  $filePath = implode('/', $fileParts) . '.php';
  /** @noinspection PhpIncludeInspection */
  require_once $filePath;
});
