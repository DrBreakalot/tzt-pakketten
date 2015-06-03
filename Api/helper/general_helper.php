<?php

/**
 * Fills the global variable $json with parsed json data from the post body
 * @global array $json The data obtained by json decoding the post body
 */
function decodePostBody() {
    global $json;
    if ($json === null) {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
    }
}

/**
 * Kills if none of the methods in $allowedMethods is found. Will return 405 to the user if this is the case
 * @param array $allowedMethods The array of allowed methods
 * @return true if successful, doesn't return otherwise
 */
function requireMethod(array $allowedMethods) {
    if (!$allowedMethods || count($allowedMethods) == 0) {
        return true;
    }
    $method = $_SERVER['REQUEST_METHOD'];
    $allowed = false;
    foreach ($allowedMethods as $allowedMethod) {
        if ($method === $allowedMethod) {
            $allowed = true;
            break;
        }
    }
    if (!$allowed) {
        http_response_code(405);
        echo json_encode(array("error" => "Method $method not allowed", "allowed_methods" => $allowedMethods));
        die;
    }
    return true;
}

/**
 * Kills if any of the variables passed in through the URL is of illegal type
 * @param array $parameters
 * @see requireParameters
 * @return bool true if valid, doesn't return otherwise
 */
function requireGetParameters($parameters) {
    return requireParameters($parameters, $_GET, "Illegal parameter in URL");
}

/**
 * Kills if any of the variables passed in though $body is of illegal type;
 * @param array $parameters
 * @param array $body
 * @see requireParameters
 * @return bool true if valid, doesn't return otherwise
 */
function requirePostParameters($parameters, $body) {    
    return requireParameters($parameters, $body, "Illegal parameter in body");
}

/**
 * Kills if any of the values contained in $parameters is not of a legal type.
 * Presents an error message in the response body
 * Legal types are passed in key => array(legal_types) through $arrayWhichShouldContainParameters
 * Types are as defined by the function gettype() http://php.net/manual/en/function.gettype.php
 * @param array $parameters The array of keys => types
 * @param array $arrayWhichShouldContainParameters The parameters which should be checked by type
 * @param string $errorMessage The message which will be part of the message sent in the response
 * @return boolean true if valid, doesn't return otherwise
 */
function requireParameters($parameters, $arrayWhichShouldContainParameters, $errorMessage) {
    $illegalParameters = array();
    foreach ($parameters as $parameterName => $allowedTypes) {
        $foundParameter = (array_key_exists($parameterName, $arrayWhichShouldContainParameters) ? $arrayWhichShouldContainParameters[$parameterName] : null);
        if (!in_array(gettype($foundParameter), $allowedTypes)) {
            $illegalParameters[$parameterName] = array("found_type" => gettype($foundParameter), "allowed_types" => $allowedTypes);
        }
    }
    if (count($illegalParameters) > 0) {
        http_response_code(400);
        echo json_encode(array("error" => $errorMessage, "parameters" => $illegalParameters));
        die;
    }
    return true;
}

/**
 * Generate a random string of $length characters
 * @param integer $length Length of the random string, default 10
 * @return string The random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Gets a value from an array
 *
 * Doesn't error when the key does not exist
 * @param $key mixed the key of which to retrieve the value
 * @param $array array The array from which to retrieve the value
 * @return mixed the retrieved value, or null if the key does not exist
 */
function getArrayValue($key, $array) {
    if (array_key_exists($key, $array)) {
        return $array[$key];
    } else {
        return null;
    }
}

/**
 * Modifies $address to guarantee a latitude and longitude value
 * @param $address array The address in which to add latitude an longitude
 */
function fillLatitudeLongitude(&$address) {
    if (!array_key_exists("latitude", $address) || $address["latitude"] === null
        || !array_key_exists("longitude", $address) || $address["longitude"] === null) {
        $latlng = getLatLong($address["address"], $address["city"], $address["postal_code"]);
        $address["latitude"] = $latlng["lat"];
        $address["longitude"] = $latlng["lng"];
    }
}

/**
 * Gets the distance between two locations as the crow flies
 * @param $location1 array The first location
 * @param $location2 array The second location
 * @return float The distance in meters between the locations
 */
function distanceBetween(&$location1, &$location2) {
    fillLatitudeLongitude($location1);
    fillLatitudeLongitude($location2);
    return vincentyGreatCircleDistance($location1["latitude"], $location1["longitude"], $location2["latitude"], $location2["longitude"]);
}

/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000.0)
{
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle = atan2(sqrt($a), $b);
    return $angle * $earthRadius;
}