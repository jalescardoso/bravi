<?php

namespace Lib;

class Response {
    private $status = 200;
    public function status(int $code) {
        $this->status = $code;
        return $this;
    }
    public function toJSON($data = []) {
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function renderView($view) {
        http_response_code($this->status);
        header('Content-type: text/html');
        require_once $_ENV['VIEW_FOLDER'] . $view;
    }

    public function notFound() {
        if ($_SERVER["HTTP_SEC_FETCH_MODE"] == "navigate") $this->status(404)->renderView("404.phtml");
        else $this->status(404)->toJSON(["type" => "erro", "message" => "Route not found"]);
    }
}
