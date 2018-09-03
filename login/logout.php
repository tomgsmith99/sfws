<?php

session_start();

include "/var/www/html/sfws/includes/includes.php";

$_SESSION = array();

// Code from http://php.net/manual/en/function.session-destroy.php
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

if (empty($_POST["destURL"])) { $destURL = $paths["home"]["web"]; }
else { $destURL = $_POST["destURL"]; }

$redirectString = 'Location: ' . $destURL;

header( $redirectString ) ;