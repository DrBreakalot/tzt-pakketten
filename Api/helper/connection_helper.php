<?php

require_once dirname(__FILE__).'/../config/config.php';

/*
$url = 'URL';
$data = array('field1' => 'value', 'field2' => 'value');
$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
var_dump($result);
 */

function getLatLong($address, $city, $postalCode) {
    $combinedAddress = $address . ", " . $postalCode . " " . $city;

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $combinedAddress . "&key=" . Config::GOOGLE_API_KEY;
    $context = stream_context_create();
    $result = file_get_contents($url, false, $context);

    if (isset($result)) {
        $parsedResult = json_decode($result);

        if (isset($parsedResult["results"])) {
            $results = $parsedResult["results"];
            $firstLocation = $results[0];
            return $firstLocation["geometry"]["location"];
        } else {
            echo($result);
        }
    } else {
        echo "HELP";
    }
}