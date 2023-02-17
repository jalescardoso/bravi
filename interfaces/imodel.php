<?php 
namespace interfaces;
interface iModel {
    public function getTableName() : string;
    public function getObject() : array;
    public function setObject(array $data) : void;
}