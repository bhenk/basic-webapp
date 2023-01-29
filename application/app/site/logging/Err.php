<?php

namespace app\site\logging;

use Stringable;

class Err {

    private static Type $type = Type::stderr;

    public static function getType(): Type {
        return self::$type;
    }

    public static function setType(Type $type): Type {
        $previous = self::$type;
        self::$type = $type;
        return $previous;
    }

    public static function emergency(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->emergency($message, $context);
    }


    public static function alert(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->alert($message, $context);
    }


    public static function critical(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->critical($message, $context);
    }


    public static function error(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->error($message, $context);
    }

    public static function warning(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->warning($message, $context);
    }


    public static function notice(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->notice($message, $context);
    }

    public static function info(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->info($message, $context);
    }

    public static function debug(Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->debug($message, $context);
    }

    public static function log($level, Stringable|string $message, array $context = []): void {
        LoggerFactory::get()->getLogger(self::$type)->log($level, $message, $context);
    }
}