<?php

namespace Connect;

use PDO;

class InsertDb extends PDOConfig
{
    public function __construct($table){
        parent::__construct();
        $this->table = $table;
    }

    /*public function insertDataBaseFetch($sql,$arr){
        $this->query = $this->conn->prepare($sql);
        $this->query->execute($arr);

        $this->result = $this->query->fetch(PDO::FETCH_ASSOC);
        return $this->result;
    }
    */
    public function getInsertFetch($sql,$arr){
        $result = $this->executeDb($sql,$arr)->fetch(PDO::FETCH_ASSOC);
        return (!$result) ? false : $result;
    }

    public function getInsertFetchAll($sql,$arr){
        $result = $this->executeDb($sql,$arr)->fetchAll(PDO::FETCH_ASSOC);
        return (!$result) ? false : $result;
    }

}

