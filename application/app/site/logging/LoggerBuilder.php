<?php

namespace app\site\logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

abstract class LoggerBuilder {

    const CHANNEL = "channel";
    const LOG_FILE = "log_file";
    const ERR_FILE = "err_file";
    const LOG_LEVEL = "log_level";
    const ERR_LEVEL = "err_level";
    const MAX_LOG_FILES = "max_log_files";
    const MAX_ERR_FILES = "max_err_files";

    protected array $warnings = [];

    public abstract function buildLogger() : Logger;

    protected abstract function createFallBackLogger() : Logger;

    protected function checkWarnings(?Logger $logger) : Logger {
        if (is_null($logger)) {
            $this->warnings[] = "Unable to create logger";
        }
        if (count($this->warnings) > 0) {
            $this->warnings[] =
                "Could not create custom logger. See above for details. Using fallback logger.";
            $err = $this->createDefaultErr();
            foreach ($this->warnings as $warning) {
                $err->error($warning);
            }
            $logger = $this->createFallBackLogger();
        }
        return $logger;
    }

    protected function createDefaultOut() : Logger {
        $logger = new Logger("out");
        $logger->pushHandler(new StreamHandler('php://stdout', 100));
        return $logger;
    }

    protected function createDefaultErr() : Logger {
        $logger = new Logger("err");
        $logger->pushHandler(new StreamHandler('php://stderr', 100));
        return $logger;
    }

}