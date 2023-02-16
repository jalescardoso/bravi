<?php

namespace controller;

use controller\Controller;
use database\MysqlFactory;
use model\Contato;
use Lib\{Request, Response};

class ContatoController extends Controller {
    function __construct(
        private MysqlFactory $mysql
    ) {
    }
    public function submitContatoAction(Request $req, Response $res) {
        $mysql = $this->mysql->createConnection();
        $pessoa = new Contato($mysql);
        $pessoa->setObject($req->getBody());
        $pessoa->save();       
    }
}
