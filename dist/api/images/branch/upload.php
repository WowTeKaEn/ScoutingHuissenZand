<?php

function branch_photo_upload() {

    try {
        session_start();
        if (isset($_POST["albumId"]) && isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {
                    require 'databaseAccess.php';


                    $ownsAlbum = db::getInstance()->preparedQuery("SELECT branchAdmin from branch where branchName in (select branchName from branchalbum where id = ?)", [$_POST["albumId"]]);

                    if($ownsAlbum[0]["branchAdmin"] !== $_SESSION["user"]["email"]){
                        http_response_code(401);
                        return;
                    }

                    \Cloudinary::config(array(
                        "cloud_name" => $_ENV["CLOUDINARY_CLOUD_NAME"],
                        "api_key"    => $_ENV["CLOUDINARY_API_KEY"],
                        "api_secret" => $_ENV["CLOUDINARY_API_SECRET"],
                        "secure"     => true,
                    ));

                    function create_photo($file_path, $orig_name) {
                        # Upload the received image file to Cloudinary
                        $res = \Cloudinary\Uploader::upload($file_path, array(
                            "tags" => "scoutingImage",
                        ));

                        
                        if ($res) {

                            $pdoResult = db::getInstance()->preparedInsert("INSERT INTO `branchimages`
                             (albumId,url,w,h) select ?,?,?,?", [$_POST["albumId"], $res["secure_url"],$res["width"],$res["height"]]);
                            if($pdoResult){
                                http_response_code(201);
                            }else{
                                http_response_code(500);
                            }
                        } else {
                            http_response_code(500);
                        }
                        unlink($file_path);
                        return $res;
                    }

                    $total = count($_FILES['files']['name']);

                    $result = [];


                    for( $i=0 ; $i < $total ; $i++ ) {
                        $result[$i]            = create_photo($_FILES["files"]["tmp_name"][$i], $_FILES["files"]["name"][$i]);
                        $return[$i]["url"]      = $result[$i]["secure_url"];
                        $return[$i]["public_id"] = $result[$i]["public_id"];
                    }

                   
                    echo json_encode($return);
                    exit;
        } else {
            http_response_code(401);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
}

?>