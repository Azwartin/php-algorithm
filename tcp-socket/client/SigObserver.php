<?php

namespace rnagaev\net\client;

class SigObserver 
{
    protected $terminationCallbacks = [];
    protected $restartCallbacks = [];

    /**
     * register pcntl handlers
    */
    public function __construct() 
    {
        pcntl_signal(SIGTERM, [$this, 'termination']);
        pcntl_signal(SIGHUP, [$this, 'restart']);
    }

    public function addRestartCallback(callable $cb) 
    {
        $this->restartCallbacks[] = $cb;
    }

    public function addTerminationCallback(callable $cb) 
    {
        $this->terminationCallbacks[] = $cb;
    }

    /**
     * Call all callbacks on termination signal
     **/
    protected function termination() 
    {
        self::runCallbacks($this->terminationCallbacks);
    }

    /**
     * Calls all callbacks on hup signal
    */
    protected function restart() 
    {
        self::runCallbacks($this->restartCallbacks);
    }

    protected static function runCallbacks(array $callbacks) 
    {
        foreach ($callbacks as $cb) {
            $cb();
        }
    }
}