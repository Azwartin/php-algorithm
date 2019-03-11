<?php
//run echo tcp server on $address:$port 
//usage php socket.php 127.0.0.1 9090
$address = $argv[1] ?? "0.0.0.0";
$port = $argv[2] ?? 9092;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket) {
    echo "Cannot create socket: "  . getSocketError() . "\n";
    return;
}

if (!socket_bind($socket, $address, $port)) {
    echo "Cannot bind socket: "  . getSocketError($socket) . "\n";
    return;
}

if (!socket_listen($socket)) {
    echo "Cannot listen socket: "  . getSocketError($socket) . "\n";
    return;
}

echo "Server listen on $address:$port\n";
while (true) {
    $connection = null;
    while (!$connection) {
        $connection = socket_accept($socket);
        if (!$connection) {
            echo "Cannot accept socket: "  . getSocketError($socket) . "\n";
        }
    }

    echo "Connection accept \n";
    while(true) {
        $msg = @socket_read($connection, 1024);
        if ($msg === false) {
            echo "Connection close\n";
            $connection = null;
            break;
        }

        echo $msg . "\n";
        $out = `$msg` ?: 'OK';
        if (@socket_write($connection, $out) === false) {
            echo "Connection close\n";
            $connection = null;
            break;
        }
    }
}

socket_close($socket);

function getSocketError($socket = null): string 
{
    return socket_strerror(socket_last_error($socket));
}