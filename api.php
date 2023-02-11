<?php

use Connector;
use modelos\{Pessoa};

class Api {
    private Connector $mysql;
    function __construct() {
        $this->mysql = new Connector();
    }
    // #[Route("/api/buscaPessoas", methods: ["GET"])]
    function buscaPessoasAction() {
        $pessoa = new Pessoa($this->mysql);
        $rows = $pessoa->getPessoas()['rows'];
        return json_encode($rows);
    }
    // #[Route("/api/buscaPessoa?id=1", methods: ["GET"])]
    function buscaPessoaAction() {
        $pessoa = $this->mysql->DBFind("SELECT A.* FROM pessoa A WHERE A.id = ?", [$_GET['id']])['rows'][0];
        $pessoa['contatos'] = $this->mysql->DBFind("SELECT * FROM contato WHERE id_pessoa = {$pessoa['id']}")['rows'];
        return json_encode($pessoa);
    }
    // #[Route("/api/submitPessoa", methods: ["POST"])]
    function submitPessoaAction() {
        $pessoa = new Pessoa($this->mysql, $_POST);
        $id = $pessoa->save($pessoa);
        return json_encode(["id" => $id]);
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
    // #[Route("/api/suporteBalanceados", methods: ["POST"])]
    function suporteBalanceadosAction() {
        $string_param = $_POST['string'];
        $checkFunction = function ($string_param) {
            $pairs = function ($x) {
                return match ($x) {
                    "(" => ")",
                    "[" => "]",
                    "{" => "}",
                    default => false
                };
            };
            $arr = str_split($string_param);
            foreach ($arr as $i => &$char) {
                if (in_array($char, ['{', '[', '('])) {
                    $new_string = implode($arr);
                    $suporte_aberto_indice = $i;
                    $str_para_frente = substr($new_string, $suporte_aberto_indice + 1, strlen($new_string));
                    $check_suporte_fechado = strpos($str_para_frente, $pairs($char));
                    if ($check_suporte_fechado === false) {
                        throw new \Exception("não fechou o suporte $char");
                    }
                    $suporte_fechado_indice = strpos($new_string, $pairs($char));
                    $arr[$suporte_aberto_indice] = 0;
                    $arr[$suporte_fechado_indice] = 0;
                }
            }
            $new_string = implode($arr);
            if (strpos($new_string, ')') !== false) throw new Exception("Suporte não ) foi aberto");
            if (strpos($new_string, ']') !== false) throw new Exception("Suporte não ] foi aberto");
            if (strpos($new_string, '}') !== false) throw new Exception("Suporte não } foi aberto");
        };
        try {
            $checkFunction($string_param);
            return json_encode(['valido' => true, 'mensagem' => $string_param]);
        } catch (\Throwable $e) {
            return json_encode(['valido' => false, 'mensagem' => $e->getMessage()]);
        }
    }
    public function setAction() {
        try {
            $this->mysql->DBConnect();
            $json = file_get_contents('php://input');
            $_POST = json_decode($json, true);
            $newAction = explode("?", $this->getParameter('api'))[0] . "Action";
            $echo = null;
            if (method_exists($this, $newAction)) $echo = $this->$newAction();
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
