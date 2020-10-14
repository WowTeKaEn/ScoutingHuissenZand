<?php
function branch_reassign() {
    include_once "databaseAccess.php";
    require_once "Mail/yandexUsers.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST["branchName"]) && isset($_POST["branchAdmin"])) {
        if (isset($_SESSION['user']) && $_SESSION["user"]["admin"]) {
            try {
                $pdoResult = db::getInstance()->preparedInsert("UPDATE `branch` set branchAdmin = ? where branchName = ?", [$_POST["branchAdmin"], $_POST["branchName"]]);
                if(update_user($_POST["branchName"], false, $_POST["branchAdmin"])){
                    http_response_code(200);
                }else{
                    http_response_code(202);
                }
                exit;
            } catch (Exception $e) {
                http_response_code(500);
            }
        } else {
            http_response_code(401);
        }
    } else {
        http_response_code(403);
    }
}

?>