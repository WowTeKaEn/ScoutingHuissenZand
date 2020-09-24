<?php
function user_login(){
    include_once "databaseAccess.php";



if(isset($_POST["email"])){
    $username = $_POST["email"];
    $password = $_POST["password"];
    $pdoResult = db::getInstance()->preparedQuery("SELECT email, Password, activated, admin FROM `user` WHERE email = ?",[$username]);
    $pdoResult2 = db::getInstance()->preparedQuery("SELECT branchName FROM `branch` WHERE branchAdmin = ?",[$username]);
    if($pdoResult){
        if(password_verify($password,  $pdoResult[0]["Password"])){
            if(!$pdoResult[0]["activated"]){
                http_response_code(403);
            }else{    
                session_start();
                $_SESSION["user"] = $pdoResult[0];
                $_SESSION["user"]["branches"] = $pdoResult2;
                http_response_code(201);
            }      
        }else{
            http_response_code(401);
        }
    }else{
        http_response_code(401);
    }
}else{
    http_response_code(400);
}
}
?>
