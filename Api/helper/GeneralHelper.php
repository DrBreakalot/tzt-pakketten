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
 * Kills if any of the parameters in $parameters is not found in the get parameters. Returns status 400 and and error body to the user if this is the case.
 * @param array $parameters The parameters which should all be present in the get parameters.
 * @return boolean true if successful, doesn't return otherwise
 */
function requireGetParameters(array $parameters) {
    $missingParameters = array();
    foreach ($parameters as $parameterName) {
        if ($_GET[$parameterName] === null) {
            $missingParameters[] = $parameterName;
        }
    }
    if (count($missingParameters) > 0) {
        http_response_code(400);
        echo json_encode(array("error" => "Missing parameter in URL", "parameters" => $missingParameters));
        die;
    }
    return true;
}