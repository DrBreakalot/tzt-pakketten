<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("POST"));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    decodePostBody();
    
    $requiredParameters = array(
        "email" => array("string"),
        "password" => array("string"),
        "type" => array("string"),
    );
    requirePostParameters($requiredParameters, $json);
    
    $type = $json["type"];
    $token = false;
    if ($type === "CUSTOMER") {
        $token = loginCustomer($json["email"], $json["password"]);
    } else if ($type === "TRAIN") {
        $token = loginTrain($json["email"], $json["password"]);
    } else if ($type === "OFFICE") {
        $token = loginOffice($json["email"], $json["password"]);
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "Unknown type: $type"));
        die;
    }
    
    if ($token) {
        http_response_code(201);
        echo json_encode(array("token" => $token));
    } else {
        http_response_code(401);
        echo json_encode(array("error" => "Unknown combination of user and password"));
    }
}