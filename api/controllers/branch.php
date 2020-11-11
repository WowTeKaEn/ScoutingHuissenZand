<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

require_once __DIR__."/../helpers/yandexUsers.php";
require __DIR__."/../helpers/middleware.php";




$branch = $app['controllers_factory'];



$branch->put("/branch", function (Request $request) use ($db) {
    $data = checkbody($request, ["branchAdmin","branchName"]);
    return branchAssign($data);
})->before($loggedIn)
->before($isAdmin);

$branch->delete("/branch/{branchName}", function (Request $request, $branchName) use ($db) {
    if ($db::getInstance()->preparedInsert("DELETE from `branch` where branchName = ?", [$branchName])) {
        if (update_user($branchName, true)) {
            return new Response("Speltak Verwijderd");
        }
        return new Response("Speltak verwijderd maar email niet");
    }
    throw new HttpException(500, "Speltak niet verwijderd");
})->before($loggedIn)
->before($isAdmin);


$branch->post("/branch", function (Request $request) use ($db) {
    $data = checkbody($request, ["branchAdmin","branchName"]);
    if (!$db->preparedInsert("insert `branch` (branchName, branchAdmin, branchDescription) values (?,?,'[]')", [$data["branchName"], $data["branchAdmin"]])) {
        return branchAssign($data);
    }
    if (create_account($data["branchName"], $data["branchAdmin"])) {
        return new Response("Speltak aangemaakt",201);
    }
    return new Response("Speltak aangemaakt maar email niet",201);
})->before($loggedIn)
->before($isAdmin);

$branch->put("/branch/{branchName}", function (Request $request, $branchName) use ($db) {
    $data = checkbody($request, ["branchDescription","visible"]);
    $data["instaUsername"] = $request->request->get("instaUsername");
    $data["facebookUsername"] = $request->request->get("facebookUsername");
    $res = $db->preparedInsert(
        "update `branch` set branchDescription = ?, instaUsername = ?, facebookUsername = ?, visible = ?  where branchName = ? and branchAdmin = ?",
        [$data["branchDescription"], $data["instaUsername"], $data["facebookUsername"], $data["visible"], $branchName, $_SESSION['user']["email"]]
    );
    if ($request->request->get("images") !== null) {
        saveImages($request->request->get("images"));
    }
    if ($request->request->get("deletedImages") !== null) {
        removeImages($request->request->get("deletedImages"));
    }
    return $res;
})->before($loggedIn);



$branch->get("/branch/{branchName}", function (Request $request, $branchName) use ($db,$loggedIn,$app) {
    $branch = [];
    try {
        call_user_func($loggedIn, $request, $app);
        $branch = $db->preparedQuery("SELECT * FROM `branch` where branchName = ?", [$branchName])[0];
    } catch (AccessDeniedHttpException $th) {
        $branch = $db->preparedQuery("SELECT * FROM `branch` where visible = 1 and branchName = ?", [$branchName])[0];
    }
    $branch["albums"] = getAlbums("SELECT * FROM `branchalbum` where branchName = ? order by inserted desc", [$branchName]);
    return $branch;
});



return $branch;


