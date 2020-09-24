<?php

function branch_photo_delete() {

    session_start();
    if (isset($_POST["albumId"]) && isset($_POST["image"]) && isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {
                require_once 'databaseAccess.php';

                \Cloudinary::config(array(
                    "cloud_name" => $_ENV["CLOUDINARY_CLOUD_NAME"],
                    "api_key"    => $_ENV["CLOUDINARY_API_KEY"],
                    "api_secret" => $_ENV["CLOUDINARY_API_SECRET"],
                    "secure"     => true,
                ));

                $url      = explode("/", $_POST["image"]);
                $url      = end($url);
                $url      = explode(".", $url);
                $publicId = $url[0];

                \Cloudinary\Uploader::destroy($publicId);

                db::getInstance()->preparedQuery("DELETE FROM `branchimages` where url = ? and albumId = ? ", [$_POST["image"], $_POST['albumId']]);
                exit;
            }
        }

?>