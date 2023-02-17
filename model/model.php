<?php

namespace model;

use database\Mysql;
use interfaces\{iModel};

abstract class Model implements iModel {
    protected ?int $id;
    public function __construct(
        protected Mysql $mysql
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
    public function delete($id) {
        $this->mysql->DBDelete($this->getTableName(), "WHERE id = ?", [$id]);
    }
}
