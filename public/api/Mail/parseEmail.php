<?php
function email_parse(){
    
include_once "Mail.php";
include_once "databaseAccess.php";

$parseAddress = "167.89.117";

if ($_SERVER["REMOTE_ADDR"] == "::1" || strrev(explode(".",strrev($_SERVER["REMOTE_ADDR"]),2)[1]) == $parseAddress) {
    $branches = db::getInstance()->executeQuery("SELECT branchName, branchAdmin FROM `branch` where visible = 1");

    $branchTo = explode("@", $_POST["to"]);

    foreach ($branches as $key => $branch) {
        if (strtolower($branch["branchName"]) == strtolower($branchTo[0])) {
            http_response_code(sendGridMail::getInstance()->sendMail("FW: ".$_POST["subject"], "Doorgestuurd bericht van " . $_POST["from"]."<br><br>".$_POST["html"],$branch["branchAdmin"], $branch["branchName"]));
            exit;
        }else if($branchTo[0] = "admin"){
            http_response_code(sendGridMail::getInstance()->sendMail("FW: ".$_POST["subject"], "Doorgestuurd bericht van " . $_POST["from"]."<br><br>".$_POST["html"]));
            exit;
        }
    }
}else{
    sendGridMail::getInstance()->sendMail("Someone accessed parseEmail from another place","Someone accessed parseEmail from: ".$_SERVER["REMOTE_ADDR"]);
    http_response_code(404);
}
}

?>