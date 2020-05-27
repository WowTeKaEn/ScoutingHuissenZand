<?php
function branch_assign(){
    include_once "databaseAccess.php";

session_start();


if (isset($_POST["branchName"]) && isset($_POST["branchAdmin"])) {
    if(isset($_SESSION['user']) && $_SESSION["user"]["admin"]){
        try {
            $pdoResult = db::getInstance()->preparedInsert("insert `branch` (branchName, branchAdmin, branchDescription) values (?,?,'[]')",[$_POST["branchName"],$_POST["branchAdmin"]]);
            http_response_code(201);
            if(!$pdoResult){
                include_once "assignBranch.php";
                branch_reassign();
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