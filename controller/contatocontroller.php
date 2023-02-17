<?php

namespace controller;

use model\Contato;
use Lib\{Request, Response, Factory};

class ContatoController {
    function __construct(
        private Factory $factory
    ) {
    }
    public function submitContatoAction(Request $req, Response $res) {
        $mysql = $this->factory->createConnection();
        $pessoa = new Contato($mysql);
        $pessoa->setObject($req->getBody());
        $id = $pessoa->save();
        $res->status(200)->toJSON(['id' => $id]);
    }
    public function deleteAction(Request $req, Response $res) {
        $mysql = $this->factory->createConnection();
        $pessoa = new Contato($mysql);
        $pessoa->delete($req->params["id"]);
    }
}
