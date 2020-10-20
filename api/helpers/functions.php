<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;



function getAlbums($sql, $args){
    $db = db::getInstance();
    $albums = $db->preparedQuery($sql, $args);
    $albumIds = substr(array_reduce($albums, function ($carry, $item) {
        $carry .= $item["id"].",";
        return $carry;
    }), 0, -1);
    if(empty($albums)){
        return [];
    }
    $images = $db->executeQuery("SELECT * FROM `branchimages` where albumId in (".$albumIds.") order by inserted desc");
    foreach ($albums as $key => $val) {
        $albums[$key]["images"] = [];
        foreach ($images as $image) {
            if ($image["albumId"] === $albums[$key]["id"]) {
                array_push($albums[$key]["images"], $image);
            }
        }
    }
    return $albums;
}

function checkbody(Request $request, $params){
    $data = [];
    foreach($params as $param){
        if($request->request->get($param) == null) throw new HttpException(400,"Parameter ".$param." not set");
        $data[$param] = $request->request->get($param);
    }
    return $data;
}

function deleteImage($url, $album){
    $db = db::getInstance();
    $publicId = getIdFromUrl($url);

    \Cloudinary\Uploader::destroy($publicId);

    $db->preparedInsert("DELETE FROM `branchimages` where url = ? and albumId = ? ", [$url, $album]);
}

function createPhoto($file_path, $orig_name, $albumId) {
    $db = db::getInstance();
    $dbRes = false;
    $res = \Cloudinary\Uploader::upload($file_path, array(
        "tags" => "scoutingImage",
    ));
    if(isset($albumId)){
        $dbRes = $db->preparedInsert("INSERT INTO `branchimages`
        (albumId,url,w,h) select ?,?,?,?", [$albumId, $res["secure_url"],$res["width"],$res["height"]]);
    }else{
        $dbRes = $db->preparedInsert("INSERT INTO `image` (id) VALUES (?)",[$res["public_id"]]);
    }
    unlink($file_path);
    if(!$dbRes){
        return $dbRes;
    }
    return $res;
}

function saveImages($images) {
    if(!empty($images)){
        $sqlString = '';
        foreach ($images as $key => $image) {
            if ($key != 0) {
                $sqlString .= "or id = ?";
            }
        }
    
        return $db->preparedQuery("UPDATE `image` set saved = 1 where id = ? " . $sqlString, $images);
    }
   
}

function removeImages($images) {
    if(!empty($images)){
        $sqlString = '';
        $ids = [];
        foreach ($images as $key => $image) {
            $publicId = getIdFromUrl($image);
            array_push($ids, $publicId);
            if ($key != 0) {
                $sqlString .= "or id = ?";
            }
            \Cloudinary\Uploader::destroy($publicId);
        }
        return db::getInstance()->preparedInsert("DELETE FROM `image` where id = ? " . $sqlString, $ids);
    }
}

function branchAssign($data) {
    $db = db::getInstance();
    if ($db->preparedInsert("UPDATE `branch` set branchAdmin = ? where branchName = ?", [$data["branchAdmin"], $data["branchName"]])) {
        if (update_user($data["branchName"], false, $data["branchAdmin"])) {
            return new Response("Speltak geüpdatet");
        }
        return new Response("Speltak geüpdatet maar email niet");
    }
    return new Response("Speltak niet geüpdatet");
}

function getIdFromUrl($url){
    $url      = explode("/", $url);
    $url      = end($url);
    $url      = explode(".", $url);
    return $url[0];
}

function createDate($dateString) {
    return date('Y-m-d', strtotime(substr($dateString, 0, 10)));
}
