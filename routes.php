<?php

use Lib\{Router, Response};

Router::get('/', ['controller\PessoaController', 'indexAction']);
Router::get('/pessoa', ['controller\PessoaController', 'editAction']);
Router::get('/api/buscaPessoas', ['controller\PessoaController', 'buscaPessoasAction']);
Router::get('/api/buscaPessoa', ['controller\PessoaController', 'buscaPessoaAction']);
Router::post('/api/submitPessoa', ['controller\PessoaController', 'submitPessoaAction']);
Router::post('/api/submitContato', ['controller\ContatoController', 'submitContatoAction']);
Router::delete('/api/deleteContato', ['controller\ContatoController', 'deleteAction']);
Router::delete('/api/deletePessoa', ['controller\PessoaController', 'deleteAction']);
Router::get('/suportes-balanceados', ['controller\SuportesController', 'editAction']);
Router::post('/api/suporteBalanceados', ['controller\SuportesController', 'suporteBalanceadosAction']);

if (Router::$notFound) {
    $res = new Response();
    $res->notFound();
}
