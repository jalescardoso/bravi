<?php

namespace modelos;

use Connector;
use interfaces\{Model};

class Contato implements Model {
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
    public function save(): int {
        if ((bool)$this->id) {
            $this->mysql->DBUpdate($this->getTableName(), $this->getObject(), " where id = ?", [$this->id]);
        } else {
            $this->id = $this->mysql->DBInsert($this->getTableName(), $this->getObject());
        }
        return $this->id;
    }
    public function getPessoas(): array {
        return $this->mysql->DBFind("SELECT A.*, (select count(id) from contato WHERE id_pessoa = A.id) qnt_cnt FROM pessoa A");
    }
}
