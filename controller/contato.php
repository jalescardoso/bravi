<?php
namespace controller;
use controller\Controller;
use database\MysqlFactory;

class Contato extends Controller {
    private MysqlFactory $mysql;
    function __construct() {
        $this->mysql = new MysqlFactory();
    }

    
}