<?php

function user_get(){
    session_start();
if(isset($_SESSION["user"])){
    if($_SESSION["user"]["admin"]){
        include_once "databaseAccess.php";
        $pdoResult = db::getInstance()->preparedQuery("SELECT email FROM `user` where activated = 1",[$_SESSION["user"]["email"]]);
        $_SESSION["user"]["users"] = $pdoResult;
    }
    echo json_encode($_SESSION["user"]);
}else{
    http_response_code(401);
}
}


