<?php

namespace model;

use database\Mysql;
use interfaces\{iModel, sqlquerybuilder};
use lib\Factory;
use stdClass;

abstract class Model implements iModel, sqlquerybuilder {
    protected ?int $id;
    protected $query;
    public function __construct(
        public Factory $factory
    ) {
    }
    public function save(): int {
        $conn = $this->factory->getConn();
        if ((bool)$this->id) {
            $conn->DBUpdate($this->getTableName(), $this->getObject(), " where id = ?", [$this->id]);
        } else {
            $this->id = $conn->DBInsert($this->getTableName(), $this->getObject());
        }
        return $this->id;
    }
    public function delete($id) {
        $conn = $this->factory->getConn();
        $conn->DBDelete($this->getTableName(), "WHERE id = ?", [$id]);
    }
    protected function reset(): void {
        $this->query = new \stdClass();
        $this->query->params = [];
        $this->query->join = [];
    }
    public function select(array $columns = ['*']): sqlquerybuilder {
        $this->reset();
        $this->query->base = "SELECT " . implode(", ", $columns) . " FROM " . $this->getTableName();
        $this->query->type = 'select';
        return $this;
    }
    public function where(string $column, $value, string $operator = '='): sqlquerybuilder {
        if (!in_array($this->query->type, ['select', 'update', 'delete'])) {
            throw new \Exception("WHERE can only be added to SELECT, UPDATE OR DELETE");
        }
        $this->query->where[] = "$column $operator ?";
        $this->query->params[] = $value;
        return $this;
    }
    public function getSQL(): string {
        $query = $this->query;
        $sql = $query->base;
        if (!empty($query->where)) {
            $sql .= " WHERE " . implode(' AND ', $query->where);
        }
        if (isset($query->limit)) {
            $sql .= $query->limit;
        }
        $sql .= ";";
        return $sql;
    }
    public function all(): stdClass {
        $return = new \stdClass();
        $return->query = true;
        $sql = $this->getSQL();
        $conn = $this->factory->getConn();
        $stmt = $conn->DBQueryParams($sql, ($this->query->params ?: []));
        $result = $stmt->get_result();
        $return->num_rows = $result->num_rows;
        $return->rows = $result->fetch_all(MYSQLI_ASSOC);
        return $return;
    }
    public function limit(int $start, int $offset): sqlquerybuilder {
        if (!in_array($this->query->type, ['select'])) {
            throw new \Exception("LIMIT can only be added to SELECT");
        }
        $this->query->limit = " LIMIT " . $start . ", " . $offset;
        return $this;
        if (!in_array($this->query->type, ['select'])) {
            throw new \Exception("LIMIT can only be added to SELECT");
        }
        $this->query->limit = " LIMIT " . $start . ", " . $offset;
        return $this;
    }
}
