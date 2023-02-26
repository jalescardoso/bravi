<?php

namespace controller;

use model\Contato;
use Lib\{Request, Response, Factory};

class ContatoController {
    function __construct(
        public Factory $factory
    ) {
    }
    public function submitContatoAction(Request $req, Response $res) {
        $mysql = $this->factory->getConn();
        $pessoa = new Contato($this->factory);
        $pessoa->setObject($req->getBody());
        $id = $pessoa->save();
        $res->status(200)->toJSON(['id' => $id]);
    }
    public function deleteAction(Request $req, Response $res) {
        $mysql = $this->factory->getConn();
        $pessoa = new Contato($this->factory);
        $pessoa->delete($req->params["id"]);
    }
}
