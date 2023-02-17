<?php

namespace Lib;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\{StreamHandler, FirePHPHandler, ErrorLogHandler, SyslogHandler};

class Log extends Logger {

    public function __construct(string $name = 'main') {
        parent::__construct($name);
        $stream = new StreamHandler($_ENV['LOG_PATH'] . '/app.log', Level::Debug);
        $firephp = new FirePHPHandler();
        $erroLog = new ErrorLogHandler();
        $this->pushHandler($stream);
        $this->pushHandler($firephp);
        $this->pushHandler($erroLog);
    }
}
