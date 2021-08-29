#!/usr/bin/env php
<?php
pcntl_async_signals(true);
pcntl_signal(SIGINT, static fn() => exit(0));

use App\App;

include_once __DIR__."/../vendor/autoload.php";

$debug = in_array("-v", $argv, true);

$app = new App(debug: $debug);

$app->runOnetNewsScraper();
