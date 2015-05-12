<?php

require_once 'helper/GeneralHelper.php';
require_once 'helper/DatabaseHelper.php';

requireMethod(array("POST"));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    decodePostBody();
    
    $requiredParameters = array(
        "name" => array("string"),
        "email" => array("string"),
        "password" => array("string"),
        "is_business" => array("string"),
        "address" => array("array", "NULL"),
        "kvk_number" => array("string", "NULL"),
    );
    
    requirePostParameters($requiredParameters, $json, null);
    
    global $db;
    $existingCustomerStatement = $db->prepare('SELECT `id` FROM `customer` WHERE `email` = :email');
    $existingCustomerStatement->execute(array(':email' => requireType(array("string"), $json["email"], "email")));
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
        $address["latitude"] = $json["address"]["name"];
        $address["longitude"] = $json["address"]["name"];
        $address["address"] = $json["address"]["name"];
        $address["city"] = $json["address"]["name"];
        $address["postal_code"] = $json["address"]["name"];        
        $address["isStation"] = false;
        
        $addressId = insertLocation($address);
        $parameters["address"] = $addressId;
    }
    
    
    $customerId = insertCustomer($json);
    $insertedCustomer = selectCustomer($customerId);
    
    http_response_code(201);
    echo json_encode($insertedCustomer);
}