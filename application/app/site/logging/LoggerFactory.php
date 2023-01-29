<?php

namespace app\site\logging;

use app\site\logging\build\DefaultLoggerBuilder;
use app\site\logging\build\ErrLoggerBuilder;
use app\site\logging\build\OutLoggerBuilder;
use Psr\Log\LoggerInterface;

class LoggerFactory {

    private static ?LoggerFactory $instance = null;

    public static function get(): LoggerFactory {
        if (self::$instance == null)
            self::$instance = new LoggerFactory();

        return self::$instance;
    }

    private array $loggers = [];

    public function getLogger(Type $type): LoggerInterface  {
        if (!isset($this->loggers[$type->name])) {
            switch ($type) {
                case Type::default :
                    $this->loggers[$type->name] = (new DefaultLoggerBuilder())->buildLogger();
                    break;
                case Type::stdout :
                    $this->loggers[$type->name] = (new OutLoggerBuilder())->buildLogger();
                    break;
                case Type::stderr :
                    $this->loggers[$type->name] = (new ErrLoggerBuilder())->buildLogger();
                    break;
            }
        }
        return $this->loggers[$type->name];
    }

}