<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;


$tab = $app['controllers_factory'];


$tab->get("/tab/{tabName}",function($tabName) use($db){
    return $db->preparedQuery("SELECT * FROM `tabs` where tabName = ?",[$tabName])[0];
});


$tab->delete("/tab/{tabName}",function($tabName) use($db){
    return $db->preparedInsert("DELETE FROM `tabs` where tabName = ?",[$tabName]);
})
->before($loggedIn)
->before($isAdmin);

$tab->put("/tab",function(Request $request) use($db){
    $data = checkbody($request,["tabName","tabDescription"]);
    $res = true;
    if(!$db->preparedInsert("insert `tabs` (tabName,tabDescription) values (?,?)",[$data["tabName"],$data["tabDescription"]])){
        $res = $db->preparedInsert("update `tabs` set tabDescription = ? where tabName = ?",[$data["tabName"],$data["tabDescription"]]);
    }
    if ($request->request->get("images") !== null) {
        saveImages($request->request->get("images"));
    }
    if ($request->request->get("deletedImages") !== null) {
        removeImages($request->request->get("deletedImages"));
    }
    return $res;
})
->before($loggedIn)
->before($isAdmin);


return $tab;