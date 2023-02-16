<?php

use Lib\Router;

Router::get('/', ['controller\PessoaController', 'indexAction']);
Router::get('/pessoa', ['controller\PessoaController', 'editAction']);
Router::get('/api/buscaPessoas', ['controller\PessoaController', 'buscaPessoasAction']);
Router::get('/api/buscaPessoa', ['controller\PessoaController', 'buscaPessoaAction']);
Router::post('/api/submitPessoa', ['controller\PessoaController', 'submitPessoaAction']);
// $route = explode("/", str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]))[1];
// if ($route == '') require_once __DIR__ . '/view/index.phtml';
// else if ($route == 'api') require_once __DIR__ . '/api.php';
// else if ($route == 'pessoa') require_once __DIR__ . '/view/edit.phtml';
// else if ($route == 'suportes-balanceados') require_once __DIR__ . '/view/suportes-balanceados.phtml';
// else require_once __DIR__ . '/view/404.phtml';