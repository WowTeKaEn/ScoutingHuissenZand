<?php
function tab_insert(){
    include_once "databaseAccess.php";
session_start();
if (isset($_POST["tabName"]) && isset($_POST["tabDescription"])) {
    if(isset($_SESSION['user']) && $_SESSION["user"]["admin"]){
        try {
            $pdoResult = db::getInstance()->preparedInsert("insert `tabs` (tabName,tabDescription) values (?,?)",[$_POST["tabName"],$_POST["tabDescription"]]);
            http_response_code(201);
            if(!$pdoResult){
                $pdoResult = db::getInstance()->preparedInsert("update `tabs` set tabDescription = ? where tabName = ?",[$_POST["tabDescription"],$_POST["tabName"]]);
                http_response_code(200);
                if(!$pdoResult){
                    http_response_code(400); 
                }
            }
            if(isset($_POST["images"])){
                include_once "images/updateImages.php";
                saveImages($_POST["images"]);  
            }
            if(isset($_POST["deletedImages"])){
                include_once "images/updateImages.php";
                removeImages($_POST["deletedImages"]);  
            }
            exit;
        } catch (Exception $e) {
            http_response_code(500);
        }
    }else{
        http_response_code(401);
    }
} else {
    http_response_code(403);
}
}

?>