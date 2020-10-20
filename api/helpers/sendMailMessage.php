<?php


require_once "mail.php";

function sendMessageToAdmin($email){
    $content = 
    $email." heeft een account aangemaakt op de website.<br/>
    Klik <a href='".$_SERVER["HTTP_HOST"]."/login/assignBranch/".$email."'>hier<a/> om het account van deze email te koppelen aan een speltak.<br/>

    ".$_SERVER["HTTP_HOST"]."/login/assignBranch/".$email."
    Als u deze email niet kent dan hoeft u niets te doen.";



    return 202 === Mailer::getInstance()->sendMail("Aanmelding bij Scouting Huissen Zand",$content);
}

function sendMessageToRecipient($data, $token){
    $link = $_SERVER["HTTP_HOST"]."/validate/".$data['username']."/".$token;
    $content = "
    U heeft een account aangemaakt op de scouting website.<br/>
    Klik <a href='".$link."'>hier<a/> om uw account te valideren<br/>
    <br/>
    Als u niet op de link kan klikken kunt u deze url kopieren en in de browser plakken:<br/>
    <a href='".$link."'>".$link."<a/>
    Zodra uw account geverifieerd is wordt er een mail gestuurd naar de administrator.";


    return 202 === Mailer::getInstance()->sendMail("Aanmelding bij Scouting Huissen Zand",$content, $data['username'], $data['username']);
}

