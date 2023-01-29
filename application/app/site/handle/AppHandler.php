<?php

namespace app\site\handle;

use app\site\control\DefaultPageControl;
use app\site\logging\Log;
use Exception;

class AppHandler {
    private static $instance = NULL;

    public static function get(): AppHandler {
        if (is_null(self::$instance)) self::$instance = new AppHandler();
        return self::$instance;
    }

    public function handleRequestURI(): void {
        $path = preg_replace('/[^0-9a-zA-Z\/._ +]/', '-',
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->handleRequest(array_slice(explode('/', $path), 1));
    }

    public function handleRequest(array $url_path): void {
        Log::debug(__METHOD__, $url_path);
        ob_start([$this, 'outputBuffer']);
        try {
            switch ($url_path[0]) {
                case '':
                    (new DefaultPageControl($url_path))->renderPage();
                    return;
                case "zzz" :
                    echo "test";
                    return;
            }
        } catch (Exception $e) {
            //Log::error('Catch all', array('exception' => $e));
            ob_end_clean();
        }
    }

    private function outputBuffer($buffer) {
        return $buffer;
    }

}