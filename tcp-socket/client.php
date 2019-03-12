<?php

//run cli tcp client to $address:$port 
//usage php client.php 127.0.0.1 9090
require __DIR__ . '/vendor/autoload.php';

use rnagaev\net\client\Client;
use rnagaev\net\logger\EchoLogger;
use rnagaev\net\client\TerminalIO;
use rnagaev\net\client\SigObserver;

$address = $argv[1] ?? "127.0.0.1";
$port = $argv[2] ?? 9092;

$io = new TerminalIO();
$client = new Client($io, $io);
$logger = new EchoLogger();
$client->setLogger($logger);
$client->connect($address, $port);