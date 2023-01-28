<?php

namespace app\site\logging\build;

use app\site\conf\Config;
use Exception;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Singleton Factory class for setting up Loggers.
 *
 */
class LoggerFactory {

    // naar Out class.
    const LOGGER_NAME_STDOUT = "stdout";


    // old
    const LOGGERS = "loggers";

    const LOGGER_NAME_DEFAULT = "default";


    const CHANNEL = "channel";
    const LOG_FILE = "log_file";
    const ERR_FILE = "err_file";
    const LOG_LEVEL = "log_level";
    const ERR_LEVEL = "err_level";
    const MAX_LOG_FILES = "max_log_files";
    const MAX_ERR_FILES = "max_err_files";


    private static ?LoggerFactory $instance = null;
    private array $configuration = [];
    private array $loggers;
    private array $stock = [];
    private array $warnings = [];

    /**
     * Get the singleton factory LoggerFactory.
     *
     * @return LoggerFactory
     */
    public static function get(): LoggerFactory {
        if (self::$instance == null)
            self::$instance = new LoggerFactory();

        return self::$instance;
    }

    /**
     * Gets the current configuration of this factory.
     *
     * @return array
     */
    public function getConfiguration(): array {
        return $this->configuration;
    }

    /**
     * Sets the new configuration for this factory.
     *
     * Products already in stock in this factory are destroyed.
     *
     * @param array $configuration the new configuration
     * @return array the previous configuration
     */
    public function setConfiguration(array $configuration): array {
        $previous = $this->configuration;
        $this->configuration = $configuration;
        $this->stock = [];
        return $previous;
    }

    /**
     * Get a logger by the given $name. If $name is not set, get the default logger.
     *
     * @throws Exception
     */
    public function getLogger(?string $name = null): LoggerInterface {
        $this->warnings = [];
        $search = $name;

        if (is_null($search)) $search = self::LOGGER_NAME_DEFAULT;
        if (isset($this->stock[$search])) return $this->stock[$search];

        // logger by name of $search was not created or set...
        if ($search == self::LOGGER_NAME_DEFAULT and $this->createDefaultLogger()) return $this->stock[$search];
        elseif ($search == self::LOGGER_NAME_STDOUT and $this->createStdOutLogger()) return $this->stock[$search];
        // custom loggers go here...
        else {
            $this->warnings[] = "Logger not found or could not be created. Logger name = '" . $search . "'";
            $logger = new Logger("err");
            $logger->pushHandler(new StreamHandler('php://stderr', 100));
            foreach ($this->warnings as $warning) $logger->warning($warning);

            $logger = new Logger("out");
            $logger->pushHandler(new StreamHandler('php://stdout', 100));
            $this->stock[$search] = $logger;
            return $logger;
        }
    }

    /**
     * Set a logger for the given $name.
     *
     * @param string $name
     * @param LoggerInterface $logger
     * @return LoggerInterface|null The previous logger under the given $name or null.
     */
    public function setLogger(string $name, LoggerInterface $logger): ?LoggerInterface {
        $previous = null;
        if (isset($this->stock[$name])) $previous = $this->stock[$name];
        $this->stock[$name] = $logger;
        return $previous;
    }

    /**
     * @throws Exception
     */
    private function createDefaultLogger(): bool {
        $validated = $this->validateDefaultLoggerConfiguration();
        if (count($this->warnings) > 0) return false;
        $apphandler = new RotatingFileHandler(
            $validated[self::LOG_FILE],
            $validated[self::MAX_LOG_FILES],
            $validated[self::LOG_LEVEL]);
        $errhandler = new RotatingFileHandler(
            $validated[self::ERR_FILE],
            $validated[self::MAX_ERR_FILES],
            $validated[self::ERR_LEVEL]);
        $logger = new Logger($validated[self::CHANNEL]);
        $logger->pushHandler($apphandler);
        $logger->pushHandler($errhandler);
        $this->stock[self::LOGGER_NAME_DEFAULT] = $logger;
        return true;
    }

    private function validateDefaultLoggerConfiguration(): array {
        $validated = [];
        if (isset($this->loggers[self::LOGGER_NAME_DEFAULT])) {
            $config = $this->loggers[self::LOGGER_NAME_DEFAULT];
            $names = [self::CHANNEL,
                self::LOG_FILE,
                self::LOG_LEVEL,
                self::MAX_LOG_FILES,
                self::ERR_FILE,
                self::ERR_LEVEL,
                self::MAX_ERR_FILES];
            foreach ($names as $name) {
                if (isset($config[$name])) $validated[$name] = $config[$name];
                else $this->warnings[] = "No " . $name . " set for logger '" . self::LOGGER_NAME_DEFAULT . "'";
            }
        } else {
            $this->warnings[] = "No configuration found for logger '" . self::LOGGER_NAME_DEFAULT . "'";
        }
        return $validated;
    }

    private function createStdOutLogger(): bool {
        $validated = $this->validateStOutLoggerConfiguration();
        if (count($this->warnings) > 0) return false;
        $logger = new Logger($validated[self::CHANNEL]);
        $logger->pushHandler(new StreamHandler('php://stdout', $validated[self::LOG_LEVEL]));
        $this->stock[self::LOGGER_NAME_STDOUT] = $logger;
        return true;
    }

    private function validateStOutLoggerConfiguration(): array {
        $validated = [];
        if (isset($this->loggers[self::LOGGER_NAME_STDOUT])) {
            $config = $this->loggers[self::LOGGER_NAME_STDOUT];
            $names = [self::CHANNEL, self::LOG_LEVEL];
            foreach ($names as $name) {
                if (isset($config[$name])) $validated[$name] = $config[$name];
                else $this->warnings[] = "No " . $name . " set for logger '" . self::LOGGER_NAME_STDOUT . "'";
            }

        } else {
            $this->warnings[] = "No configuration found for logger ." . self::LOGGER_NAME_STDOUT . "'";
        }
        return $validated;
    }

    /**
     * @throws Exception
     */
    private function __construct() {
        $this->loadConfiguration();
    }

    /**
     * @throws Exception
     */
    private function loadConfiguration(): void {
        $this->configuration = Config::get()->getConfiguration(get_class($this));
        if (isset($this->configuration[self::LOGGERS])) {
            $this->loggers = $this->configuration[self::LOGGERS];
        } else {
            throw new Exception("No entry '" . self::LOGGERS . "' found in configuration '" . get_class($this) . "'");
        }
    }
}