<?php
function images_check(){
    $cronUser    = "8T['!=3Z=Lw4vFUg";
$cronPass    = "rE=dz!P]zRM?8M63";

if ($_SERVER['PHP_AUTH_USER'] == $cronUser && $_SERVER['PHP_AUTH_PW'] == $cronPass) {

    require_once "databaseAccess.php";
    
    $pdoResult = db::getInstance()->executeQuery("SELECT * FROM `image` WHERE saved = 0 and HOUR(TIMEDIFF(NOW(), timestamp))>6");

    if ($pdoResult) {
        \Cloudinary::config(array(
            "cloud_name" => "duj1jfopx",
            "api_key"    => "648995845517767",
            "api_secret" => "yj9xOCyaYp1WSFB6VsA88TrYId4",
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