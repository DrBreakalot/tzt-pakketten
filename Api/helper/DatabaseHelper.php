<?php

require_once dirname(__FILE__).'/../config/config.php';

$db = new PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8', Config::DB_USER, Config::DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function insertLocation($location) {
    global $db;
    $insertStatement = $db->prepare('INSERT INTO `location` (`name`, `latitude`, `longitude`, `is_station`, `address`, `city`, `postal_code`) VALUES (:name, :latitude, :longitude, :is_station, :address, :city, :postal_code)');
    $insertStatement->execute($location);    
    return $db->lastInsertId();
}

function insertCustomer($customer) {
    global $db;    
    $insertStatement = $db->prepare('INSERT INTO `customer` (`name`, `email`, `address`, `is_business`, `kvk_number`, `password`) VALUES (:name, :email, :address, :is_business, :kvk_number, :password)');
    $insertStatement->execute($customer);    
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
    $statement = $db->prepare('SELECT `name`, `latitude`, `longitude`, `address`, `city`, `postal_code`, CASE(`is_station`) WHEN TRUE THEN "Station" ELSE "Address" END AS `is_station` FROM `location` WHERE `id` = :id');
    $statement->execute(array(':id' => $locationId));
    $location = $statement->fetch(PDO::FETCH_ASSOC);
    
    return $location;
}