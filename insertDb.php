<?php
include_once "./PDOConfig.php";
$con = new PDOConfig();
$connect = $con->PDO;

try {
    //$query = $connect->prepare('SELECT * FROM `draw`');
    //$query->execute();
    //$result = $query->fetch(PDO::FETCH_ASSOC);


}catch(PDOException $e){
    echo "Ошибка подключения к базе данных". $e->getMessage();
}

