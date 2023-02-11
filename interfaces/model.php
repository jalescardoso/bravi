<?php 
namespace interfaces;
use Connector;
interface Model {
    public function getTableName() : string;
    public function getObject() : array;
    public function save() : int;
}