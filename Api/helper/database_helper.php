<?php

require_once dirname(__FILE__).'/../config/config.php';

$db = new PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8', Config::DB_USER, Config::DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function insertLocation($location) {
    global $db;
    $insertStatement = $db->prepare('INSERT INTO `location` (`name`, `latitude`, `longitude`, `is_station`, `address`, `city`, `postal_code`) VALUES (:name, :latitude, :longitude, :is_station, :address, :city, :postal_code)');
    $insertStatement->execute(array(
        ":name" => $location["name"],
        ":latitude" => $location["latitude"],
        ":longitude" => $location["longitude"],
        ":is_station" => $location["is_station"],
        ":address" => $location["address"],
        ":city" => $location["city"],
        ":postal_code" => $location["postal_code"],
    ));
    return $db->lastInsertId();
}

function insertCustomer($customer) {
    global $db;    
    $insertStatement = $db->prepare('INSERT INTO `customer` (`name`, `email`, `address`, `is_business`, `kvk_number`, `password`) VALUES (:name, :email, :address, :is_business, :kvk_number, :password)');
    $insertStatement->execute(array(
        ":name" => $customer["name"],
        ":email" => $customer["email"],
        ":address" => $customer["address"],
        ":is_business" => $customer["is_business"],
        ":kvk_number" => $customer["kvk_number"],
        ":password" => $customer["password"],
    ));
    return $db->lastInsertId();
}

function selectCustomer($customerId) {
    global $db;    
    $customerStatement = $db->prepare("SELECT `id`, `name`, `email`, `is_business`, `kvk_number`, `address` FROM `customer` WHERE `id` = :id");
    $customerStatement->execute(array(':id' => $customerId));
    $user = $customerStatement->fetch(PDO::FETCH_ASSOC);
    
    if ($user["address"] != null) {
        $user["address"] = selectLocation($user["address"]);
    }
    
    return $user;
}

function selectLocation($locationId) {
    global $db;
    $statement = $db->prepare('SELECT `id`, `name`, `latitude`, `longitude`, `address`, `city`, `postal_code`, CASE(`is_station`) WHEN TRUE THEN "Station" ELSE "Address" END AS `type` FROM `location` WHERE `id` = :id');
    $statement->execute(array(':id' => $locationId));
    $location = $statement->fetch(PDO::FETCH_ASSOC);
    
    return $location;
}

function selectStations() {
    global $db;
    $statement = $db->prepare('SELECT `id`, `name`, `latitude`, `longitude`, "Station" AS `type` FROM `location` WHERE `is_station`');
    $statement->execute();
    $stations = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $stations;
}

function selectCouriers() {
    global $db;
    $statement = $db->prepare('SELECT `id`, `name`, `description`, `transit_mode` FROM `courier`');
    $statement->execute();
    $couriers = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($couriers as $key => $courier) {
        $calculatorStatement = $db->prepare('SELECT `id`, `cost`, `per_kilometer`, `start_distance`, `courier_id`, `minimum_distance`, `maximum_distance` FROM `tariffcalculator` WHERE `courier_id` = :courier_id');
        $calculatorStatement->execute(array(
            ':courier_id' => $courier['id'],
        ));
        $calculators = $calculatorStatement->fetchAll(PDO::FETCH_ASSOC);
        $courier['calculators'] = $calculators;
        $couriers[$key] = $courier;
    }

    return $couriers;
}