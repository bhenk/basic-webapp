<?php

namespace unit\site\logging\build;

use app\site\conf\Config;
use app\site\logging\build\DefaultLoggerBuilder;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use unit\helper\ConfigHelper;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

class DefaultLoggerBuilderTest extends TestCase {
    use ConfigHelper;

    public function testBuildLogger() {
        $builder = new DefaultLoggerBuilder();
        $logger = $builder->buildLogger();

        assertInstanceOf(Logger::class, $logger,
            "Expected a logger");
        assertEmpty($builder->getWarnings(),
            "Normal build process. No warnings expected");

        $handlers = $logger->getHandlers();
        assertTrue(count($handlers) >= 2,
            "Expected at least two handlers");
    }

    public function testBuildLoggerWithWarnings() {
        $config = [];
        Config::get()->setConfigurationFor(DefaultLoggerBuilder::class, $config);

        $builder = new DefaultLoggerBuilder();
        $builder->setQuiet(true);
        $logger = $builder->buildLogger();

        assertInstanceOf(Logger::class, $logger,
            "Things might have gone wrong, but we want a logger anyway");
        assertNotEmpty($builder->getWarnings(),
            "There should be warnings because the builder had no configuration");
    }

}
