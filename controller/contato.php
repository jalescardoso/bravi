<?php

namespace controller;

use controller\Controller;
use database\MysqlFactory;

class Contato extends Controller {
    function __construct(
        private MysqlFactory $mysql
    ) {
    }
}
