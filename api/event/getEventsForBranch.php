<?php
function event_get_branch(){
    include_once "databaseAccess.php";
session_start();


if (isset($_POST["branchName"])) {
    try {
        if(isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && (($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0))){
            $pdoResult = db::getInstance()->preparedQuery("SELECT * FROM `event` where branchName = ?",[$_POST["branchName"]]);
        }else{
            $pdoResult = db::getInstance()->preparedQuery("SELECT * FROM `event` where visible = 1 and branchName = ?",[$_POST["branchName"]]);
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
} else {
    http_response_code(403);
}
}

?>