<?php
function event_insert() {
    include_once "databaseAccess.php";
    $_POST["startDate"] = date('Y-m-d', strtotime(substr($_POST["startDate"], 0, 10)));
    $_POST["endDate"]   = date('Y-m-d', strtotime(substr($_POST["endDate"], 0, 10)));
    session_start();
    if (isset($_POST["eventName"]) && isset($_POST["branchName"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["eventDescription"])) {
        if (!isset($_POST['visible'])) {
            $_POST["visible"] = 0;
        }

        $_POST["startDate"] = date('Y-m-d', strtotime(substr($_POST["startDate"], 0, 10)));
        $_POST["endDate"]   = date('Y-m-d', strtotime(substr($_POST["endDate"], 0, 10)));

        foreach ($_SESSION["user"]["branches"] as $branch) {
            if ($branch["branchName"] == $_POST["branchName"]) {
                try {
                    $pdoResult = db::getInstance()->preparedInsert("
                    insert into `event` (eventname, branchname, startdate, enddate, eventdescription, visible) values (?,?,?,?,?,?)",
                        [
                            $_POST["eventName"],
                            $_POST["branchName"],
                            $_POST["startDate"],
                            $_POST["endDate"],
                            $_POST["eventDescription"],
                            $_POST["visible"],
                        ]);
                    if ($pdoResult) {
                        http_response_code(201);
                    } else {
                        $pdoResult = db::getInstance()->preparedInsert("
                            update `event` set eventDescription = ?, visible = ?  where startDate = ? and endDate = ? and eventName = ? and branchName = ?",
                            [
                                $_POST["eventDescription"],
                                $_POST["visible"],
                                $_POST["startDate"],
                                $_POST["endDate"],
                                $_POST["eventName"],
                                $_POST["branchName"],
                            ]);
                        if (isset($_POST["deletedImages"])) {
                            include_once "images/updateImages.php";
                            removeImages($_POST["deletedImages"]);
                        }
                        http_response_code(200);
                    }
                    if (isset($_POST["images"])) {
                        include_once "images/updateImages.php";
                        saveImages($_POST["images"]);
                    }
                    exit;
                } catch (Exception $e) {
                    http_response_code(500);
                }
            }
        }
        http_response_code(401);
        exit;
    } else {
        http_response_code(403);
    }
}
