<?php

namespace app\site\logging;

use app\site\conf\Config;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class DefaultLoggerBuilder extends LoggerBuilder {

    public function buildLogger(): Logger {
        $this->warnings = [];
        $logger = null;
        try {
            $config = Config::get()->getConfiguration(get_class($this));
            if ($this->validateConfig($config)) {
                $app_handler = new RotatingFileHandler(
                    $config[self::LOG_FILE],
                    $config[self::MAX_LOG_FILES],
                    $config[self::LOG_LEVEL]);
                $err_handler = new RotatingFileHandler(
                    $config[self::ERR_FILE],
                    $config[self::MAX_ERR_FILES],
                    $config[self::ERR_LEVEL]);
                $logger = new Logger($config[self::CHANNEL]);
                $logger->pushHandler($app_handler);
                $logger->pushHandler($err_handler);
            }
        } catch (\Exception $e) {
            $this->warnings[] = $e->getMessage();
        }
        return $this->checkWarnings($logger);
    }

    private function validateConfig(array $config): bool {
        $validated = true;
        $names = [self::CHANNEL,
            self::LOG_FILE,
            self::LOG_LEVEL,
            self::MAX_LOG_FILES,
            self::ERR_FILE,
            self::ERR_LEVEL,
            self::MAX_ERR_FILES];
        foreach ($names as $name) {
            if (!isset($config[$name])) {
                $this->warnings[] = "No " . $name . " set in configuration for " . get_class($this);
                $validated = false;
            }
        }
        return $validated;
    }

    protected function createFallBackLogger(): Logger {
        return $this->createDefaultOut();
    }
}