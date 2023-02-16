<?php

namespace database;

use mysqli, mysqli_result, mysqli_stmt;

class Mysql {
    private mysqli $mysqli;
    private bool $inTransaction = false;
    public function __construct(
        private string $DB_HOST,
        private string $DB_USERNAME,
        private string $DB_PASSWORD,
        private string $DB_NAME,
        private string $DB_PORT,
    ) {
        $this->mysqli = new mysqli($this->DB_HOST, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_NAME, $this->DB_PORT);
        if ($this->mysqli->connect_errno) throw new \Exception($this->mysqli->connect_error);
        mysqli_set_charset($this->mysqli, "utf8mb4");
    }
    public function DBInsert(string $table, array $data, array $params = []) {
        $this->startTransaction();
        $data = (array)$data;
        unset($data['delete_at']);
        unset($data['id']);
        $fields = $values = $object = [];
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $values[] = "?";
            $object[] = $value;
        }
        $sql = "INSERT INTO " . $table . " (" . implode(',', $fields) . ") VALUES(" . implode(',', $values) . ")";
        $refValues = array_merge($object, $params);
        $this->DBQueryParams($sql, $refValues);
        return $this->mysqli->insert_id;
    }
    public function DBUpdate(string $table, array $data, string $where, array $params = []) {
        $this->startTransaction();
        $fields = $object = [];
        $data = (array)$data;
        unset($data['id']);
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $object[] = $value;
        }
        $sql = "UPDATE " . $table . " SET  " . implode(' = ?, ', $fields) . " = ? $where";
        $refValues = array_merge($object, $params);
        $this->DBQueryParams($sql, $refValues);
    }
    public function DBDelete($table, $where, $params = []) {
        $sql = "DELETE FROM " . $table . " " . $where;
        $this->DBQueryParams($sql, $params);
    }
    private function param_types(array $arr): string {
        $types = '';
        foreach ($arr as $a) {
            // i	corresponde a uma variável de tipo inteiro 
            // d	corresponde a uma variável de tipo double 
            // s	corresponde a uma variável de tipo string 
            // b	corresponde a uma variável que contém dados para um blob e enviará em pacotes 
            if (is_string($a)) $types .= 's';
            else if (is_int($a)) $types .= 'i';
            else if (is_double($a)) $types .= 'd';
            else $types .= 's';
        }
        return $types;
    }
    public function DBFind(string $queryin, $params = []): array {
        $return = new \stdClass();
        $return->query = true;
        $stmt = $this->DBQueryParams($queryin, $params);
        $result = $stmt->get_result();
        $return->num_rows = $result->num_rows;
        $return->rows = $result->fetch_all(MYSQLI_ASSOC);
        return (array)$return;
    }
    public function DBQuery($query): mysqli_result {
        $response = $this->mysqli->query($query);
        if (!(bool)$response) throw new \Exception($this->mysqli->error);
        return $response;
    }
    public function DBQueryParams($queryin, $params = []): mysqli_stmt {
        $stmt = $this->mysqli->prepare($queryin);
        if (!(bool)$stmt) throw new \Exception($this->mysqli->error);
        if (count($params)) {
            $stmt->bind_param($this->param_types($params), ...array_values($params));
        }
        $stmt->execute();
        if ((bool)$stmt->error) throw new \Exception($stmt->error);
        return $stmt;
    }
    private function startTransaction() {
        if ($this->inTransaction) return;
        $this->mysqli->autocommit(FALSE);
        $this->inTransaction = true;
    }
    public  function commitAndClose() {
        $this->mysqli->commit();
        $this->mysqli->close();
    }
    public  function rollbackAndClose() {
        $this->mysqli->rollback();
        $this->mysqli->close();
    }
}
