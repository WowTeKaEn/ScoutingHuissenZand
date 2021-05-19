<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

$user = $app['controllers_factory'];

$user->get('/user', function () {
    return $_SESSION["user"];
})
->before($loggedIn);

$user->post('/user', function (Request $request) use ($db) {
    $body = checkBody($request, ["username","password"]);
    $user = $db->preparedQuery("SELECT email, Password as 'password', activated, admin FROM `user` WHERE email = ?", [$body["username"]]);

    if (!$user || !password_verify($body["password"], $user[0]["password"])) {
        throw new HttpException(401, "Gebruikersnaam en wachtwoord combinatie niet bekend");
    }
    if (!$user[0]["activated"]) {
        throw new HttpException(403, "Gebruiker niet gevalideerd");
    }

    unset($user["password"]);
    $_SESSION["user"] = $user[0];
    $_SESSION["user"]["branches"] = $db->preparedQuery("SELECT branchName FROM `branch` WHERE branchAdmin = ?", [$body["username"]]);

    return $_SESSION["user"];
});


$user->delete('/user', function () {
    if (isset($_SESSION["user"])) {
        session_destroy();
        return "uitgelogd";
    } else {
        throw new HttpException(403, "Niet ingelogd");
    }
});


$user->post('/user/signup', function (Request $request) use ($db) {
    $body = checkBody($request, ["username","password"]);
    $pass      = password_hash($body["password"], PASSWORD_DEFAULT);
    $token     = md5(uniqid(rand(), true));
    $res = $db->preparedInsert("INSERT INTO `user` (Email, Password, validateToken) VALUES (?,?,?)", [$body['username'], $pass, $token]);
    if (!$res) {
        throw new HttpException(400, "Email bestaat al");
    }
    require_once __DIR__."/../helpers/sendMailMessage.php";
    if (!sendMessageToRecipient($body, $token)) {
        throw new HttpException(500, "Validatie email kon niet worden verstuurd");
        $db->preparedInsert("delete from `user` where email = ?", [$body['username']]);
    }
    return new Response("Account aangemaakt. Er is een email verstuurd naar uw email voor validatie (dit kan 10 minuten duren)", 201);
});


$user->get('/user/all', function () use ($db) {
    return $db->preparedQuery("SELECT email, activated, admin, (not exists (select 1 from branch where branchAdmin = u.email)) as deletable FROM user u where email != ?", [$_SESSION["user"]["email"]]);
})
->before($loggedIn)
->before($isAdmin);

$user->delete('/user/{email}', function ($email) use ($db) {
    return $db->preparedInsert("DELETE from user WHERE email = ?", [$email]);
})
->before($loggedIn)
->before($isAdmin);

$user->put('/user/promote/{email}', function ($email) use ($db) {
    return $db->preparedInsert("UPDATE user set admin = not admin WHERE email = ?", [$email]);
})
->before($loggedIn)
->before($isAdmin);

$user->put('/user/validate/{email}/{token}', function ($email, $token) use ($db) {
    $res = $db->preparedInsert("UPDATE user set activated = 1 WHERE email = ? and validateToken = ?", [$email, $token]);
    if ($res) {
        sendMessageToAdmin($email);
    }
    return $res;
});


$user->put('/user/validate/{email}', function ($email) use ($db) {
    return $db->preparedInsert("UPDATE user set activated = not activated WHERE email = ?", [$email]);
})
->before($loggedIn)
->before($isAdmin);


return $user;
