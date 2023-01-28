<?php

namespace app\site\logging;

use Stringable;

class Log {

    private static ?string $loggerName = null;

    public static function getLoggerName(): ?string {
        return self::$loggerName;
    }

    public static function setLoggerName(string $loggerName = null): ?string {
        $previous = self::$loggerName;
        self::$loggerName = $loggerName;
        return $previous;
    }

    public static function emergency(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->emergency($message, $context);
    }


    public static function alert(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->alert($message, $context);
    }


    public static function critical(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->critical($message, $context);
    }


    public static function error(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->error($message, $context);
    }

    public static function warning(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->warning($message, $context);
    }


    public static function notice(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->notice($message, $context);
    }

    public static function info(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->info($message, $context);
    }

    public static function debug(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->debug($message, $context);
    }

    public static function log($level, Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$loggerName)->log($level, $message, $context);
    }
}