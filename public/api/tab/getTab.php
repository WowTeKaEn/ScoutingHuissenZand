<?php
function tab_get(){
    include_once "databaseAccess.php";


if (isset($_POST["tab"])) {
    try {
        $pdoResult = db::getInstance()->preparedQuery("SELECT * FROM `tabs` where tabName = ?",[$_POST["tab"]]);
        http_response_code(201);
        echo json_encode($pdoResult[0]);
    } catch (Exception $e) {
        http_response_code(500);
    }
} else {
    http_response_code(403);
}
}

?>