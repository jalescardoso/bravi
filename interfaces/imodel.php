<?php 
namespace interfaces;
use Connector;
interface iModel {
    public function getTableName() : string;
    public function getObject() : array;
    public function setObject(array $data) : void;
}