<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

require_once __DIR__."/../helpers/yandexUsers.php";
require __DIR__."/../helpers/middleware.php";

$event = $app['controllers_factory'];

$event->put("/event/{branchName}", function (Request $request, $branchName) use ($db) {
    $data = checkbody($request, ["startDate","endDate","eventName","eventDescription"]);
    if (!isset($data['visible'])) {
        $data["visible"] = 0;
    }
    
    $data["startDate"] = createDate($data["startDate"]);
    $data["endDate"]   = createDate($data["endDate"]);


    foreach ($_SESSION["user"]["branches"] as $branch) {
        if ($branch["branchName"] == $branchName) {
            if ($request->request->get("images") !== null) {
                saveImages($request->request->get("images"));
            }
            if ($request->request->get("deletedImages") !== null) {
                removeImages($request->request->get("deletedImages"));
            }


            $res = $db->preparedInsert(
                "INSERT into `event` (eventname, branchname, startdate, enddate, eventdescription, visible) values (?,?,?,?,?,?)",
                [$data["eventName"], $branchName, $data["startDate"], $data["endDate"], $data["eventDescription"], $data["visible"]]
            );

            if (!$res) {
                $res = db::getInstance()->preparedInsert(
                    "UPDATE `event` set eventDescription = ?, visible = ?  where startDate = ? and endDate = ? and eventName = ? and branchName = ?",
                    [$data["eventDescription"], $data["visible"], $data["startDate"], $data["endDate"], $data["eventName"], $branchName]
                );
                if($res){
                    return new Response("Evenement geüpdatet",200);
                }
            }else{
                return new Response("Evenement aangemaakt",201);
            }
        }
    }
    throw new HttpException(401, "Je moet eigenaar van de speltak zijn");
})->before($loggedIn);

$event->put("/event/{branchName}/date", function (Request $request, $branchName) use ($db) {
    $data = checkbody($request, ["startdate","enddate","prevStartDate","prevEndDate","eventName"]);
    
    $data["startDate"] = createDate($data["startDate"]);
    $data["endDate"]   = createDate($data["endDate"]);
    $data["prevStartDate"] = createDate($data["prevStartDate"]);
    $data["prevEndDate"]   = createDate($data["prevEndDate"]);

    foreach ($_SESSION["user"]["branches"] as $branch) {
        if ($branch["branchName"] == $branchName) {
            $res = $db->preparedInsert(
                "UPDATE `event` set startDate = ?, endDate = ? where eventName = ? and branchName = ? and startDate = ? and endDate = ?",
                [$data["startDate"],$data["endDate"], $data["eventName"], $branchName, $data["prevStartDate"], $data["prevEndDate"]]
            );
            return $res;
        }
    }
    throw new HttpException(401, "Je moet eigenaar van de speltak zijn");
})->before($loggedIn);

$event->get("/event/{branchName}", function (Request $request, $branchName) use ($db,$loggedIn,$app) {
    try {
        call_user_func($loggedIn, $request, $app);
        foreach ($_SESSION["user"]["branches"] as $branch) {
            if ($branch["branchName"] == $branchName) {
                return $db->preparedQuery("SELECT * FROM `event` where branchName = ?", [$branchName]);
            }
        }
    } catch (AccessDeniedHttpException $th) {
        return $db->preparedQuery("SELECT * FROM `event` where visible = 1 and branchName = ?", [$branchName]);
    }
});

$event->get("/event", function (Request $request) use ($db) {
    return $db->executeQuery("SELECT * FROM `event` where visible = 1");
});

$event->delete("/event", function (Request $request) use ($db) {
    $data = checkbody($request, ["startDate","endDate","branchName","eventName"]);
    
    $data["startDate"] = createDate($data["startDate"]);
    $data["endDate"]   = createDate($data["endDate"]);

    foreach ($_SESSION["user"]["branches"] as $branch) {
        if ($branch["branchName"] == $data["branchName"]) {
            return $db->preparedInsert(
                "DELETE from `event` where eventName = ? and branchName = ? and startDate = ? and endDate = ?",
                [$data["eventName"], $data["branchName"], $data["startDate"], $data["endDate"]]
            );
        }
    }
    throw new HttpException(401, "Je moet eigenaar van de speltak zijn");
})->before($loggedIn);


return $event;


