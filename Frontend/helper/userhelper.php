<?php

require_once 'authhelper.php';
require_once 'connectionhelper.php';

function getUser() {
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

function registerCustomer($name, $email, $password, $address = null, $isBusiness = false, $kvk_number = null) {
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