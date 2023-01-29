<?php

namespace app\site\logging\build;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class AbstractLoggerBuilder {

    const CHANNEL = "channel";
    const LOG_FILE = "log_file";
    const ERR_FILE = "err_file";
    const LOG_LEVEL = "log_level";
    const ERR_LEVEL = "err_level";
    const MAX_LOG_FILES = "max_log_files";
    const MAX_ERR_FILES = "max_err_files";

    protected array $warnings = [];
    protected bool $quiet = false;

    public static function createDefaultOut(): Logger {
        $logger = new Logger("out");
        $logger->pushHandler(new StreamHandler('php://stdout', 100));
        return $logger;
    }

    /**
     * @return bool
     */
    public function isQuiet(): bool {
        return $this->quiet;
    }

    /**
     * @param bool $quiet
     */
    public function setQuiet(bool $quiet): void {
        $this->quiet = $quiet;
    }

    public abstract function buildLogger(): Logger;

    public function getWarnings() {
        return $this->warnings;
    }

    protected function checkWarnings(?Logger $logger): Logger {
        if (is_null($logger)) {
            $this->warnings[] = "Unable to create logger";
        }
        if (count($this->warnings) > 0) {
            $this->warnings[] =
                "Could not create custom logger. See above for details. Using fallback logger.";
            if (!$this->quiet) {
                $err = AbstractLoggerBuilder::createDefaultErr();
                foreach ($this->warnings as $warning) {
                    $err->error($warning);
                }
            }
            $logger = $this->createFallBackLogger();
        }
        return $logger;
    }

    public static function createDefaultErr(): Logger {
        $logger = new Logger("err");
        $logger->pushHandler(new StreamHandler('php://stderr', 100));
        return $logger;
    }

    protected abstract function createFallBackLogger(): Logger;

}