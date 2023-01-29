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

        assertInstanceOf(Logger::class, $logger);

        $handler = $logger->getHandlers()[0];
        assertInstanceOf(StreamHandler::class, $handler);

        assertEmpty($builder->getWarnings());
    }

    public function testBuildLoggerWithWarnings() {
        $config = [];
        Config::get()->setConfigurationFor(ErrLoggerBuilder::class, $config);

        $builder = new ErrLoggerBuilder();
        $builder->setQuiet(true);
        $logger = $builder->buildLogger();

        assertInstanceOf(Logger::class, $logger);
        assertNotEmpty(count($builder->getWarnings()));
    }
}
