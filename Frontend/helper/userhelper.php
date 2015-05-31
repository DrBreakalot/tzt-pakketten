<?php

require_once 'authhelper.php';
require_once 'connectionhelper.php';

function getUser()
{
    global $TZT_API_URL;

    $token = getTokenFromCookie();
    if (!$token) {
        return null;
    }

    $url = $TZT_API_URL . 'user.php';

    $response = get($url, $token);

    if ($response['status'] === 200) {
        return $response['body'];
    } else {
        return null;
    }
}

function registerCustomer($name, $email, $password, $address = null, $isBusiness = false, $kvk_number = null)
{
    global $TZT_API_URL;
    $url = $TZT_API_URL . 'customer.php';

    $body = array(
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'address' => $address,
        'is_business' => $isBusiness,
        'kvk_number' => $kvk_number
    );

    $response = post($url, $body);

    return $response;
}

function getPackages()
{
    global $TZT_API_URL;

    $token = getTokenFromCookie();
    if (!$token) {
        return null;
    }

    $url = $TZT_API_URL . 'packages.php';

    $response = get($url, $token);

    if ($response['status'] === 200) {
        return $response['body'];
    } else {
        return null;
    }
}

function getPackage($id) {
    global $TZT_API_URL;

    $token = getTokenFromCookie();
    if (!$token) {
        return null;
    }

    $url = $TZT_API_URL . 'package.php?package_id=' . $id;

    $response = get($url, $token);

    if ($response['status'] === 200) {
        return $response['body'];
    } else {
        return null;
    }
}

function acceptPackage($id, $accept) {
    global $TZT_API_URL;

    $token = getTokenFromCookie();
    if (!$token) {
        return null;
    }

    $url = $TZT_API_URL . 'package.php?package_id=' . $id;

    $body = array(
        'accept' => $accept,
    );
    $response = post($url, $body, $token);

    if ($response['status'] === 200) {
        return $response['body'];
    } else {
        return null;
    }

}

function getMenu()
{
    $token = getTokenFromCookie();
    if ($token) {
        return '<li><a href="index.php" title="Home">Home</a></li>'
        . '<li><a href="onzediensten.php" title="Onze diensten">Onze diensten</a></li>'
        . '<li><a href="mijnpakketten.php"" title="Mijn Pakket">Mijn Pakket</a></li>'
        . '<li><a href="contact.php" title="Contact">Contact</a></li>'
        . '<li><a href="ingelogdeUser.php?loguit=true" title="Uitloggen">Uitloggen</a></li>';
    } else {
        return '<li><a href="index.php" title="Home">Home</a></li>'
        . '<li><a href="onzediensten.php" title="Onze diensten">Onze diensten</a></li>'
        . '<li><a href="mijnpakketten.php"" title="Mijn Pakket">Mijn Pakket</a></li>'
        . '<li><a href="contact.php" title="Contact">Contact</a></li>'
        . '<li><a href="treinkoerierStart.php" title="Treinkoerier">Treinkoerier</a></li>';
    }

}