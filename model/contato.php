<?php

namespace model;

use Connector;
use interfaces\{iModel};
use model\Model;

class Contato extends Model implements iModel {
    public ?int $id;
    public int $id_pessoa;
    public string $descricao;
    public string $tipo;
    public float $valor;
    public function __construct(
        protected Connector $mysql,
    ) {
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
    public function setObject(array $data): void {
        $this->id   = $data['id'] ?: null;
        $this->id_pessoa = $data['id_pessoa'] ?: throw new \Exception("Pessoa relacionada ao contato obrigatório");
        $this->descricao = $data['descricao'] ?: throw new \Exception("Descrição obrigatório");
        $this->tipo = $data['tipo'] ?: throw new \Exception("Tipo obrigatório");
        $this->valor = $data['valor'] ?: throw new \Exception("Valor obrigatório");
    }
}
