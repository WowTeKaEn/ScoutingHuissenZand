<?php
function user_logout(){
    session_start();
$_SESSION = [];
session_destroy();
http_response_code(201);
}
?>