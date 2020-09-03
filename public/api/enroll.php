<?php

function enroll() {

    if (isset($_POST["branchName"]) &&
        isset($_POST["firstname"]) &&
        isset($_POST["surname"]) &&
        isset($_POST["age"]) &&
        isset($_POST["town"]) &&
        isset($_POST["postcode"]) &&
        isset($_POST["housenumber"]) &&
        isset($_POST["email"]) &&
        isset($_POST["accepted"]) &&
        $_POST["accepted"]
    ) {
        require_once "databaseAccess.php";

        $branches = db::getInstance()->executeQuery("SELECT branchName, branchAdmin FROM `branch` where visible = 1");

        foreach ($branches as $branch) {
            if ($branch["branchName"] == $_POST["branchName"]) {
                require_once "Mail/Mail.php";
                $_POST["id"] = "d-adfcad1f404c4e5db8f2ca0b5177962c";

                $content = "
                Iemand heeft zich ingeschreven voor de ".$_POST["branchName"].".<br/>
                <br/>
                Naam: ".$_POST["firstname"]." ".$_POST["surname"]."<br/>
                Leeftijd: ".$_POST["age"]."<br/>
                Adres: ".$_POST["town"].", ".$_POST["postcode"].", huisnummer: ".$_POST["housenumber"]."<br/>
                Email: ".$_POST["email"]."<br/>
                Telefoon: ".$_POST["phonenumber"]."<br/>
                ";

                http_response_code(Mailer::getInstance()->sendMail("Aanmelding voor ".$_POST["branchName"], $content, $branch["branchName"]."@scoutinghuissenzand.nl", $branch["branchName"]." beheerder"));
                exit;
            }
        }

    }else{
        http_response_code(401);
    }

}
