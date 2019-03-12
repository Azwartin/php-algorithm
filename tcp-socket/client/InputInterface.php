<?php

namespace rnagaev\net\client;

interface InputInterface 
{
    /**
     * get user input
     * @param $prompt string
     * @return string
    */
    public function input(string $prompt): string;
}