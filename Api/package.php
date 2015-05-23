<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/connection_helper.php';
require_once 'helper/route_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("POST"));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (array_key_exists('package_id', $_GET)) {
        requireUserType(array("Customer", "BackOffice"));
        decodePostBody();
        acceptPackage($json, $_GET['package_id']);
    } else {
        requireUserType(array("Customer"));
        decodePostBody();
        createPackage($json);
    }
}

function acceptPackage($json, $packageId) {
    global $user;
    $package = selectPackage($packageId);
    if ($package['customer_id'] === null) {
        http_response_code(404);
        echo json_encode(array(
            'error' => 'Package not found'
        ));
        die;
    }
    if ($user['type'] === 'Customer') {
        if ($user['id'] !== $package['customer_id']) {
            http_response_code(403);
            echo json_encode(array(
                'error' => 'This package belongs to another customer'
            ));
            die;
        }
    }

    if ($package['state'] !== 'PREPARING') {
        http_response_code(409);
        echo json_encode(array(
            'error' => 'This package has already been accepted or canceled',
        ));
        die;
    }

    $requiredParameters = array(
        'accept' => array('boolean'),
    );
    requirePostParameters($requiredParameters, $json, null);

    $accepted = $json['accept'];
    if ($accepted) {
        $package['state'] = "ACCEPTED";
    } else {
        $package['state'] = "CANCELED";
    }
    updatePackageState($packageId, $package['state']);
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