<?php
return [
    "app\site\logging\StdOutLoggerBuilder" => [
        "channel" => "out",
        "log_level" => 100,
    ],
    "app\site\logging\StdErrLoggerBuilder" => [
        "channel" => "err",
        "log_level" => 100,
    ],
    "app\site\logging\DefaultLoggerBuilder" => [
        "channel" => "app",
        "log_file" => dirname(__FILE__, 2)
            . DIRECTORY_SEPARATOR . "logs"
            . DIRECTORY_SEPARATOR . "app_log.log",
        "err_file" => dirname(__FILE__, 2)
            . DIRECTORY_SEPARATOR . "logs"
            . DIRECTORY_SEPARATOR . "err_log.log",
        "log_level" => 100,
        "err_level" => 400,
        "max_log_files" => 5,
        "max_err_files" => 5,
    ],
    "efg" => "ezel",
];
