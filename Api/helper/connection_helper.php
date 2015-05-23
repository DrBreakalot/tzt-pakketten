<?php

require_once dirname(__FILE__).'/../config/config.php';

function getLatLong($address, $city, $postalCode) {
    $combinedAddress = $address . ", " . $postalCode . " " . $city;

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($combinedAddress) . "&key=" . Config::GOOGLE_API_KEY;
    $context = stream_context_create();
    $result = file_get_contents($url, false, $context);

    if (isset($result)) {
        $parsedResult = json_decode($result, true);

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

function getDistanceTime($from, $to, $mode) {
    $origin = $from["latitude"] . "," . $from["longitude"];
    $destination = $to["latitude"] . "," . $to["longitude"];
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $origin . "&destinations=" . $destination . "&mode=" . $mode . "&key=" . Config::GOOGLE_API_KEY;
    if ($mode === "transit") {
        $url .= "&transit_mode=train";
    }
    $context = stream_context_create();
    $result = file_get_contents($url, false, $context);
    $element = null;
    if (isset($result)) {
        $parsedResult = json_decode($result, true);
        if (isset($parsedResult["rows"])) {
            if (isset($parsedResult["rows"][0]["elements"])) {
                $element = $parsedResult["rows"][0]["elements"][0];
            }
        }
    }
    $duration = null;
    $distance = null;
    if ($element) {
        $duration = $element["duration"]["value"];
        $distance = $element["distance"]["value"];
    }
    return array(
        "duration" => $duration,
        "distance" => $distance / 1000.0,
    );
}