<?php
require_once __DIR__ . '/autoload.php';

error_reporting(0);
header('Content-Type: text/html; charset=utf-8', true);
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/routes.php';

