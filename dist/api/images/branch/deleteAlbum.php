<?php
function branch_album_delete() {
    include_once "databaseAccess.php";
    session_start();

    if (isset($_POST["albumId"]) && isset($_POST["branchName"]) 
    && isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {
            try {
                $pdoResult = db::getInstance()->preparedQuery("DELETE from `branchalbum` where id = ? and ? in (select branchName from branch where branchAdmin = ?) limit 1", [$_POST["albumId"],$_POST["branchName"],$_SESSION["user"]["email"]]);
                    $images = db::getInstance()->preparedQuery("SELECT url from `branchimages` where albumId = ?",[$_POST["albumId"]]);
                    foreach($images as $image){
                        deleteImage($image["url"],$_POST["albumId"]);
                    }
                   db::getInstance()->preparedQuery("DELETE from `branchimages` where albumId = ?",[$_POST["albumId"]]);
                exit;
            } catch (Exception $e) {
                http_response_code(500);
            }
    } else {
        http_response_code(403);
    }
}

function deleteImage($url, $album){
    \Cloudinary::config(array(
        "cloud_name" => $_ENV["CLOUDINARY_CLOUD_NAME"],
        "api_key"    => $_ENV["CLOUDINARY_API_KEY"],
        "api_secret" => $_ENV["CLOUDINARY_API_SECRET"],
        "secure"     => true,
    ));

    $url      = explode("/", $url);
    $url      = end($url);
    $url      = explode(".", $url);
    $publicId = $url[0];

    \Cloudinary\Uploader::destroy($publicId);

    db::getInstance()->preparedQuery("DELETE FROM `branchimages` where url = ? and albumId = ? ", [$url, $album]);
}

?>
