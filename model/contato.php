<?php

namespace model;

use Connector;
use interfaces\{iModel};
use model\Model;

class Contato extends Model implements iModel {
    public ?int $id;
    public string $nome;
    public function __construct(
        protected Connector $mysql,
        array $model = null
    ) {
        if ($model) {
            $this->id   = $model['id'] ?: null;
            $this->nome = $model['nome'] ?: throw new \Exception("Nome da pessoa obrigatório");
        }
    }
    public function getTableName(): string {
        return "pessoa";
    }
    public function getObject(): array {
        return [
            "id"   => $this->id,
            "nome" => $this->nome
        ];
    }
    public function getPessoas(): array {
        return $this->mysql->DBFind("SELECT A.*, (select count(id) from contato WHERE id_pessoa = A.id) qnt_cnt FROM pessoa A");
    }
}