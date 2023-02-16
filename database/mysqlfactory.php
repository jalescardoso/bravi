<?php

namespace database;

use database\Mysql;

class MysqlFactory {
    private Mysql $mysql;
    public function createConnection(): Mysql {
        if (isset($this->mysql)) return $this->mysql;
        $this->mysql = new Mysql($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);
        return $this->mysql;
    }
 
    public function getConnection() {
        if (isset($this->mysql)) return $this->mysql;
        else return null;
    }
}
