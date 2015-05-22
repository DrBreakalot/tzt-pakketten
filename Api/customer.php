<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("POST"));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
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
    $parameters["is_business"] = $json["is_business"];
    $parameters["kvk_number"] = $json["kvk_number"];
    $parameters["address"] = null;
    $parameters["password"] = createPassword($json["password"]);

    $addressId = null;

    if ($json["address"] != null) {
        $requiredAddressParameters = array(
            "name" => array("string", "NULL"),
            "latitude" => array("double", "integer", "NULL"),
            "longitude" => array("double", "integer", "NULL"),
            "address" => array("string", "NULL"),
            "city" => array("string", "NULL"),
            "postal_code" => array("string", "NULL"),
        );

        requirePostParameters($json["address"], $requiredAddressParameters);
        $address = array();
        $address["name"] = $json["address"]["name"];
        $address["latitude"] = $json["address"]["latitude"];
        $address["longitude"] = $json["address"]["longitude"];
        $address["address"] = $json["address"]["address"];
        $address["city"] = $json["address"]["city"];
        $address["postal_code"] = $json["address"]["postal_code"];
        $address["isStation"] = false;

        $addressId = insertLocation($address);
        $parameters["address"] = $addressId;
    }


    $customerId = insertCustomer($json);
    $insertedCustomer = selectCustomer($customerId);

    http_response_code(201);
    echo json_encode($insertedCustomer);
}