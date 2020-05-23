<?php
include_once "databaseAccess.php";
require_once "./Mail/sendMailMessage.php";

function user_signup(){





if (isset($_POST['password']) && isset($_POST['email'])) {
    $post      = cleanNonAllowedStrings($_POST, " ");
    $pass      = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $token     = md5(uniqid(rand(), true));
    $pdoResult = db::getInstance()->preparedInsert("INSERT INTO `user` (Email, Password, validateToken) VALUES (?,?,?)", [strip_tags($post['email']), $pass, $token]);
    if ($pdoResult) {
        sendMailtoRecipient($token);
        if(http_response_code() === 202){
            http_response_code(201);
        }else{
            $pdoResult = db::getInstance()->preparedInsert("delete from `user` where email = ?", [strip_tags($post['email'])]);
        }
    } else {
        http_response_code(409);
    }
} else {
    http_response_code(400);
}
}
?>