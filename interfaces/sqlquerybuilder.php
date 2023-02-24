<?php

namespace interfaces;

interface SQLQueryBuilder {
    public function select(array $fields): SQLQueryBuilder;
    public function from(string $table, string $alias): SQLQueryBuilder;
    public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder;
    public function limit(int $start, int $offset): SQLQueryBuilder;
    public function update(array $object): SQLQueryBuilder;
    public function leftJoin(string $table, string $alias): SQLQueryBuilder;
    public function getSQL(): string;
}
