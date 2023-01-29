<?php

namespace unit;

/**
 * Running phpunit from commandline:
 * $ phpunit --bootstrap unit/autoload.php unit
 *
 * Running phpunit from phpStorm:
 * make sure this file is set in settings>PHP>Test Frameworks,
 * 'Default bootstrap file' (under Test Runner).
 */
defined("APP_ROOT")
or define("APP_ROOT", realpath(dirname(__DIR__)));

$vendor_autoload = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

//echo "\nBootstrapping from '".__FILE__."'\n";
//echo "\napplication root = '".APP_ROOT."'<br/>";
//echo "\nvendor autoload  = '".$vendor_autoload."'<br/>\n";

spl_autoload_register(function ($para) {
    $path = APP_ROOT . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $para) . '.php';
    if (file_exists($path)) {
        include $path;
        return true;
    }
    return false;
});
require_once $vendor_autoload;

// Redirect all calls for app\site\logging\Log to stdout.
// (Which is better done in setup of individual Test class.)
//Log::setType(Type::stdout);
