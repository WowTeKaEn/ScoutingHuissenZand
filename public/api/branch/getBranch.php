<?php
function branch_get(){
    include_once "databaseAccess.php";
session_start();


if (isset($_POST["branchName"])) {
    try {
        if(isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && (($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0))){
            $branch = db::getInstance()->preparedQuery("SELECT * FROM `branch` where branchName = ?",[$_POST["branchName"]])[0];
        }else{
            $branch = db::getInstance()->preparedQuery("SELECT * FROM `branch` where visible = 1 and branchName = ?",[$_POST["branchName"]])[0];
        }
        if(isset($branch)){
            $branch["images"] = db::getInstance()->preparedQuery("SELECT * FROM `branchimages` where branchName = ? order by inserted desc",[$_POST["branchName"]] );
            http_response_code(201);
            echo json_encode($branch);
        }else{
            http_response_code(404);
        }
    } catch (Exception $e) {
        http_response_code(500);
    }
} else {
    http_response_code(403);
}
}

?>