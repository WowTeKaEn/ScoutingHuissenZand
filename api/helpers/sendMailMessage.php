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



function mapOrder($carry, $order){ 
    return $carry."<br/>".$order["amount"]." ".$order["choice"].","; 
}

function sendOrderClient($data){
    $content = "<h4>U heeft een bestelling geplaatst voor oliebollen.</h4><p>U heeft: " . array_reduce($data["orders"],"mapOrder") . "<br/> besteld.<br/>";
    if($data["type"] === "pickup"){
        $content .= "U kan uw bestelling ophalen op: ". $data["moment"] ." bij het clubgebouw van de scouting. <br/>Adres: Van Wijkstraat 31a, 6851 GL Huissen</p>";
    }else{
        $content .= "U bestelling wordt bezorgt op: ". $data["moment"] ." op adres: ".$data["postcode"].", ".$data["street"]." ".$data["housenumber"]."</p>";
    }
    $content .= "</br><p>Mocht er iets niet kloppen mail dan zo snel mogelijk naar: welpen-huissenzand@live.nl</p>";

    return 202 === Mailer::getInstance()->sendMail("Bestelling van oliebollen",$content, $data["mail"], $data["name"]);
}

function sendOrderAdmin($data){
    $content = "<h4>Er is een bestelling gedaan voor oliebollen.</h4>";
    $content .= "Naam: ".$data["name"]."<br/>";
    $content .= "Email: ".$data["mail"]."<br/>";
    $content .= "Postcode: ".$data["postcode"]."<br/>";
    $content .= "Straat: ".$data["street"]."<br/>";
    $content .= "Huisnummer: ".$data["housenumber"]."<br/>";
    $content .= "Tel: ".$data["phone"]."<br/>";
    $content .= "Bestelling: ".array_reduce($data["orders"],"mapOrder")."<br/>";
    $content .= "Moment: ".$data["moment"]."<br/>";
    $content .= "Type: ".$data["type"]."<br/>";

    return 202 === Mailer::getInstance()->sendMail("Bestelling van oliebollen",$content, "woutkn+test2@gmail.com", $data["name"]);
}