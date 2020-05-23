<?php
function event_insert() {
    include_once "databaseAccess.php";

    session_start();

    if (isset($_POST["eventName"]) && isset($_POST["branchName"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["eventDescription"]) && isset($_POST["visible"])) {
        foreach ($_SESSION["user"]["branches"] as $branch) {
            if ($branch["branchName"] == $_POST["branchName"]) {
                try {
                    $pdoResult = db::getInstance()->preparedInsert("insert `event` (eventname, branchname, startdate, enddate, eventdescription, visible) values (?,?,?,?,?,?)",
                        [$_POST["eventName"], $_POST["branchName"], $_POST["startDate"], $_POST["endDate"], $_POST["eventDescription"], $_POST["visible"]]);
                    http_response_code(201);
                    if (!$pdoResult) {
                        $pdoResult = db::getInstance()->preparedInsert("
                            update `event` set startDate = ?, endDate = ?, eventDescription = ?, visible = ?  where eventName = ? and branchName = ?",
                            [
                                $_POST["startDate"],
                                $_POST["endDate"],
                                $_POST["eventDescription"],
                                $_POST["visible"],
                                $_POST["eventName"],
                                $_POST["branchName"]
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
            exit;
        }
        http_response_code(401);

    } else {
        http_response_code(403);
    }
}

?>