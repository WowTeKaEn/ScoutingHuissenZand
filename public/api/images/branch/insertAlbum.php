<?php
function branch_album_insert() {
    include_once "databaseAccess.php";
    session_start();

    if (isset($_POST["albumName"]) && isset($_POST["branchName"]) 
    && isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {

        foreach ($_SESSION["user"]["branches"] as $branch) {
            if ($branch["branchName"] == $_POST["branchName"]) {


            try {
                $pdoResult = db::getInstance()->preparedInsert("INSERT into `branchalbum` (branchName, name) values (?,?)", [$_POST["branchName"], $_POST["albumName"]]);
                
                if ($pdoResult) {
                http_response_code(200);
                   echo json_encode(db::getInstance()->preparedQuery("SELECT * from `branchalbum` where branchName = ? order by inserted desc limit 1",[$_POST["branchName"]]));
                }else{
                    http_response_code(500);
                }
                exit;
            } catch (Exception $e) {
                http_response_code(500);
            }
        } else {
            http_response_code(401);
        }
    }
}
}

?>
