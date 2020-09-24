<?php

// header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN'] );
header("Access-Control-Allow-Credentials: true");

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization, cache-control");
    header("HTTP/1.1 200 OK");
    die();
    }

$endPoints = ["info" => "getNavInfo.php",
"user/delete" => "account/deleteUser.php",
 "user/logout" => "account/log-out.php",
 "user/login" => "account/login.php",
 "user/signup" => "account/sign-up.php",
 "user/get" => "account/getUser.php",
 "user/get/all" => "account/getAllUsers.php",
 "user/validate" => "account/validate.php",
 "user/promote" => "account/setAdmin.php",
  "branch/get" => "branch/getBranch.php",
  "branch/assign" => "branch/insertBranch.php",
  "branch/update" => "branch/updateBranch.php",
  "branch/delete" => "branch/deleteBranch.php",
  "branch/photo/delete" => "images/branch/delete.php",
  "branch/photo/upload" => "images/branch/upload.php",
  "branch/album/insert" => "images/branch/insertAlbum.php",
  "branch/album/delete" => "images/branch/deleteAlbum.php",
  "branch/albums/get" => "images/branch/getAlbums.php",
  "event/get" => "event/getEvent.php",
  "event/getall" => "event/getAllEvents.php",
  "event/get/branch" => "event/getEventsForBranch.php",
  "event/insert" => "event/insertEvent.php",
  "event/update" => "event/updateEventDate.php",
  "event/delete" => "event/deleteEvent.php",
  "tab/delete" => "tab/deleteTab.php",
  "tab/get" => "tab/getTab.php",
  "tab/insert" => "tab/insertTab.php",
  "images/insert" => "images/insertImage.php",
  "images/remove" => "images/removeImage.php",
  "images/check" => "images/checkImages.php",
  "enroll" => "enroll.php",

];



if(!isset($_GET['path'])){
    http_response_code(404);
}else{
    try{
        $found = false;
        foreach($endPoints as $key => $endPoint){
            if($key == $_GET["path"]){
                require_once "env.php";
                require_once $endPoint;
                $funcName = str_replace("/","_",$key);

                if(!($funcName == "branch_photo_upload" || $funcName == "email_parse")){
                    $_POST = json_decode(file_get_contents("php://input"), true);
                }
                if(function_exists($funcName)){
                    require_once "vendor/autoload.php";
                    $funcName();
                    $found = true;
                break;
                }else{
                    http_response_code(404);
                }
                echo "endpoint configured incorrectly <br>";
                exit;
            }
        }
        if(!$found){
            http_response_code(404);
            echo "endpoint unknown <br>";
        }
    }catch(Exception $e){
        http_response_code(404);
    }
}

?>