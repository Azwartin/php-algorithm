<?php

//run simple cli tcp server on $address:$port 
//usage php server.php 127.0.0.1 9090
require __DIR__ . '/vendor/autoload.php';

use rnagaev\net\server\Server;
use rnagaev\net\logger\EchoLogger;

$address = $argv[1] ?? "0.0.0.0";
$port = $argv[2] ?? 9092;
$server = new Server($address, $port);
$logger = new EchoLogger();
$server->setLogger($logger);
$server->run();