<?php

namespace rnagaev\net\client;

use rnagaev\net\logger\{
    LogLevel,
    LoggerInterface,
    LoggerAwareInterface
};

use  rnagaev\net\client\{
    InputInterface,
    OutputInterface
};

class Client implements LoggerAwareInterface
{
    /* @var resource $socket */
    protected $socket;
    /* @var LoggerInterface $logger */
    protected $logger;
    /* @var InputInterface $input */
    protected $input;
    /* @var OutputInterface $output */
    protected $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function __destruct()
    {
        if ($this->socket) {
            @socket_close($this->socket);
        }
    }

    /**
     * connect to server and run communication cycle
     * @return string - error description or empty string if success
    */
    public function connect(string $address, int $port): string
    {
        [$this->socket, $err] = $this->connectToSocket($address, $port);
        if ($err) {
            $this->log(LogLevel::EMERGENCY, $err);
            return $err;
        }

        $this->communicate();
    }

    /**
     * connect to socket by params
     * @param string $address
     * @param int $port
     * @return array [resource - socket, string errror]
    */
    public function connectToSocket(string $address, int $port): array
    {
        $addrinfo = @socket_addrinfo_lookup($address, $port);
        if (!$addrinfo) {
            return [null, "Cannot connect to $address:$port"];
        }
        
        $socket = @socket_addrinfo_connect($addrinfo[0]);
        if (!$socket) {
            return [null, "Cannot connect to $address:$port"];
        }

        return [$socket, ''];
    }

    /**
     * Communication cycle. Client send message and wait response from service. After response 
     **/
    public function communicate() 
    {
        $this->log(LogLevel::DEBUG, "Connection established");
        while(true) {
            $msg = readline("Command:");
            if (@socket_write($this->socket, $msg) === false) {
                $this->log(LogLevel::DEBUG, "Connection close");
            }
        
            $msg = @socket_read($this->socket, 1024);
            if ($msg === false) {
                $this->log(LogLevel::DEBUG, "Connection close");
            }
            echo "Server:\n$msg\n";
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
}