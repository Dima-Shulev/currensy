<?php
//echo phpinfo();
require_once dirname(__DIR__)."/app/vendor/autoload.php";

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;
use Workerman\Lib\Timer;
use Connect\SelectDb;
use Connect\InsertDb;


function checkName($serverCurrensy, $nameCurrensyDb, $idCurrensy){
    $arrCurrensy = [];

    foreach($serverCurrensy as $val){
          $arrCurrensy["name"] = preg_replace('/[^A-Z]/',"",$val);
          $arrCurrensy["value"] = preg_replace("/[^0-9.]/","",$val);

          if($arrCurrensy["name"] !== $nameCurrensyDb){
              $insert_name = new InsertDb("currensy_name");
              $insert_name->getInsertFetch("INSERT INTO :table(:name)VALUES(:currensy)",[":name"=>"name",":currensy"=>$arrCurrensy["name"]]);
              $insert_value = new InsertDb("currensy_value");
              $insert_value->getInsertFetch("INSERT INTO :table(:value,:currensy_id)VALUES(:float)",[":value"=>"value",":currensy_id"=>$idCurrensy,":float"=>$arrCurrensy["value"]]);
          }
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
        $select_currens = new SelectDb("currensy_name");
        $result_currens = $select_currens->getSelectFetch("SELECT :id, :column FROM :table",[":id"=>"id",":column"=>"name",":table"=>"currensy_name"]);
        checkName((Array)$data,$result_currens["name"],$result_currens["id"]);

    };
    $worker_connection->onError = function($connection, $code, $msg){
        echo "Error: ".$msg;
    };
    $worker_connection->onClose = function($connection){
        echo "Connection Close\n";
    };
    $worker_connection->connect();
};
Worker::runAll();