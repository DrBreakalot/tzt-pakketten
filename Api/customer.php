<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("GET", "POST"));

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    requireUserType(array("BackOffice", "Customer"));
    if ($user['type'] === 'BackOffice') {
        requireGetParameters(array('customer_id' => array('string')));
        echo json_encode(selectCustomer($_GET['customer_id']));
    } else {
        echo json_encode(selectCustomer($user['id']));
    }
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
    decodePostBody();

    createCustomer($json);
}

function createCustomer($json) {
    $requiredParameters = array(
        "name" => array("string"),
        "email" => array("string"),
        "password" => array("string"),
        "is_business" => array("boolean"),
        "address" => array("array", "NULL"),
        "kvk_number" => array("string", "NULL"),
    );

    requirePostParameters($requiredParameters, $json, null);

    global $db;
    $existingCustomerStatement = $db->prepare('SELECT `id` FROM `customer` WHERE `email` = :email');
    $existingCustomerStatement->execute(array(':email' => $json['email']));
    if ($existingCustomerStatement->rowCount() > 0) {
        http_response_code(409);
        $existingCustomer = $existingCustomerStatement->fetch(PDO::FETCH_ASSOC);
        echo json_encode(array("error" => "User already exists", "id" => $existingCustomer["id"]));
        die;
    }

    $parameters = array();
    $parameters["name"] = $json["name"];
    $parameters["email"] = $json["email"];
    $parameters["is_business"] =  $json["is_business"];
    $parameters["kvk_number"] = getArrayValue("kvk_number", $json);
    $parameters["address"] = null;
    $parameters["password"] = createPassword($json["password"]);

    $addressId = null;

    if (getArrayValue("address", $json) !== null) {
        $requiredAddressParameters = array(
            "name" => array("string", "NULL"),
            "latitude" => array("double", "integer", "NULL"),
            "longitude" => array("double", "integer", "NULL"),
            "address" => array("string", "NULL"),
            "city" => array("string", "NULL"),
            "postal_code" => array("string", "NULL"),
        );

        requirePostParameters($requiredAddressParameters, $json["address"]);
        $address = array();
        $address["name"] = getArrayValue('name', $json['address']);
        $address["latitude"] = getArrayValue('latitude', $json['address']);
        $address["longitude"] = getArrayValue('longitude', $json['address']);
        $address["address"] = getArrayValue('address', $json['address']);
        $address["city"] = getArrayValue('city', $json['address']);
        $address["postal_code"] = getArrayValue('postal_code', $json['address']);
        $address["is_station"] = false;

        $addressId = insertLocation($address);
        $parameters["address"] = $addressId;
    }


    $customerId = insertCustomer($parameters);
    $insertedCustomer = selectCustomer($customerId);

    http_response_code(201);
    echo json_encode($insertedCustomer);
}