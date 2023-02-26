<?php

namespace model;

use database\Mysql;
use interfaces\{iModel};
use model\Model;
use lib\Factory;
class Contato extends Model implements iModel {
    protected ?int $id;
    private int $id_pessoa;
    private string $descricao;
    private string $tipo;
    private string $valor;
    public function __construct(
        public Factory $factory
    ) {
        parent::__construct($factory);
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
            "tipo" => $this->tipo,
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
