<?php

use Lib\Router;
use Lib\Request;
use Lib\Response;
use controller\PessoaController;

Router::get('/', [new PessoaController(), 'indexAction']);
Router::get('/api/buscaPessoas', [new PessoaController(), 'buscaPessoas']);

// Router::post('/post', function (Request $req, Response $res) {
//     $post = Posts::add($req->getJSON());
//     $res->status(201)->toJSON($post);
// });
// Router::get('/post/([0-9]*)', function (Request $req, Response $res) {
//     $post = Posts::findById($req->params[0]);
//     if ($post) {
//         $res->toJSON($post);
//     } else {
//         $res->status(404)->toJSON(['error' => "Not Found"]);
//     }
// });
// $route = explode("/", str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]))[1];
// if ($route == '') require_once __DIR__ . '/view/index.phtml';
// else if ($route == 'api') require_once __DIR__ . '/api.php';
// else if ($route == 'pessoa') require_once __DIR__ . '/view/edit.phtml';
// else if ($route == 'suportes-balanceados') require_once __DIR__ . '/view/suportes-balanceados.phtml';
// else require_once __DIR__ . '/view/404.phtml';