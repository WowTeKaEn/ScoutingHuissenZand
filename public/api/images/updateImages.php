<?php
require_once 'databaseAccess.php';

function saveImages($images) {
    if(!empty($images)){
        $sqlString = '';
        foreach ($images as $key => $image) {
            if ($key != 0) {
                $sqlString .= "or id = ?";
            }
        }
    
        return db::getInstance()->preparedQuery("update `image` set saved = 1 where id = ? " . $sqlString, $images);
    }
   
}


function removeImages($images) {
    if(!empty($images)){
    \Cloudinary::config(array(
        "cloud_name" => "duj1jfopx",
        "api_key"    => "648995845517767",
        "api_secret" => "yj9xOCyaYp1WSFB6VsA88TrYId4",
        "secure"     => true,
    ));
    
    
    $sqlString = '';
    $ids = [];
    foreach ($images as $key => $image) {
        $url = explode("/",$image);
        $url = end($url);
        $url = explode(".",$url);
        $publicId = $url[0];
        array_push($ids, $publicId);
        if ($key != 0) {
            $sqlString .= "or id = ?";
        }
        \Cloudinary\Uploader::destroy($publicId);
    }

    return db::getInstance()->preparedQuery("DELETE FROM `image` where id = ? " . $sqlString, $ids);
}
}

?>