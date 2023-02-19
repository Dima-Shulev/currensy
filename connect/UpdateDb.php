<?php

namespace Connect;

use PDO;

class UpdateDb extends PDOConfig
{
    public function __construct(){
        parent::__construct();
    }

    public function updateDataBase($value,$one,$two = null,$three = null,$four = null){
        $this->query = $this->conn->prepare("UPDATE :table SET {$one} = :one,{$two} = :two,{$three} = :three,{$four} = :four");
        $this->query->execute([":table"=>$this->table,":one"=>$value,":two"=>$two,":three"=>$three,":four"=>$four]);
        $this->result = $this->query->fetch(PDO::FETCH_ASSOC);
        return $this->result;
    }

}