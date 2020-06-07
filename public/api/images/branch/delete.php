<?php

function branch_photo_delete() {

    session_start();
    if (isset($_POST["branchName"]) && isset($_POST["image"]) && isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {
        foreach ($_SESSION["user"]["branches"] as $branch) {
            if ($branch["branchName"] == $_POST["branchName"]) {
                require 'databaseAccess.php';

                \Cloudinary::config(array(
                    "cloud_name" => "duj1jfopx",
                    "api_key"    => "648995845517767",
                    "api_secret" => "yj9xOCyaYp1WSFB6VsA88TrYId4",
                    "secure"     => true,
                ));

                $url      = explode("/", $_POST["image"]);
                $url      = end($url);
                $url      = explode(".", $url);
                $publicId = $url[0];

                \Cloudinary\Uploader::destroy($publicId);

                db::getInstance()->preparedQuery("DELETE FROM `branchimages` where url = ? and branchName = ? ", [$_POST["image"], $_POST['branchName']]);
                exit;
            }
        }
        http_response_code(401);
    } else {
        http_response_code(401);
    }
}

?>