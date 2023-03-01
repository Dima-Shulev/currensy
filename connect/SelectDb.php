<?php

namespace Connect;

use PDO;

class SelectDb{
    private $connect;
    protected $query;
    protected $result_fetch;

    public function __construct(){
    }

    public function getPrepareSql($sql){
        $this->connect = new PDOConfig();
        $this->query = $this->connect->PDO->prepare($sql);
        $this->query->execute();
        $this->result_fetch = $this->query->fetch(PDO::FETCH_ASSOC);
        return $this->result_fetch;
    }
}
