<?php

namespace lib;

use database\Mysql;
use Lib\Log;

class Factory {
    private Mysql $mysql;
    private Log $logger;
    public function createConnection(): Mysql {
        if (isset($this->mysql)) return $this->mysql;
        $this->mysql = new Mysql($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);
        return $this->mysql;
    }
    public function checkDBConnection() {
        if (isset($this->mysql)) return $this->mysql;
        else return null;
    }
    public function getLogger(): Log {
        if (isset($this->logger)) return $this->logger;
        $this->logger = new Log();
        return $this->logger;
    }
}
