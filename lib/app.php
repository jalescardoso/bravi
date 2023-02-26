<?php

namespace Lib;

use Lib\Factory;

class App {
    private Factory $factory;
    function __construct() {
        $this->factory = new Factory();
    }
    public function handle($cb, Request $req, Response $res) {
        try {
            $a = new $cb[0]($this->factory);
            $a->{$cb[1]}($req, $res);
            $conn = $this->factory->checkDBConnection();
            if (isset($conn)) $conn->commitAndClose();
        } catch (\Throwable $e) {
            $conn = $this->factory->checkDBConnection();
            if (isset($conn)) $conn->rollbackAndClose();
            $res->status(500)->toJSON([
                'status' => 'erro',
                'message' => $e->getMessage()
            ]);
            $log = $this->factory->getLogger();
            $log->error($e->getMessage(), $e->getTrace());
        }
    }
}
