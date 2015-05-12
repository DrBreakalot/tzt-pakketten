<?php

/**
 * Fills the global variable $json with parsed json data from the post body
 * @global type $json The data obtained by json decoding the post body
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
        echo json_encode(array("error" => "Method not allowed", "allowed_methods" => $allowedMethods));
        die;
    }
    return true;
}

/**
 * Kills if any of the variables passed in through the URL is of illegal type
 * @param array $parameters
 * @see requireParameters
 */
function requireGetParameters($parameters) {
    return requireParameters($parameters, $_GET, "Illegal parameter in URL");
}

/**
 * Kills if any of the variables passed in though $body is of illegal type;
 * @param array $parameters
 * @param array $body
 * @see requireParameters
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
        if (!in_array(gettype($arrayWhichShouldContainParameters[$parameterName]), $allowedTypes)) {
            $illegalParameters[$parameterName] = array("found_type" => gettype($arrayWhichShouldContainParameters[$parameterName]), "allowed_types" => $allowedTypes);
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