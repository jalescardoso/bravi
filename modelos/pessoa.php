<?php

namespace modelos;

use Connector;
use interfaces\{Model};

class Pessoa implements Model {
    public ?int $id;
    public int $id_pessoa;
    public string $descricao;
    public string $tipo;
    public float $valor;
    public function __construct(
        protected Connector $mysql,
        array $model = null
    ) {
        if ($model) {
            $this->id   = $model['id'] ?: null;
            $this->id_pessoa = $model['id_pessoa'] ?: throw new \Exception("Pessoa relacionada ao contato obrigatório");
            $this->descricao = $model['descricao'] ?: throw new \Exception("Descrição obrigatório");
            $this->tipo = $model['tipo'] ?: throw new \Exception("Tipo obrigatório");
            $this->valor = $model['valor'] ?: throw new \Exception("Valor obrigatório");
        }
    }
    public function getTableName(): string {
        return "contato";
    }
    public function getObject(): array {
        return [
            "id"   => $this->id,
            "id_pessoa" => $this->id_pessoa,
            "descricao" => $this->descricao,
            "valor" => $this->valor,
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
