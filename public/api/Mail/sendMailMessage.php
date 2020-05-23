<?php


require_once "Mail.php";


function sendMailtoRecipient($token){
    http_response_code(sendMessageToRecipient($_POST, $token));
}


function sendMailtoAdmin(){
    http_response_code(sendMessageToAdmin($_POST));
}

function sendMessageToAdmin($data){
    return sendGridMail::getInstance()->sendMail(false,false,["id" => "d-4bf06958b0b44e1fbe9366e0d748936d",'email' => $data['email']]);
}

function sendMessageToRecipient($data, $token){
    return sendGridMail::getInstance()->sendMail(false,false,["id" => "d-a9071f00e82340b6b642c75f29b86504",'email' => $data['email'] ,'token'=> $token], $data['email'], $data['email']);
}

