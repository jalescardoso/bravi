<?php

namespace interfaces;
use stdClass;

interface sqlquerybuilder {
    public function select(array $columns = ['*']): sqlquerybuilder;
    public function where(string $fiecolumnld, $value, string $operator = '='): sqlquerybuilder;
    public function limit(int $start, int $offset): sqlquerybuilder;
    public function getSQL(): string;
    public function all(): stdClass;
}
