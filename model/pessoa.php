<?php

namespace model;

use database\Mysql;
use interfaces\{iModel};
use model\Model;

class Pessoa extends Model implements iModel {
    public ?int $id;
    public string $nome;
    public function __construct(
        protected Mysql $mysql
    ) {
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
    public function setObject(array $data): void {
        $this->id   = $data['id'] ?: null;
        $this->nome = $data['nome'] ?: throw new \Exception("Nome da pessoa obrigatório");
    }
}
