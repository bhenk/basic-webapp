<?php
namespace public_html;

defined("APP_ROOT")
    or define("APP_ROOT", realpath(dirname(__DIR__)));

$vendor_autoload = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . "vendor". DIRECTORY_SEPARATOR."autoload.php";

//echo "application root = '".APP_ROOT."'<br/>";
//echo "vendor autoload  = '".$vendor_autoload."'<br/>";

spl_autoload_register(function($para) {
    $path = APP_ROOT.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $para).'.php';
    if (file_exists($path)) {
        include $path;
        return true;
    }
    return false;
});
require_once $vendor_autoload;

use app\site\handle\AppHandler;

AppHandler::get()->handleRequestURI();



