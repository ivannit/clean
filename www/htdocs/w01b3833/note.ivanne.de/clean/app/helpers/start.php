<?php

// accept only SSL
if (!isset($_SERVER["HTTPS"]) or $_SERVER["HTTPS"] == "off") {
    $redirect_url = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    header("Location: $redirect_url");
    exit();
}

session_start();

function isLoggedIn() {
    if (isset($_SESSION['userid'])) {
        return true;
    } else {
        return false;
    }
}

function currentActive($view, $menu) {
    if ($view == $menu) {
        echo ' class="active" ';
    }
}