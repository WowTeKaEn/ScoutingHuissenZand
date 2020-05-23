<?php
function event_delete() {
    include_once "databaseAccess.php";
    session_start();
    if (isset($_POST["branchName"]) && isset($_POST["eventName"]) && isset($_SESSION['user']) && $_SESSION["user"]["admin"]) {
        foreach($_SESSION["user"]["branches"] as $branch){
            if($branch["branchName"] == $_POST["branchName"]){
                try {
                    $pdoResult = db::getInstance()->preparedQuery("delete from `event` where eventName = ? and branchName = ?", [$_POST["eventName"],$_POST["branchName"]]);
                } catch (Exception $e) {
                    http_response_code(500);
                }
                exit;
            }
        }
            http_response_code(401);
    } else {
        http_response_code(401);
    }
}

?>