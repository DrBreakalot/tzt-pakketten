<?php

$TZT_API_URL = "http://localhost/api/";

function post($url, $body, $token = null) {
    $ch = curl_init($url);

    $headers = array();
    if ($token) {
        $headers[] = 'TZT_AUTHORIZATION: ' . $token;
    }

    curl_setopt_array($ch, array(
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => json_encode($body),
    ));

    $response = curl_exec($ch);
    $responseData = json_decode($response, true);

    $result = array(
        'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        'body' => $responseData,
    );

    curl_close($ch);

    return $result;
}

function get($url, $token = null) {
    $ch = curl_init($url);

    $headers = array();
    if ($token) {
        $headers[] = 'TZT-AUTHORIZATION: ' . $token;
    }

    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($ch);

    $responseData = json_decode($response, true);

    $result = array(
        'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        'body' => $responseData,
    );

    curl_close($ch);

    return $result;
}