<?php
function albums_get() {
    include_once "databaseAccess.php";
    session_start();

        $albums = db::getInstance()->executeQuery("SELECT * FROM `branchalbum` order by inserted desc");
        $images = db::getInstance()->executeQuery("SELECT * FROM `branchimages` where albumId in (SELECT id FROM `branchalbum`) order by inserted desc");
        foreach($albums as $key => $val){
            $albums[$key]["images"] = [];
            foreach($images as $image){
                if($image["albumId"] === $albums[$key]["id"]){
                    array_push($albums[$key]["images"],$image);
                }
            }
        }
        echo json_encode($albums);
}

?>
