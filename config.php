<?php
if (!(bool)$_ENV) $_ENV = getenv();
$_ENV['VIEW_FOLDER'] =   __DIR__ . '/view/';
$_ENV['LOG_PATH']    =   __DIR__ ;