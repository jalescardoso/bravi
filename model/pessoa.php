<?php

namespace model;

use database\Mysql;
use interfaces\{iModel};
use model\Model;
use lib\Factory;
class Pessoa extends Model implements iModel {
    protected ?int $id;
    private string $nome;
    public function __construct(
        public Factory $factory
    ) {
        parent::__construct($factory);
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
    public function setObject(array $data): void {
        $this->id   = $data['id'] ?: null;
        $this->nome = $data['nome'] ?: throw new \Exception("Nome da pessoa obrigatório");
    }

    public function getPessoas(): array {
        return $this->select(['*'])->all();
    }
    
}
