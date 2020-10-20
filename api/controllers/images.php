<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

$images = $app['controllers_factory'];


$images->get("/images/check",function() use($db){
if ($_SERVER['PHP_AUTH_USER'] == $_ENV["CRON_USER"] && $_SERVER['PHP_AUTH_PW'] == $_ENV["CRON_PASS"]) {
    $res = $db->executeQuery("SELECT * FROM `image` WHERE saved = 0 and HOUR(TIMEDIFF(NOW(), timestamp))>6");
    if ($res) {
        $sqlString = '';
        $images    = [];
        foreach ($pdoResult as $key => $image) {
            array_push($images, $pdoResult[$key]['id']);
            if ($key != 0) {
                $sqlString .= "or id = ?";
            }
            \Cloudinary\Uploader::destroy($pdoResult[$key]['id']);
        }
        $db->preparedInsert("DELETE FROM `image` where id = ? " . $sqlString, $images);
    }
}
});


$images->post("/images",function() use($db){       
 $result            = createPhoto($_FILES["file"]["tmp_name"], $_FILES["file"]["name"],"INSERT INTO `image` (id) VALUES (?)");
 $return["url"]      = $result["secure_url"];
 $return["public_id"] = $result["public_id"];
 return $return;
})->before($loggedIn)
->before($ownsBranch);


$images->delete("/images",function(Request $request) use($db){       
    $data = checkbody($request, ["image"]);
    removeImages([$data["image"]]);
    return true;
   })->before($loggedIn)
   ->before($ownsBranch);


return $images;
