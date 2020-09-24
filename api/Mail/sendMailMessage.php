<?php


require_once "Mail.php";


function sendMailtoRecipient($token){
    http_response_code(sendMessageToRecipient($_POST, $token));
}


function sendMailtoAdmin(){
    http_response_code(sendMessageToAdmin($_POST));
}

function sendMessageToAdmin($data){
    $content = 
    $data['email']." heeft een account aangemaakt op de website.<br/>
    Klik <a href='".$_SERVER["HTTP_HOST"]."/login/assignBranch/".$data['email']."'>hier<a/> om het account van deze email te koppelen aan een speltak.<br/>

    ".$_SERVER["HTTP_HOST"]."/login/assignBranch/".$data['email']."
    Als u deze email niet kent dan hoeft u niets te doen.";



    return Mailer::getInstance()->sendMail("Aanmelding bij Scouting Huissen Zand",$content);
}

function sendMessageToRecipient($data, $token){
    $content = "
    U heeft een account aangemaakt op de scouting website.<br/>
    Klik <a href='".$_SERVER["HTTP_HOST"]."/validate/".$data['email']."/".$token."'>hier<a/> om uw account te valideren<br/>
    <br/>
    ".$_SERVER["HTTP_HOST"]."/validate/".$data['email']."/".$token."
    Zodra uw account geverifieerd is wordt er een mail gestuurd naar de administrator.";


    return Mailer::getInstance()->sendMail("Aanmelding bij Scouting Huissen Zand",$content, $data['email'], $data['email']);
}

