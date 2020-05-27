<?php
function branch_reassign(){
    include_once "databaseAccess.php";
session_start();


if (isset($_POST["branchName"]) && isset($_POST["branchAdmin"])) {
    if(isset($_SESSION['user']) && $_SESSION["user"]["admin"]){
        try {
            $pdoResult = db::getInstance()->preparedInsert("update `branch` set branchAdmin = ? where branchName = ?",[$_POST["branchAdmin"],$_POST["branchName"]]);
            http_response_code(200);
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