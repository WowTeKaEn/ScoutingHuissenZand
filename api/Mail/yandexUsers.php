<?php

function create_account($branchName, $branchAdmin) {
    $password = randomPassword();
    $data     = array(
        "gender"        => "male",
        "is_admin"      => false,
        "nickname"      => $branchName,
        "password"      => $password,
        "department_id" => 1,
        "birthday" => "1990-10-10",
        "name"          => array(
            "first"  => $branchName,
            "last"   => "Huissen Zand",
            "middle" => "",
        ),
        "language"=> "en"
    );
    $ch = user_curl_init();
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $output   = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpcode == 201) {
        return send_account_details($branchName, $branchAdmin, $password);
    }
    return false;
}

function update_user($branchName, $dismiss = false, $branchAdmin = "") {
    $branchName = strtolower($branchName);
    $data       = array("is_dismissed" => $dismiss,"language"=> "en");
    $ch         = user_curl_init();
    $password   = "";
    $userId     = get_user_id($branchName);
    if ($userId == false) {
        create_account($branchName,$branchAdmin);
        $endpoint = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_setopt($ch, CURLOPT_URL, $endpoint . get_user_id($branchName) . "/");
    }else{
        $endpoint = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_setopt($ch, CURLOPT_URL, $endpoint . $userId . "/");
    }
    if (!$dismiss) {
        $password         = randomPassword();
        $data["password"] = $password;
    }
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_exec($ch);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpcode == 200 && !$dismiss) {
        return send_account_details($branchName, $branchAdmin, $password);
    }
    if($httpcode == 401){
        http_response_code(405);
        exit;
    }

    return $httpcode == 200;
}

function send_account_details($branchName, $branchAdmin, $password) {
    require_once "Mail/Mail.php";
    $temp = Mailer::getInstance()->sendMail("Inlog gegevens voor email account", "
    <p>Gebruikersnaam: " . $branchName . "@scoutinghuissenzand.nl<br>
    Wachtwoord: " . $password . "<br>
    Mail portaal waar het wachtwoord geldt: <a href='https://mail.yandex.com/'>https://mail.yandex.com/</a><br>
    Voor het makkelijk doorsturen van emails van Yandex naar Gmail, Hotmail of een andere mail service volg <a href='https://yandex.com/support/mail/web/preferences/filters/forwarding.html'>deze<a/> uitleg.<br/>
    Verander na inloggen meteen je wachtwoord.<br><br>
    Gebruik dit email adres niet voor persoonlijke doeleinden.<br>
    De admin kan mocht het nodig zijn ook bij dit email adres.
    </p>", $branchAdmin, ucfirst($branchName) . " Admin");
    var_dump($temp);
    if ($temp !== 202) {
        
        return false;
    }
    return true;
}

function get_user_id($branchName) {
    $users = get_users();
    if ($users) {
        foreach ($users["result"] as $user) {
            if ($branchName == $user["nickname"]) {
                return $user["id"];
            }
        }
    }
    return false;
}

function randomPassword() {
    $alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass        = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 20; $i++) {
        $n      = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function get_users() {
    $ch       = user_curl_init();
    $endpoint = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $params   = array('fields' => 'nickname');
    $url      = $endpoint . '?' . http_build_query($params);
    curl_setopt($ch, CURLOPT_URL, $url);
    $output   = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpcode == 200) {
        return json_decode($output, true, 512, JSON_BIGINT_AS_STRING);
    }else if($httpcode == 401){
        http_response_code(405);
        exit;
    }

    return false;
}

function user_curl_init() {
    $ch = curl_init("https://api.directory.yandex.net/v6/users/");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($_ENV["YANDEX_ORG_ID"], 'Authorization: '.$_ENV["YANDEX_OAUTH"], 'Content-Type: application/json'));
    return $ch;
}