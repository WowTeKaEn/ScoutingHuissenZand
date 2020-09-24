<?php

function user_delete(){
session_start();
if(isset($_SESSION["user"]) && $_SESSION["user"]["admin"] && $_SESSION["user"]["activated"] && isset($_POST["email"])){
    include_once "databaseAccess.php";
    $pdoResult = db::getInstance()->preparedInsert("delete from user WHERE email = ?",[$_POST["email"]]);
}else{
    http_response_code(401);
}

}