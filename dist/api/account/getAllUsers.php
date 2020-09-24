<?php

function user_get_all(){
    session_start();
if(isset($_SESSION["user"]) && $_SESSION["user"]["admin"]){
        include_once "databaseAccess.php";
        $pdoResult = db::getInstance()->preparedQuery("SELECT email, activated, admin, (not exists (select 1 from branch where branchAdmin = u.email)) as deletable FROM user u where email != ?",[$_SESSION["user"]["email"]]);
        echo json_encode($pdoResult);
}else{
    http_response_code(401);
}
}