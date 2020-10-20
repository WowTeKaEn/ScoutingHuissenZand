<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Silex\Application;

require_once __DIR__."/databaseaccess.php";



$jsonBody = function (Request $request) {
    if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
};

$resultToError = function(Request $request, Response $response){
    if($response->getContent()[0] === "[" || $response->getContent()[0] === "{") return $response;
    if(!$response->getContent()) throw new HttpException(200,"Niets aangepast");
    return new JsonResponse(["message"=>$response->getContent()],$response->getStatusCode());
};

$loggedIn = function(Request $request, Application $app){
    if(!isset($_SESSION["user"])) throw new AccessDeniedHttpException("Je moet ingelogd zijn om deze actie te kunnen doen");
    if(!$_SESSION["user"]["activated"]) throw new AccessDeniedHttpException("Je account moet geactiveerd zijn om deze actie te kunnen doen");

};

$isAdmin = function(Request $request, Application $app){
    if(!$_SESSION["user"]["admin"]) throw new AccessDeniedHttpException("Je moet admin zijn om deze actie te kunnen doen");
};

$ownsBranch = function(Request $request){
    if(!$_SESSION["user"]["admin"] && !sizeof($_SESSION["user"]["branches"]) > 0) throw new AccessDeniedHttpException("Je moet admin zijn of een speltak beheren om deze actie te kunnen doen");
};
