<?php

require_once 'authhelper.php';
require_once 'connectionhelper.php';

function getUser() {
    global $TZT_API_URL;

    $token = getTokenFromCookie();
    if (!$token) {
        return null;
    }

    $url = $TZT_API_URL . 'customer.php';

    $response = get($url, $token);

    if ($response['status'] === 200) {
        return $response['body'];
    } else {
        return null;
    }
}