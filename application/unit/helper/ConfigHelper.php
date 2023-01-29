<?php

namespace unit\helper;

use app\site\conf\Config;

/**
 * Test classes that want to mess around with app\site\conf\Config can use this trait;
 */
trait ConfigHelper {

    private array $original_configuration;

    public function getOriginalConfiguration(): array {
        return $this->original_configuration;
    }

    protected function setUp(): void {
        $this->original_configuration = Config::get()->getConfiguration();
    }

    protected function tearDown(): void {
        Config::get()->setConfiguration($this->original_configuration);
    }
}
