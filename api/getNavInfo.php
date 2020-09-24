<?php
function info(){
    try {
        include_once "databaseAccess.php";
        $return = new \stdClass();
        session_start();
        if (isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {
            $pdoResult = db::getInstance()->executeQuery("SELECT branchName, branchAdmin, instaUsername, facebookUsername   FROM `branch`");
        } else {
            if (!isset($_SESSION["user"])) {
                $_SESSION = [];
                session_destroy();
            }
            $pdoResult = db::getInstance()->executeQuery("SELECT branchName, branchAdmin, instaUsername, facebookUsername FROM `branch` where visible = 1");
        }
        $return->branches = $pdoResult;
        $pdoResult        = db::getInstance()->executeQuery("SELECT tabName FROM `tabs`");
        $return->tabs     = $pdoResult;
        echo json_encode($return);
    } catch (Exception $e) {
        http_response_code(500);
    }
}
?>