<?php

namespace controller;

use controller\Controller;

class Pessoa extends Controller {


    # [Route("/api/buscaPessoas", methods: ["GET"])]
    public function index() {
        $pessoa = new Pessoa($this->mysql);
        $rows = $pessoa->getPessoas()['rows'];
        return json_encode($rows);
    }
}
