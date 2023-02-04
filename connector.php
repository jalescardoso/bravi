<?php

class Connector {
    public $mysqli;
    public function __construct() {
        $la = 'la';
    }
    public function DBConnect() {
        $_ENV = [
            'DB_HOST' =>      'localhost',
            'DB_USERNAME' =>  'root',
            'DB_PASSWORD' =>  'root',
            'DB_PORT' =>      '3306',
            'DB_NAME' =>      'bravi',
        ];
        $this->mysqli = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);
        if ($this->mysqli->connect_errno) throw new \Exception($this->mysqli->connect_error);
        # start transaction
        $this->mysqli->autocommit(false);
        mysqli_set_charset($this->mysqli, "utf8mb4");
    }
    public function DBInsert($table, $data, $params = []) {
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
    public function DBUpdate($table, $data, $where, $params = []) {
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
    private function param_types($arr) {
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
    public function DBFind($queryin, $params = []) {
        if (!(bool)$queryin) return;
        $return = new \stdClass();
        $return->query = true;
        $stmt = $this->DBQueryParams($queryin, $params);
        $result = $stmt->get_result();
        $return->num_rows = $result->num_rows;
        $return->rows = $result->fetch_all(MYSQLI_ASSOC);
        if (str_contains(strtoupper($queryin), 'SQL_CALC_FOUND_ROWS'))
            $return->total = (int)$this->mysqli->query("SELECT FOUND_ROWS() count")->fetch_assoc()['count'];
        return $return;
    }
    public function DBQuery($query) {
        $response = $this->mysqli->query($query);
        if (!(bool)$response) throw new \Exception($this->mysqli->error);
        return $response;
    }
    public function DBQueryParams($queryin, $params = []) {
        $stmt = $this->mysqli->prepare($queryin);
        if (!(bool)$stmt) throw new \Exception($this->mysqli->error);
        if (count($params)) {
            $stmt->bind_param($this->param_types($params), ...array_values($params));
        }
        $stmt->execute();
        if ((bool)$stmt->error) throw new \Exception($stmt->error);
        return $stmt;
    }
}
