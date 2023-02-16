<?php

namespace controller;

use model\Pessoa;
use controller\Controller;
use database\MysqlFactory;
use Lib\{Request, Response};

class PessoaController extends Controller {
    function __construct(
        private MysqlFactory $mysql
    ) {
    }
    public function indexAction(Request $req, Response $res) {
        $res->status(200)->renderView('index.phtml');
    }
    function buscaPessoasAction(Request $req, Response $res) {
        $mysql = $this->mysql->createConnection();
        $pessoaModel = new Pessoa($mysql);
        $rows = $pessoaModel->getPessoas()['rows'];
        $res->status(200)->toJSON($rows);
    }
    public function buscaPessoaAction(Request $req, Response $res) {
        $mysql = $this->mysql->createConnection();
        $pessoa = $mysql->DBFind("SELECT A.* FROM pessoa A WHERE A.id = ?", [$req->params["id"]])['rows'][0];
        $pessoa['contatos'] = $mysql->DBFind("SELECT * FROM contato WHERE id_pessoa = {$pessoa['id']}")['rows'];
        $res->status(200)->toJSON($pessoa);
    }
    public function editAction(Request $req, Response $res) {
        $res->status(200)->renderView('edit.phtml');
    }
    public function submitPessoaAction(Request $req, Response $res) {
        $mysql = $this->mysql->createConnection();
        $pessoa = new Pessoa($mysql);
        $pessoa->setObject($req->getBody());
        $pessoa->save();       
    }
}
