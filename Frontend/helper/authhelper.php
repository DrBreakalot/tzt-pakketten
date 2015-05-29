<?php
require_once 'connectionhelper.php';
require_once 'userhelper.php';

function getTokenFromCookie() {
    if (array_key_exists('tzt-auth', $_COOKIE)) {
        return $_COOKIE["tzt-auth"];
    } else {
        return null;
    }
}

function setTokenToCookie($token) {
    setcookie("tzt-auth", $token);
}

function login($email, $password) {
    global $TZT_API_URL;

    $postData = array(
        'type' => 'CUSTOMER',
        'email' => $email,
        'password' => $password
    );
    $url = $TZT_API_URL . 'auth.php';

    $response = post($url, $postData);
    if ($response['status'] === 201) {
        $body = $response['body'];
        $token = $body['token'];
        setTokenToCookie($token);
        return true;
    } else {
        return false;
    }
}

function logout() {
    setTokenToCookie(null);
}

function requireUser() {
    $user = getUser();
    if (!$user) {
        setTokenToCookie(null);
        header('Location: inlogscherm.php');
        die;
    }
    return $user;
}