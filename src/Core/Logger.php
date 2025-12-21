<?php

namespace Src\Core;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;

class Logger
{
    private static ?MonologLogger $logger = null;

    public static function getLogger(): MonologLogger
    {
        if (self::$logger === null) {
            self::$logger = new MonologLogger('app');

            // Add a unique ID to each log record
            self::$logger->pushProcessor(new UidProcessor());

            // Log to a file
            $logFile = __DIR__ . '/../../logs/app.log';
            $handler = new StreamHandler($logFile, MonologLogger::DEBUG);
            self::$logger->pushHandler($handler);

            // You can add more handlers here, like for errors only
            $errorLogFile = __DIR__ . '/../../logs/error.log';
            $errorHandler = new StreamHandler($errorLogFile, MonologLogger::WARNING);
            self::$logger->pushHandler($errorHandler);
        }

        return self::$logger;
    }

    public static function debug(string $message, array $context = []): void
    {
        self::getLogger()->debug($message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::getLogger()->info($message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::getLogger()->warning($message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }

    public static function critical(string $message, array $context = []): void
    {
        self::getLogger()->critical($message, $context);
    }
}