<?php
function images_check(){
    $cronUser    = "8T['!=3Z=Lw4vFUg";
$cronPass    = "rE=dz!P]zRM?8M63";

if ($_SERVER['PHP_AUTH_USER'] == $cronUser && $_SERVER['PHP_AUTH_PW'] == $cronPass) {

    require_once "databaseAccess.php";
    
    $pdoResult = db::getInstance()->executeQuery("SELECT * FROM `image` WHERE saved = 0 and HOUR(TIMEDIFF(NOW(), timestamp))>6");

    if ($pdoResult) {
        \Cloudinary::config(array(
            "cloud_name" => $_ENV["CLOUDINARY_CLOUD_NAME"],
            "api_key"    => $_ENV["CLOUDINARY_API_KEY"],
            "api_secret" => $_ENV["CLOUDINARY_API_SECRET"],
            "secure"     => true,
        ));

        $sqlString = '';
        $images    = [];
        foreach ($pdoResult as $key => $image) {
            array_push($images, $pdoResult[$key]['id']);
            if ($key != 0) {
                $sqlString .= "or id = ?";
            }
            \Cloudinary\Uploader::destroy($pdoResult[$key]['id']);
        }
        $pdoResult = db::getInstance()->preparedQuery("DELETE FROM `image` where id = ? " . $sqlString, $images);
    }
}
}

?>