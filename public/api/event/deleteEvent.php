<?php
function event_delete() {
    include_once "databaseAccess.php";
    session_start();
    if (isset($_POST["branchName"]) && isset($_POST["eventName"]) && isset($_SESSION['user']) && isset($_POST["startDate"]) && isset($_POST["endDate"])) {

        $_POST["startDate"] = date('Y-m-d', strtotime(substr($_POST["startDate"], 0, 10)));
        $_POST["endDate"]   = date('Y-m-d', strtotime(substr($_POST["endDate"], 0, 10)));

        foreach ($_SESSION["user"]["branches"] as $branch) {
            if ($branch["branchName"] == $_POST["branchName"]) {
                try {
                    $pdoResult = db::getInstance()->preparedQuery("
                    delete from `event` where eventName = ? and branchName = ? and startDate = ? and endDate = ?",
                        [
                            $_POST["eventName"],
                            $_POST["branchName"],
                            $_POST["startDate"],
                            $_POST["endDate"]
                        ]);
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