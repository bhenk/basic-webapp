<?php

namespace unit\site\conf;

use app\site\conf\Config;
use Exception;
use PHPUnit\Framework\TestCase;
use unit\helper\ConfigHelper;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertFileIsReadable;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

class ConfigTest extends TestCase {
    use ConfigHelper;

    public function testSetConfiguration() {
        $config = [
            get_class($this) => [
                "is unit tester" => true,
                "name" => "Joe",
                "skills" => [
                    "java",
                    "python",
                    "php",
                ],
            ],
        ];
        Config::get()->setConfiguration($config);
        assertEquals(1, Config::get()->getSize(),
            "Now there should only be 1 item in configuration");

        $myConfig = Config::get()->getConfigurationFor(get_class($this));
        assertEquals("Joe", $myConfig["name"],
            "The name in myConfig should be 'Joe'");
        assertEquals(3, count($myConfig["skills"]),
            "Joe has 3 skills");
    }

    public function testSetConfigurationFor() {
        $myConfig = [
            "is unit tester" => true,
            "name" => "Joe",
            "skills" => [
                "java",
                "python",
                "php",
            ],
        ];
        // get original count of items in configuration
        $config = Config::get()->getConfiguration();
        $original_count = count($config);

        // add a new configuration for ...
        $previous = Config::get()->setConfigurationFor(get_class($this), $myConfig);
        assertNull($previous,
            "Nothing was set before so previous should be null");

        $config = Config::get()->getConfiguration();
        assertEquals($original_count + 1, count($config),
            "count after update should be 1 more than original count");

        $myReturnedConfig = Config::get()->getConfigurationFor(get_class($this));
        assertEquals($myConfig, $myReturnedConfig,
            "the returned config should be equal to the set config");
    }

    public function testGetConfiguration() {
        $config = Config::get()->getConfiguration();
        assertNotEmpty($config,
            "Deployment configuration array is empty");
    }

    public function testGetConfigurationFor() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("not set or null");
        Config::get()->getConfigurationFor(get_class($this));
    }

    public function testGetDefaultConfigFileName() {
        $file = Config:: getDefaultConfigFileName();
        assertFileExists($file);
        assertFileIsReadable($file);
        $expected = dirname(__DIR__, 3)
            . DIRECTORY_SEPARATOR . "config"
            . DIRECTORY_SEPARATOR . "config.php";
        assertEquals($expected, $file, "Configuration filename not expected");
    }

    public function testLoad() {
        Config::get()->load();
        assertEquals($this->getOriginalConfiguration(), Config::get()->getConfiguration(),
            "Load with null filename should default to default config filename");


        $test_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "test_file.php";
        Config::get()->load($test_file);
        $config = Config::get()->getConfiguration();
        assertTrue($config["foo"]);
        assertFalse($config["bar"]);

        $test_file = "/foo/bar";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("File does not exist: /foo/bar");
        Config::get()->load($test_file);
    }
}
