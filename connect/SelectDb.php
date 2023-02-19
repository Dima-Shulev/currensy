<?php

namespace Connect;

use PDO;
use Connect\InsertDb;

class SelectDb extends PDOConfig
{
    public $id;
    public $column;

    public function __construct($table){
        parent::__construct();
        $this->table = $table;
    }

    public function getSelectFetch($sql,$arr){
        $result = $this->executeDb($sql,$arr)->fetch(PDO::FETCH_ASSOC);
        return (!$result) ? false : $result;
    }

    public function getSelectFetchAll($sql,$arr){
        $result = $this->executeDb($sql,$arr)->fetchAll(PDO::FETCH_ASSOC);
        return (!$result) ? false : $result;
    }
}




