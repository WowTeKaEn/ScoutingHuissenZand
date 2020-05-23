<?php

function cleanStrings($stringArray){
    foreach($stringArray as $string=>$key){
        $stringArray[$key] = strip_tags($string,"<strong><em><br><br/><i><bold>");
    }
    return $stringArray;
}

function cleanNonAllowedStrings($stringArray,$allowedStrings){
    foreach($stringArray as $string=>$key){
        $stringArray[$key] = strip_tags($string,$allowedStrings);
    }
    return $stringArray;
}

?>