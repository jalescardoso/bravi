<?php

namespace Lib;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\{StreamHandler, FirePHPHandler, ErrorLogHandler, SyslogHandler};

class AppLogger {
    private static $loggers = [];
    public function __construct() {
        $stream = new StreamHandler($_ENV['LOG_PATH'] . '/my_app.log', Level::Debug);
        $firephp = new FirePHPHandler();
        $erroLog = new ErrorLogHandler();
        // $sysLog = new SyslogHandler();
        $logger = new Logger('main');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);
        $logger->pushHandler($erroLog);
        // $logger->pushHandler($sysLog);
    }
}
