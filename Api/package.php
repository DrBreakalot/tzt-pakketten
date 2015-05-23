<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/connection_helper.php';
require_once 'helper/route_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("POST"));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    requireUserType(array("Customer"));

    decodePostBody();

    createPackage($json);
}

function createPackage($json) {
    $requiredParameters = array(
        "width" => array("integer", "double"),
        "height" => array("integer", "double"),
        "depth" => array("integer", "double"),
        "weight" => array("integer", "double"),
        "from" => array("array"),
        "to" => array("array"),
    );
    requirePostParameters($requiredParameters, $json, null);

    $fromAddress = $json["from"];
    $toAddress = $json["to"];

    $requiredAddressParameters = array(
        "name" => array("string", "NULL"),
        "address" => array("string"),
        "city" => array("string"),
        "postal_code" => array("string"),
        "latitude" => array("integer", "double", "NULL"),
        "longitude" => array("integer", "double", "NULL"),
    );
    requirePostParameters($requiredAddressParameters, $json["from"], "from");
    requirePostParameters($requiredAddressParameters, $json["to"], "to");

    $fromAddress['is_station'] = false;
    $toAddress['is_station'] = false;

    $route = calculateRoute($fromAddress, $toAddress);

    global $user;

    $package = $json;
    $package['route'] = $route;
    $package['customer'] = $user;
    $package['state'] = 'PREPARING';
    $package['paid_price'] = $route['cost'];

    $packageId = insertPackage($package);

    http_response_code(201);
    echo json_encode(array(
        'package_id' => $packageId,
        'price' => $package['paid_price']
    ));
}