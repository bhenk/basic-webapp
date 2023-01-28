<?php
return [
    "app\site\logging\build\OutLoggerBuilder" => [
        "channel" => "out",
        "log_level" => 100,
    ],
    "app\site\logging\build\ErrLoggerBuilder" => [
        "channel" => "err",
        "log_level" => 100,
    ],
    "app\site\logging\build\DefaultLoggerBuilder" => [
        "channel" => "app",
        "log_file" => dirname(__FILE__, 2)
            . DIRECTORY_SEPARATOR . "logs"
            . DIRECTORY_SEPARATOR . "app"
            . DIRECTORY_SEPARATOR . "app_log.log",
        "err_file" => dirname(__FILE__, 2)
            . DIRECTORY_SEPARATOR . "logs"
            . DIRECTORY_SEPARATOR . "err"
            . DIRECTORY_SEPARATOR . "err_log.log",
        "log_level" => 100,
        "err_level" => 400,
        "max_log_files" => 5,
        "max_err_files" => 5,
    ],
    "efg" => "ezel",
];
