<?php
include_once 'baseapi.php';

use Connector;

class Api {
    private Connector $mysql;
    function __construct() {
        $this->mysql = new Connector();
    }
    private function indexAction() {
    }
    // #[Route("/api/buscaPessoas", methods: ["GET"])]
    function buscaPessoasAction() {
        $pessoas = $this->mysql->DBFind("SELECT A.*, (select count(id) from contato WHERE id_pessoa = A.id) qnt_cnt FROM pessoa A")->rows;
        return json_encode($pessoas);
    }
    // #[Route("/api/buscaPessoa?id=1", methods: ["GET"])]
    function buscaPessoaAction() {
        $pessoa = $this->mysql->DBFind("SELECT A.* FROM pessoa A WHERE A.id = ?", [$_GET['id']])->rows[0];
        $pessoa['contatos'] = $this->mysql->DBFind("SELECT * FROM contato WHERE id_pessoa = {$pessoa['id']}")->rows;
        return json_encode($pessoa);
    }
    // #[Route("/api/submitPessoa", methods: ["POST"])]
    function submitPessoaAction() {
        if ((bool)$_POST['id']) {
            $this->mysql->DBUpdate("pessoa", ['nome' => $_POST['nome']], "WHERE id = ?", [$_POST['id']]);
        } else {
            $_POST['id'] = $this->mysql->DBInsert("pessoa", ['nome' => $_POST['nome']]);
        }
        return json_encode(['id' => $_POST['id']]);
    }
    // #[Route("/api/submitContato", methods: ["POST"])]
    function submitContatoAction() {
        $contato = [
            'id_pessoa' =>  $_POST['id_pessoa'],
            'tipo' =>       $_POST['tipo'],
            'descricao' =>  $_POST['descricao'],
            'valor' =>      $_POST['valor'],
        ];
        if ((bool)$_POST['id']) {
            $this->mysql->DBUpdate("contato", $contato, "WHERE id = ?", [$_POST['id']]);
        } else {
            $this->mysql->DBInsert("contato", $contato);
        }
    }
    // #[Route("/api/deleteContato?id=1", methods: ["DELETE"])]
    function deleteContatoAction() {
        $this->mysql->DBDelete("contato", "WHERE id = ?", [$_GET['id']]);
    }
    // #[Route("/api/deletePessoa?id=1", methods: ["DELETE"])]
    function deletePessoaAction() {
        $this->mysql->DBDelete("pessoa", "WHERE id = ?", [$_GET['id']]);
    }
    public function setAction() {
        try {
            $this->mysql->DBConnect();
            $json = file_get_contents('php://input');
            $_POST = json_decode($json, true);
            $newAction = explode("?", $this->getParameter('api'))[0] . "Action";
            $echo = null;
            if (method_exists($this, $newAction)) $echo = $this->$newAction();
            else if ($newAction == "Action" && method_exists($this, 'indexAction')) $echo = $this->indexAction();
            else header("HTTP/1.0 404 Not Found");
            $this->mysql->mysqli->commit();
            if ((bool)$echo) echo $echo;
        } catch (\Throwable $e) {
            $this->mysql->mysqli->rollback();
            header("HTTP/1.0 500 Internal Server Error");
        } finally {
            $this->mysql->mysqli->close();
        }
    }
    private function getParameter($parametro) {
        $uri = urldecode($_SERVER["REQUEST_URI"]);
        $uri = explode("?", $uri)[0];
        $uri = explode("/", $uri);
        if (!in_array($parametro, $uri)) return null;
        $i_param = array_search($parametro, $uri) + 1;
        return $uri[$i_param];
    }
}
$class = new Api();
$class->setAction();
