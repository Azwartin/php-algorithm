<?php

namespace rnagaev\net\client;

class TerminalIO implements InputInterface, OutputInterface 
{
    public function input(string $prompt = 'Command: '): string
    {
        return readline($prompt);
    }

    public function output(string $response)
    {
        echo "Server:\n$response\n";
    }
}