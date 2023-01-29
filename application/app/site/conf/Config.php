<?php

namespace app\site\conf;

use app\site\logging\Log;

class Config {

    private static ?Config $instance = null;
    private array $config = [];

    /**
     * @throws \Exception
     */
    private function __construct() {
        $this->load();
    }

    /**
     * @throws \Exception
     */
    public function getConfiguration(): array {
        return $this->config;
    }

    public function getConfigurationFor(string $name) {
        if (!isset($this->config[$name])) {
            throw new \Exception("Configuration '" . $name . "' not set or null");
        }
        return $this->config[$name];
    }

    public function setConfiguration(array $config): array {
        $previous = $this->config;
        $this->config = $config;
        return $previous;
    }

    public function setConfigurationFor(string $name, array $config) {
        $previous = isset($this->config[$name]) ? $this->config[$name] : null;
        $this->config[$name] = $config;
        return $previous;
    }

    public function getSize() {
        return count($this->config);
    }

    /**
     * @throws \Exception
     */
    public function load(string $config_file = null): void {
        if (!isset($config_file)) {
            $config_file = $this->getDefaultConfigFileName();
        }
        if (!file_exists($config_file)) {
            throw new \Exception("File does not exist: " . $config_file);
        }
        $this->config = require $config_file;
    }

    public static function getDefaultConfigFileName(): string {
        return dirname(__DIR__, 3)
            . DIRECTORY_SEPARATOR . "config"
            . DIRECTORY_SEPARATOR . "config.php";
    }

    public static function get(): Config {
        if (self::$instance == null)
            self::$instance = new Config();

        return self::$instance;
    }


}