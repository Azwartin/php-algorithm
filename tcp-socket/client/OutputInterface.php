<?php

namespace rnagaev\net\client;

interface OutputInterface 
{
    /**
     * display message to user
     * @return string
    */
    public function output(string $response);
}