<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("POST"));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    decodePostBody();

    createTrainCourier($json);
}

/**
 * Creates a train courier
 * @param $json array The json body
 */
function createTrainCourier($json) {
    $requiredParameters = array(
        "name" => array("string"),
        "email" => array("string"),
        "password" => array("string"),
        "bank_account" => array("string"),
        "address" => array("array", "NULL"),
    );

    requirePostParameters($requiredParameters, $json, null);

    global $db;
    $existingCourierStatement = $db->prepare('SELECT `id` FROM `traincourier` WHERE `email` = :email');
    $existingCourierStatement->execute(array(':email' => $json['email']));
    if ($existingCourierStatement->rowCount() > 0) {
        http_response_code(409);
        $existingCourier = $existingCourierStatement->fetch(PDO::FETCH_ASSOC);
        echo json_encode(array("error" => "User already exists", "id" => $existingCourier["id"]));
        die;
    }

    $parameters = array();
    $parameters["name"] = $json["name"];
    $parameters["email"] = $json["email"];
    $parameters["bank_account"] = $json["bank_account"];
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

    $trainCourier = insertCustomer($parameters);
    $insertedTrainCourier = selectCustomer($trainCourier);

    http_response_code(201);
    echo json_encode($insertedTrainCourier);
}