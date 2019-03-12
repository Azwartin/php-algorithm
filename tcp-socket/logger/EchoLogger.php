<?php

namespace rnagaev\net\logger;

class EchoLogger extends AbstractLogger
{
    public function log($level, $message, array $context = array())
    {
        $message = $message . ($context ? ("\nContext:\n" . join("\n", $context)) : '') . "\n";
        echo $this->getColoredString($message, self::getFGColor($level));
    }

    /**
     * returns special characters to colorize string
     * @param string $level - log level
     * @return string
    */
    protected static function getFGColor(string $level): string
    {
        $colors = [
            LogLevel::EMERGENCY => '0;31',
            LogLevel::CRITICAL => '0;31',
            LogLevel::CRITICAL =>  '1;31',
            LogLevel::ERROR =>  '1;31',
            LogLevel::WARNING => '1;33',
            LogLevel::NOTICE => '1;34',
            LogLevel::INFO => '1;32',
            LogLevel::DEBUG => '0;32',
        ];

        return $colors[$level] ?? '';
    }

    /**
     * returns a colored at cli string
    */
    protected static function getColoredString($string, string $fgColor): string 
    {
        if ($fgColor) {
            return "\033[{$fgColor}m$string\033[0m";
        }

        return $string;
    }
} 