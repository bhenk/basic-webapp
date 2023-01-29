<?php

namespace unit\site\logging\build;

use app\site\logging\build\OutLoggerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertInstanceOf;

class OutLoggerBuilderTest extends TestCase {

    public function testBuildLogger() {
        $builder = new OutLoggerBuilder();
        $logger = $builder->buildLogger();

        assertInstanceOf(Logger::class, $logger);

        $handler = $logger->getHandlers()[0];
        assertInstanceOf(StreamHandler::class, $handler);

        self::assertEmpty($builder->getWarnings());
    }

}
