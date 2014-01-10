<?php
require dirname(__FILE__).'/../config.php';

if ($_GET['type']=='all'){
    get_all();
    die();
}

if ($_GET['type']=='error'){
    get_errors();
    die();
}


//***************************************************************
function respond($stmt){
    $response = array();
    while($data = $stmt->fetch()){
        $response[]=array(
            'id'=>$data[0],
            'timestamp'=>$data[1],
            'message'=>$data[2],
            'level'=>$data[3],
            'site'=>$data[4],
            'sitegroup'=>$data[5],
        );
    }
    //header('');
    echo json_encode(array('logs'=>$response));
}


function get_all(){
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
    $sql = 'SELECT id,timestamp,message,level,site,sitegroup FROM log order by id desc';
    $stmt = $dbh->query($sql);
    respond($stmt);
}

function get_errors(){
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
    $sql = 'SELECT id,timestamp,substr(message,1,100),level,site,sitegroup FROM log where level="ERROR" order by id desc';
    $stmt = $dbh->query($sql);
    respond($stmt);
}

// todo
//function confirm($id){
//    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
//    $sql = 'UPDATE log SET status="CONFIRMED" where id=?';
//    $stmt = $dbh->query($sql,array($id));
//}

