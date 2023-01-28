<?php

namespace app\site\logging\build;

use Monolog\Logger;

class ErrLoggerBuilder extends StreamLoggerBuilder {

    protected function getStream(): string {
        return "php://stderr";
    }

    protected function createFallBackLogger(): Logger {
        return AbstractLoggerBuilder::createDefaultErr();
    }

    protected function getChannel(): string {
        return "err";
    }
}