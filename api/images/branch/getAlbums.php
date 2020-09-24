<?php
function branch_albums_get() {
    include_once "databaseAccess.php";
    session_start();

    if (isset($_POST["branchName"])) {
        $albums = db::getInstance()->preparedQuery("SELECT * FROM `branchalbum` where branchName = ? order by inserted desc",[$_POST["branchName"]] );
        $images = db::getInstance()->preparedQuery("SELECT * FROM `branchimages` where albumId in (SELECT id FROM `branchalbum` where branchName = ?) order by inserted desc",[$_POST["branchName"]] );
        foreach($albums as $key => $val){
            $albums[$key]["images"] = [];
            foreach($images as $image){
                if($image["albumId"] === $albums[$key]["id"]){
                    array_push($albums[$key]["images"],$image);
                }
            }
        }
        echo json_encode($albums);
    } else {
        http_response_code(403);
    }
}

?>
