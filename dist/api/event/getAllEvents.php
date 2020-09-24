<?php
function event_getall(){
    include_once "databaseAccess.php";
session_start();
    try {
        if(isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && (($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0))){
            $pdoResult = db::getInstance()->executeQuery("SELECT * FROM `event`");
        }else{
            $pdoResult = db::getInstance()->executeQuery("SELECT * FROM `event` where visible = 1");
        }
        if($pdoResult){
            http_response_code(201);
            echo json_encode($pdoResult);
        }else{
            http_response_code(404);
        }
    } catch (Exception $e) {
        http_response_code(500);
    }
}

?>