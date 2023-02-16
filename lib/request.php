<?php

namespace Lib;

class Request {
    public $params;
    public $reqMethod;
    public $contentType;
    public function __construct($params = []) {
        $this->params = $params;
        $this->reqMethod = trim($_SERVER['REQUEST_METHOD']);
        $this->contentType = !empty($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    }
    public function getBody() {
        if ($this->reqMethod !== 'POST') {
            return '';
        }
        if ((bool)$_POST) return $_POST;
        else return $this->getJSON();
    }
    public function getJSON() {
        if ($this->reqMethod !== 'POST') {
            return [];
        }
        if (!str_contains($this->contentType, 'application/json')) {
            return [];
        }
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
        return $decoded;
    }
}
