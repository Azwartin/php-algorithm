<?php

namespace rnagaev\net\server;

use rnagaev\net\logger\{
    LogLevel,
    LoggerInterface,
    LoggerAwareInterface
};

class Server implements LoggerAwareInterface
{
    /* @var string $address */
    protected $address;
    /* @var int $port */
    protected $port;
    /* @var resource $socket */
    protected $socket;
    /* @var LoggerInterface $logger */
    protected $logger;

    public function __construct(string $address, int $port)
    {
        $this->address = $address;
        $this->port = $port;
    }

    public function __destruct()
    {
        if ($this->socket) {
            @socket_close($this->socket);
        }
    }

    /**
     * run server
     * @return string - error description or empty string if success
    */
    public function run(): string
    {
        [$this->socket, $err] = $this->createSocket();
        if ($err) {
            $this->log(LogLevel::EMERGENCY, $err);
            return $err;
        }

        $err = $this->listen();
        if ($err) {
            return $err;
        }

        return '';
    }

    /**
     * create socket by params
     * @return array [resource - socket, string errror]
    */
    protected function createSocket() 
    {
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {            
            return [null, "Cannot create socket: "  . $this->getSocketError()];
        }
        
        if (!@socket_bind($socket, $this->address, $this->port)) {
            return [null, "Cannot bind socket: "  . $this->getSocketError()];
        }
        
        if (!@socket_listen($socket)) {
            return [null, "Cannot listen socket: "  . $this->getSocketError()];
        }

        return [$socket, ''];
    }

    /**
     * listen cycle
     * @return array []
    */
    protected function listen() 
    {
        $this->log(LogLevel::DEBUG, "Server listen at $this->address:$this->port");
        while (true) {
            $connection = null;
            while (!$connection) {
                $connection = socket_accept($this->socket);
                if (!$connection) {
                    $this->log(LogLevel::ERROR, "Cannot accept socket: "  . $this->getSocketError());
                }
            }
        
            $this->log(LogLevel::DEBUG, "Connection accept");
            while(true) {
                $msg = @socket_read($connection, 1024);
                if ($msg === false) {
                    $this->log(LogLevel::DEBUG, "Connection close");
                    $connection = null;
                    break;
                }
        
                $this->log(LogLevel::DEBUG, "User print: $msg");
                $out = `$msg` ?: 'OK';
                if (@socket_write($connection, $out) === false) {
                    $this->log(LogLevel::DEBUG, "Connection close");
                    $connection = null;
                    break;
                }
            }
        }
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /*log message if logger setted */
    public function log(string $level, string $message) 
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->log($level, $message);
    }

    protected function getSocketError(): string 
    {
        return socket_strerror($this->socket ? socket_last_error($this->socket) : socket_last_error());
    }
}
