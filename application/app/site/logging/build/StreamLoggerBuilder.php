<?php

namespace app\site\logging\build;

use app\site\conf\Config;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class StreamLoggerBuilder extends AbstractLoggerBuilder {

    public function buildLogger(): Logger {
        $this->warnings = [];
        $logger = null;
        try {
            $config = Config::get()->getConfigurationFor(get_class($this));
            $validated = $this->validateConfig($config);
            $logger = new Logger($validated[self::CHANNEL]);
            $logger->pushHandler(new StreamHandler($this->getStream(), $validated[self::LOG_LEVEL]));
            //$logger->pushProcessor(new IntrospectionProcessor());
        } catch (Exception $e) {
            $this->warnings[] = $e->getMessage();
        }
        return $this->checkWarnings($logger);
    }

    private function validateConfig(array $config): array {
        $validated = [self::CHANNEL => $this->getChannel(), self::LOG_LEVEL => 100];
        $names = [
            self::CHANNEL,
            self::LOG_LEVEL
        ];
        foreach ($names as $name) {
            if (isset($config[$name])) $validated[$name] = $config[$name];
            else $this->warnings[] = "No " . $name . " set in configuration for " . get_class($this);
        }
        return $validated;
    }

    protected abstract function getChannel(): string;

    protected abstract function getStream(): string;
}