<?php

namespace app\site\logging\build;

use Monolog\Logger;

class StdErrLoggerBuilder extends StreamLoggerBuilder {

    protected function getStream(): string {
        return "php://stderr";
    }

    protected function createFallBackLogger(): Logger {
        return $this->createDefaultErr();
    }

    protected function getChannel(): string {
        return "err";
    }
}