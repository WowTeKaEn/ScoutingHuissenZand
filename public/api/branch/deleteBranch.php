<?php
function branch_delete() {
    include_once "databaseAccess.php";
    session_start();
    if (isset($_POST["branchName"]) && isset($_SESSION['user']) && $_SESSION["user"]["admin"]) {
        try {
            $pdoResult = db::getInstance()->preparedQuery("delete from `branch` where branchName = ?", [$_POST["branchName"]]);
        } catch (Exception $e) {
            http_response_code(500);
        }
    } else {
        http_response_code(401);
    }
}

?>