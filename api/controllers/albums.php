<?php
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;


$album = $app['controllers_factory'];

$album->get("/albums/each", function () use ($db) {
    return getAlbums("SELECT *, (branchName) as albumName FROM `branchalbum` GROUP BY branchName order by inserted desc", []);
});

$album->get("/albums/{branchName}", function ($branchName) use ($db) {
    return getAlbums("SELECT * FROM `branchalbum` where branchName = ? order by inserted desc", [$branchName]);
});

$album->post("/albums/{branchName}/{albumName}", function ($branchName, $albumName) use ($db) {
    foreach ($_SESSION["user"]["branches"] as $branch) {
        if ($branch["branchName"] == $branchName) {
            $db->preparedInsert("INSERT into `branchalbum` (branchName, name) values (?,?)", [$branchName, $albumName]);
            return $db->preparedQuery("SELECT * from `branchalbum` where branchName = ? and name = ? order by inserted desc limit 1", [$branchName, $albumName]);
        }
    }
    throw new HttpException(401, "Je moet eigenaar van de speltak zijn");
})
->before($loggedIn)
->before($ownsBranch);

$album->post("/albums/{albumId}", function ($albumId) use ($db) {
    $ownsAlbum = $db->preparedQuery("SELECT branchAdmin from branch where branchName in (select branchName from branchalbum where id = ?)", [$albumId]);
    if ($ownsAlbum[0]["branchAdmin"] !== $_SESSION["user"]["email"]) {
        throw new HttpException(401, "Je moet de eigenaar van dit album zijn");
    }
    $total = count($_FILES['files']['name']);
    $result = [];
    $return = [];
    for ($i=0 ; $i < $total ; $i++) {
        $result[$i]            = createPhoto($_FILES["files"]["tmp_name"][$i], $_FILES["files"]["name"][$i], $albumId);
        $return[$i]["url"]      = $result[$i]["secure_url"];
        $return[$i]["public_id"] = $result[$i]["public_id"];
        $return[$i]["w"] = $result[$i]["width"];
        $return[$i]["h"] = $result[$i]["height"];
        $return[$i]["albumId"] = $albumId;
    }
    return $return;
})
->before($loggedIn)
->before($ownsBranch);

$album->delete("/albums/{albumId}/image", function (Request $request, $albumId) use ($db) {
    $data = checkBody($request, ["image"]);
    \Cloudinary\Uploader::destroy(getIdFromUrl($data["image"]));
    return $db->preparedInsert("DELETE FROM `branchimages` where url = ? and albumId = ? ", [$data["image"], $albumId]);
})
->before($loggedIn)
->before($ownsBranch);

$album->delete("/albums/{albumId}", function ($albumId) use ($db) {
    $res = $db->preparedInsert("DELETE from `branchalbum` where id = ? and branchName in (select branchName from branch where branchAdmin = ?) limit 1", [$albumId,$_SESSION["user"]["email"]]);
    if (!$res) {
        throw new HttpException(401, "Je bent geen eigenaar van deze speltak");
    }
    $images = $db->preparedQuery("SELECT url from `branchimages` where albumId = ?", [$albumId]);
    foreach ($images as $image) {
        deleteImage($image["url"], $albumId);
    }
    $db->preparedInsert("DELETE from `branchimages` where albumId = ?", [$albumId]);
    return $res;
})
->before($loggedIn)
->before($ownsBranch);


return $album;
