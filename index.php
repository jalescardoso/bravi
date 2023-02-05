<?php
require_once __DIR__ . '/Loader.php';
error_reporting(0);
header('Content-Type: text/html; charset=utf-8', true);
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
$route = explode("/", str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]))[1];
if ($route == '') require_once __DIR__ . '/table.phtml';
else if ($route == 'api') require_once __DIR__ . '/api.php';
else if ($route == 'pessoa') require_once __DIR__ . '/form.phtml';
else if ($route == 'suportes-balanceados') require_once __DIR__ . '/suportes-balanceados.phtml';
else require_once __DIR__ . '/404.phtml';