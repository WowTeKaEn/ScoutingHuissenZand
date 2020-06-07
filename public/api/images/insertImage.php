<?php

function images_insert(){
    $return = new \stdClass();

try {
    session_start();
    if (isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {
        require 'databaseAccess.php';


        \Cloudinary::config(array(
            "cloud_name" => "duj1jfopx",
            "api_key"    => "648995845517767",
            "api_secret" => "yj9xOCyaYp1WSFB6VsA88TrYId4",
            "secure"     => true,
        ));

        function create_photo($file_path, $orig_name) {
            # Upload the received image file to Cloudinary
            $result = \Cloudinary\Uploader::upload($file_path, array(
                "tags" => "scoutingImage",
            ));

            unlink($file_path);
            if ($result) {
                db::getInstance()->preparedInsert("INSERT INTO `image` (id) VALUES (?)",[$result["public_id"]]);
                http_response_code(201);
            } else {
                http_response_code(500);
            }
            return $result;
        }

        $result      = create_photo($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
        $return->url = $result["secure_url"];
        $return->public_id = $result["public_id"];
        echo json_encode($return);
    }else{
        http_response_code(401);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo $e->getMessage();
}
}

?>