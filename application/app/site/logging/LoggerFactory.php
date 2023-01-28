<?php

namespace app\site\logging;

use app\site\logging\build\AbstractLoggerBuilder;
use app\site\logging\build\DefaultLoggerBuilder;
use app\site\logging\build\ErrLoggerBuilder;
use app\site\logging\build\OutLoggerBuilder;
use Psr\Log\LoggerInterface;

class LoggerFactory {

    const LOGGER_DEFAULT = "default";
    const LOGGER_OUT = "out";
    const LOGGER_ERR = "err";

    private static ?LoggerFactory $instance = null;

    public static function get(): LoggerFactory {
        if (self::$instance == null)
            self::$instance = new LoggerFactory();

        return self::$instance;
    }

    private array $loggers = [];

    public function getLogger(?string $name = null): LoggerInterface  {
        if (!isset($name)) $name = self::LOGGER_DEFAULT;
        if (!isset($this->loggers[$name])) {
            switch ($name) {
                case self::LOGGER_DEFAULT :
                    $this->loggers[$name] = (new DefaultLoggerBuilder())->buildLogger();
                    break;
                case self::LOGGER_OUT :
                    $this->loggers[$name] = (new OutLoggerBuilder())->buildLogger();
                    break;
                case self::LOGGER_ERR :
                    $this->loggers[$name] = (new ErrLoggerBuilder())->buildLogger();
                    break;
                default :
                    $err = AbstractLoggerBuilder::createDefaultErr();
                    $err->error("Unknown logger: ".$name);
                    $this->loggers[$name] = $err;
            }
        }
        return $this->loggers[$name];
    }

}