<?php
//connect to tcp server on $address:$port 
//usage php client.php 127.0.0.1 9090
$address = $argv[1] ?? "127.0.0.1";
$port = $argv[2] ?? 9092;
$addrinfo = socket_addrinfo_lookup($address, $port);
if (!$addrinfo) {
    echo "Cannot connect to $address:$port\n";
    return;
}

$socket = socket_addrinfo_connect($addrinfo[0]);
if (!$socket) {
    echo "Cannot connect to $address:$port\n";
    return;
}

while(true) {
    $msg = readline("Command:");
    if (socket_write($socket, $msg) === false) {
        echo "Connection close\n";
        break;
    }

    $msg = socket_read($socket, 1024);
    if ($msg === false) {
        echo "Connection close\n";
        break;
    }
    echo "Server:\n$msg\n";
}

socket_close($socket);