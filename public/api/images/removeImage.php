<?php

function imaged_remove(){
    

session_start();
if (isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0) && isset($_POST["image"])) {
    require_once "updateImages.php";
    removeImages([$_POST["image"]]);
} else {
    http_response_code(401);
}
}

?>