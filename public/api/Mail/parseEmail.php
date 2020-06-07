<?php
function email_parse() {

    include_once "Mail.php";
    include_once "databaseAccess.php";

    $branches = db::getInstance()->executeQuery("SELECT branchName, branchAdmin FROM `branch` where visible = 1");
    if (isset($_POST["to"]) &&
        isset($_POST["subject"]) &&
        isset($_POST["from"]) &&
        isset($_POST["html"])
    ) {
        $branchTo = explode("@", $_POST["to"]);

        foreach ($branches as $key => $branch) {
            if (strtolower($branch["branchName"]) == strtolower($branchTo[0])) {
                http_response_code(sendGridMail::getInstance()->sendMail("FW: " . $_POST["subject"], "Doorgestuurd bericht van " . $_POST["from"] . "<br><br>" . $_POST["html"],[], $branch["branchAdmin"], $branch["branchName"]));
                exit;
            } else if ($branchTo[0] = "admin") {
                http_response_code(sendGridMail::getInstance()->sendMail("FW: " . $_POST["subject"], "Doorgestuurd bericht van " . $_POST["from"] . "<br><br>" . $_POST["html"]));
                exit;
            }
        }
        sendGridMail::getInstance()->sendMail("Something went wrong", "But I'm not certain what. ");
    }else{
        sendGridMail::getInstance()->sendMail("Something went wrong", "But I'm not certain what. POST: ". var_export($_POST, true));
    }
}

?>