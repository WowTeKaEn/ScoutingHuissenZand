<?php

function user_promote(){
session_start();
if(isset($_SESSION["user"]) && $_SESSION["user"]["admin"] && $_SESSION["user"]["activated"] && isset($_POST["email"])){
    include_once "databaseAccess.php";
    $pdoResult = db::getInstance()->preparedInsert("UPDATE user set admin = not admin WHERE email = ?",[$_POST["email"]]);
}else{
    http_response_code(401);
}

}