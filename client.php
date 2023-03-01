<?php

require_once dirname(__DIR__)."/app/vendor/autoload.php";

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;
use Workerman\Lib\Timer;
use Connect\SelectDb;
use Connect\InsertDb;

function notDuplicateCode($currensy,$value,$update){
    $new_insert_currensy = new InsertDb;
    $new_insert_currensy->getPrepareSql("INSERT INTO `currensy_name`(`id`,`name`)VALUES(null,'".$currensy."')");
    $select_db = new SelectDb;
    $result_id = $select_db->getPrepareSql("SELECT `id` FROM `currensy_name` WHERE `name` = '".$currensy."'");
    $new_insert_value = new InsertDb;
    $new_insert_value->getPrepareSql("INSERT INTO `currensy_value`(`value`,`updated_at`,`currensy_name_id`)VALUES('".$value."','".$update."','".$result_id["id"]."')");
}

function serverCurrensy($serverObject)
{
    //выборка входных данных с сервера и запись их в переменные
    $serverCurrensy = (array)$serverObject;
    foreach ($serverCurrensy as $key => $val) {
        if($key === "name"){
            $currensy = $val;
        }else if($key === "value"){
           $value = $val;
        }
    }
    $currensy_select = new SelectDb;
    $result_select = $currensy_select->getPrepareSql("SELECT `name` FROM `currensy_name`");
    $update = Date("Y-m-d H:m:s");
    //если база данных не пуста и есть записи
    if($result_select !== false){
        foreach ($result_select as $k => $v){
            //если валютная пара совпадает с парой в базе данных
            if(($k === "name")&&($v === $currensy)){
                $resultSelectId = $currensy_select->getPrepareSql("SELECT `id` FROM `currensy_name` WHERE `name` = '".$currensy."'");
                $new_insert_value = new InsertDb;
                $new_insert_value->getPrepareSql("INSERT INTO `currensy_value`(`value`,`updated_at`,`currensy_name_id`)VALUES('".$value."','".$update."','".$resultSelectId["id"]."')");
                //если валютная пара не совпадает с парой в базе данных
            }else if (($k === "name")&&($v !== $currensy)){
                //контрольная проверка, без нее будут дубли в базе данных
                $checkCurrensy = new SelectDb;
                $resultCheck = $checkCurrensy->getPrepareSql("SELECT `id`,`name` FROM `currensy_name` WHERE `name` = '".$currensy."'");
                if(isset($resultCheck["name"])) {
                    $new_insert_value = new InsertDb;
                    $new_insert_value->getPrepareSql("INSERT INTO `currensy_value`(`value`,`updated_at`,`currensy_name_id`)VALUES('" . $value . "','" . $update . "','" . $resultCheck["id"] . "')");
                }else{
                    notDuplicateCode($currensy,$value,$update);
                }
            }
        }
    }else{
        //если база данных пуста и нет ни одной валюты тогда создаем первую запись, после чего данное условие игнорируется";
        notDuplicateCode($currensy,$value,$update);
       }
}

$worker = new Worker();
$worker->onWorkerStart = function(){

    $worker_connection = new AsyncTcpConnection("ws://fx-gen.otv5125.ru");
    $worker_connection->onConnect = function($connection){
        Timer::add(2, function() use($connection){
        $connection->send("New connection: \n");
        });
    };
    $worker_connection->onMessage = function($connection, $data){
        serverCurrensy(json_decode($data));
    };
    $worker_connection->onError = function($connection, $code, $msg){
        echo "Error: ".$msg;
    };
    $worker_connection->onClose = function($connection){
        echo "Connection Close ". $connection."\n";
    };
    $worker_connection->connect();
};
Worker::runAll();
