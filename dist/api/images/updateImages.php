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
        "cloud_name" => $_ENV["CLOUDINARY_CLOUD_NAME"],
        "api_key"    => $_ENV["CLOUDINARY_API_KEY"],
        "api_secret" => $_ENV["CLOUDINARY_API_SECRET"],
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