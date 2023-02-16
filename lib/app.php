<?php

namespace Lib;
use database\MysqlFactory;

class App {
    private MysqlFactory $mysql;
    function __construct() {
        $this->mysql = new MysqlFactory();
    }
    public function handler($cb, Request $req, Response $res) {
        try {
            $a = new $cb[0]($this->mysql);
            $a->{$cb[1]}($req, $res);
            $conn = $this->mysql->getConnection();
            if(isset($conn)) $conn->commitAndClose();
        } catch (\Throwable $e) {
            $conn = $this->mysql->getConnection();
            if(isset($conn)) $conn->rollbackAndClose();
            $res->status(500)->toJSON([
                'status' => 'erro',
                'message' => $e->getMessage()
            ]);
        }
    }
}
