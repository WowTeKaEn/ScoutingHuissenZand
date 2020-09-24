<?php
function tab_delete(){
    include_once "databaseAccess.php";
session_start();
if (isset($_POST["tabName"]) && isset($_SESSION['user']) && $_SESSION["user"]["admin"]) {
    try {
        $pdoResult = db::getInstance()->preparedQuery("delete from `tabs` where tabName = ?",[$_POST["tabName"]]);
    } catch (Exception $e) {
        http_response_code(500);
    }
} else {
    http_response_code(401);
}
}

?>