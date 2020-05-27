<?php
function event_update() {
    include_once "databaseAccess.php";

    session_start();

    if (isset($_POST["eventName"]) && isset($_POST["branchName"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["prevStartDate"]) && isset($_POST["prevEndDate"])) {

        $_POST["startDate"] = date('Y-m-d', strtotime(substr($_POST["startDate"], 0, 10)));
        $_POST["endDate"]   = date('Y-m-d', strtotime(substr($_POST["endDate"], 0, 10)));
        $_POST["prevStartDate"] = date('Y-m-d', strtotime(substr($_POST["prevStartDate"], 0, 10)));
        $_POST["prevEndDate"]   = date('Y-m-d', strtotime(substr($_POST["prevEndDate"], 0, 10)));

        foreach ($_SESSION["user"]["branches"] as $branch) {
            if ($branch["branchName"] == $_POST["branchName"]) {
                try {

                    $pdoResult = db::getInstance()->preparedInsert("
                            update `event` set startDate = ?, endDate = ? where eventName = ? and branchName = ? and startDate = ? and endDate = ?",
                        [
                            $_POST["startDate"],
                            $_POST["endDate"],
                            $_POST["eventName"],
                            $_POST["branchName"],
                            $_POST["prevStartDate"],
                            $_POST["prevEndDate"],
                        ]);
                    
                    if($pdoResult){
                        http_response_code(200);
                    }else{
                        var_dump($_POST);
                        http_response_code(500);
                    }
                    exit;
                } catch (Exception $e) {
                    http_response_code(500);
                }
            }
            exit;
        }
        http_response_code(401);

    } else {
        http_response_code(403);
    }
}

?>