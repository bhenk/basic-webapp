<?php

namespace app\site\logging\build;

use Monolog\Logger;

class StdOutLoggerBuilder extends StreamLoggerBuilder {

    protected function getStream(): string {
        return "php://stdout";
    }

    protected function createFallBackLogger(): Logger {
        return $this->createDefaultOut();
    }

    protected function getChannel(): string {
        return "out";
    }
}