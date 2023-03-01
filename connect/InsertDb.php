<?php

namespace Connect;

use PDO;

class InsertDb{
    private $connect;
    protected $query;
    protected $result;

    public function __construct(){
    }

    public function getPrepareSql($sql){
        $this->connect = new PDOConfig();
        $this->query = $this->connect->PDO->prepare($sql);
        $this->query->execute();
        $this->result = $this->query->fetch(PDO::FETCH_ASSOC);
        return $this->result;
    }
}
