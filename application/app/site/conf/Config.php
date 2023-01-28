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
    public function getConfiguration(string $name = null): array {
        if (is_null($name)) {
            return $this->config;
        }
        if (!isset($this->config[$name])) {
            throw new \Exception("Configuration '" . $name . "' not set or null");
        }
        return $this->config[$name];
    }

    public function setConfiguration(array $config, string $name = null): array {
        $previous = null;
        if (is_null($name)) {
            $previous = $this->config;
            $this->config = $config;
        } else {
            $previous = $this->config[$name];
            $this->config[$name] = $config;
        }
        return $previous;
    }

    /**
     * @throws \Exception
     */
    public function load(string $config_file = null): void {
        if (is_null($config_file)) {
            $config_file = $this->getDefaultConfigFileName();
        }
        if (!file_exists($config_file)) {
            throw new \Exception("File does not exist: " . $config_file);
        }
        $this->config = require_once $config_file;
    }

    public function getDefaultConfigFileName(): string {
        return dirname(__FILE__, 4)
            . DIRECTORY_SEPARATOR . "config"
            . DIRECTORY_SEPARATOR . "config.php";
    }

    public static function get(): Config {
        if (self::$instance == null)
            self::$instance = new Config();

        return self::$instance;
    }


}