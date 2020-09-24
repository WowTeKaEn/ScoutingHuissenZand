<?php
function branch_get(){
    include_once "databaseAccess.php";
session_start();


if (isset($_POST["branchName"])) {
    try {
        if(isset($_SESSION["user"]) && $_SESSION["user"]["activated"] && (($_SESSION["user"]["admin"] || sizeof($_SESSION["user"]["branches"]) > 0))){
            $branch = db::getInstance()->preparedQuery("SELECT * FROM `branch` where branchName = ?",[$_POST["branchName"]])[0];
        }else{
            $branch = db::getInstance()->preparedQuery("SELECT * FROM `branch` where visible = 1 and branchName = ?",[$_POST["branchName"]])[0];
        }
        if(isset($branch)){
            $branch["albums"] = db::getInstance()->preparedQuery("SELECT * FROM `branchalbum` where branchName = ? order by inserted desc",[$_POST["branchName"]] );
            $images = db::getInstance()->preparedQuery("SELECT * FROM `branchimages` where albumId in (SELECT id FROM `branchalbum` where branchName = ?) order by inserted desc",[$_POST["branchName"]] );
            foreach($branch["albums"] as $key => $val){
                $branch["albums"][$key]["images"] = [];
                foreach($images as $image){
                    if($image["albumId"] === $branch["albums"][$key]["id"]){
                        array_push($branch["albums"][$key]["images"],$image);
                    }
                }
            }


            http_response_code(201);
            echo json_encode($branch);
        }else{
            http_response_code(404);
        }
    } catch (Exception $e) {
        http_response_code(500);
    }
} else {
    http_response_code(403);
}
}

?>