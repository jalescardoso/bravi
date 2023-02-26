<?php

namespace controller;

use model\Pessoa;
use Lib\{Request, Response, Factory};
use model\Contato;

class PessoaController {
    function __construct(
        public Factory $factory
    ) {
    }
    public function indexAction(Request $req, Response $res) {
        $res->status(200)->renderView('index.phtml');
    }
    function buscaPessoasAction(Request $req, Response $res) {
        $pessoa = new Pessoa($this->factory);
        $result = $pessoa->select(['*'])->all()->rows;
        $res->status(200)->toJSON($result);
    }
    public function buscaPessoaAction(Request $req, Response $res) {
        $pessoa = new Pessoa($this->factory);
        $pessoa = $pessoa->select(['*'])->where('id', (int)$req->params["id"])->all()->rows[0];
        $contato = new Contato($this->factory);
        $pessoa['contatos'] = $contato->select(['*'])->where('id_pessoa', $pessoa['id'])->all()->rows;
        $res->status(200)->toJSON($pessoa);
    }
    public function editAction(Request $req, Response $res) {
        $res->status(200)->renderView('edit.phtml');
    }
    public function submitPessoaAction(Request $req, Response $res) {
        $pessoa = new Pessoa($this->factory);
        $pessoa->setObject($req->getBody());
        $id = $pessoa->save();
        $res->status(200)->toJSON(['id' => $id]);
    }
    public function deleteAction(Request $req, Response $res) {
        $pessoa = new Pessoa($this->factory);
        $pessoa->delete($req->params["id"]);
    }
}
