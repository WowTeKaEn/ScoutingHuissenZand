<?php
function branch_update(){
    include_once "databaseAccess.php";
session_start();


if (isset($_POST["branchName"]) && isset($_POST["branchDescription"]) && isset($_POST["instaUsername"]) && isset($_POST["facebookUsername"]) && isset($_POST["visible"])){
    if(isset($_SESSION['user'])){
        try {
            $pdoResult = db::getInstance()->preparedInsert("
            update `branch` set branchDescription = ?, instaUsername = ?, facebookUsername = ?, visible = ?  where branchName = ? and branchAdmin = ?",
            [
            $_POST["branchDescription"],
            $_POST["instaUsername"],
            $_POST["facebookUsername"],
            $_POST["visible"],
            $_POST["branchName"],
            $_SESSION['user']["email"],
            ]);
            if(isset($_POST["images"])){
                include_once "images/updateImages.php";
                saveImages($_POST["images"]);  
            }
            if(isset($_POST["deletedImages"])){
                include_once "images/updateImages.php";
                removeImages($_POST["deletedImages"]);  
            }
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