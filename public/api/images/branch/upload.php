<?php

function branch_photo_upload() {
    $return = new \stdClass();

    try {
        session_start();
        if (isset($_POST["branchName"]) && isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && ($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0)) {
            foreach ($_SESSION["user"]["branches"] as $branch) {
                if ($branch["branchName"] == $_POST["branchName"]) {
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
                            db::getInstance()->preparedInsert("INSERT INTO `branchimages` (branchName,url) VALUES (?,?)", [$_POST["branchName"], $result["secure_url"]]);
                            http_response_code(201);
                        } else {
                            http_response_code(500);
                        }
                        return $result;
                    }

                    $result            = create_photo($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
                    $return->url       = $result["secure_url"];
                    $return->public_id = $result["public_id"];
                    echo json_encode($return);
                    exit;
                }
            }
            http_response_code(401);
        } else {
            http_response_code(401);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
}

?>