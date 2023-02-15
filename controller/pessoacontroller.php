<?php

namespace controller;

use model\Pessoa;
use controller\Controller;
use database\MysqlFactory;
use Lib\{Request, Response};

class PessoaController extends Controller {
    private MysqlFactory $mysql;
    function __construct() {
        $this->mysql = new MysqlFactory();
    }
    # [Route("/api/buscaPessoas", methods: ["GET"])]
    public function indexAction(Request $req, Response $res) {
        require_once __DIR__ . '/../view/index.phtml';
    }
    // #[Route("/api/buscaPessoas", methods: ["GET"])]
    function buscaPessoas(Request $req, Response $res) {
        $mysql = $this->mysql->createConnection();
        $pessoaModel = new Pessoa($mysql);
        $rows = $pessoaModel->getPessoas()['rows'];
        $res->status(200)->toJSON($rows);
    }
    // #[Route("/api/buscaPessoa?id=1", methods: ["GET"])]
    public function buscaPessoa(Request $req, Response $res) {
        $mysql = $this->mysql->createConnection();
        $pessoa = $mysql->DBFind("SELECT A.* FROM pessoa A WHERE A.id = ?", [6])['rows'][0];
        $pessoa['contatos'] = $mysql->DBFind("SELECT * FROM contato WHERE id_pessoa = {$pessoa['id']}")['rows'];
        $res->status(200)->toJSON($pessoa);
    }
}
