<?php

session_start();

function getLogoutButton($destURL) {
    global $paths;
    $action = "/sfws/login/logout.php";
    
    $returnString = "<div id = 'logoutButton'>";
    $returnString .= "<form action = '$action' method = 'post'>";
    $returnString .= "<input type = 'hidden' name = 'destURL' value = '$destURL'>";
    $returnString .= "<p><input type = 'submit' name = 'submit' value = 'Log out'></form></p></li>";
    $returnString .= "</div>";
    return $returnString;
}

function adminUserIsAuthenticated() {
    $validUser = "http://www.facebook.com/profile.php?id=1624323223";
    return ($_SESSION['auth_info']["profile"]["identifier"] === $validUser);
}

// $destURL is the page that the user should be sent back to
// after authenticating
function getLoginMessage($destURL) {
    $returnString = "<p>You must be logged in to use this page.</p>";
    $returnString .= getSocialLoginButton($destURL);
    
    return $returnString;
}

function getSocialLoginButton($destURL){
    global $paths;
    $action = $paths["home"]["web"] . "/login/login.php";
    $returnString = "<div id = 'socialLoginForm'>";
    $returnString .= "<form action = '$action' method = 'post'>";
    $returnString .= "<input type = 'hidden' name = 'destURL' value = '$destURL'>";
    $returnString .= "<p><input type = 'submit' name = 'submit' value = 'Log in with a web account'></form></p>";
    $returnString .= "</div>";

    return $returnString;
}