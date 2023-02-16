<?php

namespace model;

use database\Mysql;
use interfaces\{iModel};

abstract class Model implements iModel {
    private ?int $id;
    public function __construct(
        protected Mysql $mysql,
        array $model = null
    ) {
    }
    public function save(): int {
        if ((bool)$this->id) {
            $this->mysql->DBUpdate($this->getTableName(), $this->getObject(), " where id = ?", [$this->id]);
        } else {
            $this->id = $this->mysql->DBInsert($this->getTableName(), $this->getObject());
        }
        return $this->id;
    }
}
