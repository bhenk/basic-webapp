<?php

namespace unit\site\logging\build;

use app\site\conf\Config;
use app\site\logging\build\ErrLoggerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use unit\helper\ConfigHelper;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEmpty;

class ErrLoggerBuilderTest extends TestCase {
    use ConfigHelper;

    public function testBuildLogger() {
        $builder = new ErrLoggerBuilder();
        $logger = $builder->buildLogger();

        assertInstanceOf(Logger::class, $logger,
            "Expected a logger");

        $handler = $logger->getHandlers()[0];
        assertInstanceOf(StreamHandler::class, $handler,
            "Expected a StreamHandler as first Handler");

        assertEmpty($builder->getWarnings(),
            "Normal build process. No warnings expected");
    }

    public function testBuildLoggerWithWarnings() {
        $config = [];
        Config::get()->setConfigurationFor(ErrLoggerBuilder::class, $config);

        $builder = new ErrLoggerBuilder();
        $builder->setQuiet(true);
        $logger = $builder->buildLogger();

        assertInstanceOf(Logger::class, $logger,
            "Things might have gone wrong, but we want a logger anyway");
        assertNotEmpty($builder->getWarnings(),
            "There should be warnings because the builder had no configuration");
    }
}
