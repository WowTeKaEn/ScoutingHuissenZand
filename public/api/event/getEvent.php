<?php
function event_get(){
    include_once "databaseAccess.php";
session_start();


if (isset($_POST["eventName"]) && isset($_POST["branchName"])) {
    try {
        if(isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && (($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0))){
            $pdoResult = db::getInstance()->preparedQuery("SELECT * FROM `event` where eventName = ? and branchName = ?",[$_POST["eventName"], $_POST["branchName"]]);
        }else{
            $pdoResult = db::getInstance()->preparedQuery("SELECT * FROM `event` where visible = 1 and eventName = ? and branchName = ?",[$_POST["eventName"], $_POST["branchName"]]);
        }
        if(isset($pdoResult[0])){
            http_response_code(201);
            echo json_encode($pdoResult[0]);
        }else{
            http_response_code(404);
        }
    } catch (Exception $e) {
        http_response_code(500);
    }
} else {
    http_response_code(403);
}
}

?>