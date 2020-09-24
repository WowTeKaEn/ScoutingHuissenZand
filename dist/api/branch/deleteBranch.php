<?php
function branch_delete() {
    include_once "databaseAccess.php";
    require_once "Mail/yandexUsers.php";
    session_start();
    if (isset($_POST["branchName"]) && isset($_SESSION['user']) && $_SESSION["user"]["admin"]) {
        try {
            $pdoResult = db::getInstance()->preparedQuery("delete from `branch` where branchName = ?", [$_POST["branchName"]]);
            if(update_user($_POST["branchName"], true)){
                http_response_code(200);
            }else{
                http_response_code(201);
            }
        } catch (Exception $e) {
            http_response_code(500);
        }
    } else {
        http_response_code(401);
    }
}

?>