<?php

require_once dirname(__FILE__).'/../config/config.php';
require_once dirname(__FILE__).'/general_helper.php';

$db = new PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8', Config::DB_USER, Config::DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function insertLocation($location) {
    global $db;
    $insertStatement = $db->prepare('INSERT INTO `location` (`name`, `latitude`, `longitude`, `is_station`, `address`, `city`, `postal_code`) VALUES (:name, :latitude, :longitude, :is_station, :address, :city, :postal_code)');
    $insertStatement->execute(array(
        ":name" => getArrayValue('name', $location),
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

function insertPackage($package) {
    global $db;

    $routeId = insertRoute($package['route']);

    $insertStatement = $db->prepare('INSERT INTO `package` (`width`, `height`, `depth`, `weight`, `barcode`, `enter_date`, `paid_price`, `state`, `customer_id`, `route_id`, `invoice_number`) VALUES (:width, :height, :depth, :weight, :barcode, :enter_date, :paid_price, :state, :customer_id, :route_id, :invoice_number)');
    $insertStatement->execute(array(
        ':width' => $package['width'],
        ':height' => $package['height'],
        ':depth' => $package['depth'],
        ':weight' => $package['weight'],
        ':barcode' => getArrayValue('barcode', $package),
        ':enter_date' => getArrayValue('enter_date', $package),
        ':paid_price' => getArrayValue('paid_price', $package),
        ':state' => $package['state'],
        ':customer_id' => $package['customer']['id'],
        ':route_id' => $routeId,
        ':invoice_number' => null,
    ));
    return $db->lastInsertId();
}

function insertRoute($route) {
    global $db;

    $fromId = null;
    $toId = null;
    if (getArrayValue('id', $route['from']) === null) {
        $fromId = insertLocation($route['from']);
    } else {
        $fromId = $route['from']['id'];
    }
    if (getArrayValue('id', $route['to']) === null) {
        $toId = insertLocation($route['to']);
    } else {
        $toId = $route['to']['id'];
    }

    $insertStatement = $db->prepare('INSERT INTO `route` (`cost`, `start`, `from_address`, `to_address`) VALUES (:cost, :start, :from_address, :to_address)');
    $insertStatement->execute(array(
        ':cost' => $route['cost'],
        ':start' => null,
        ':from_address' => $fromId,
        ':to_address' => $toId,
    ));
    $routeId = $db->lastInsertId();

    foreach ($route['legs'] as $routeLeg) {
        insertRouteLeg($routeLeg, $routeId);
    }

    return $routeId;
}

function insertRouteLeg($routeLeg, $routeId) {
    global $db;

    if (!array_key_exists('id', $routeLeg['from']) || $routeLeg['from']['id'] === null) {
        $fromId = insertLocation($routeLeg['from']);
    } else {
        $fromId = $routeLeg['from']['id'];
    }
    if (!array_key_exists('id', $routeLeg['to']) || $routeLeg['to']['id'] === null) {
        $toId = insertLocation($routeLeg['to']);
    } else {
        $toId = $routeLeg['to']['id'];
    }

    $courier = getArrayValue('courier', $routeLeg);
    $trainCourier = getArrayValue('train_courier', $routeLeg);

    $insertStatement = $db->prepare('INSERT INTO `routeleg` (`cost`, `duration`, `start`, `is_actual`, `route_id`, `from_location_id`, `to_location_id`, `courier_id`, `train_courier_id`) VALUES (:cost, :duration, :start, :is_actual, :route_id, :from_location_id, :to_location_id, :courier_id, :train_courier_id)');
    $insertStatement->execute(array(
        ':cost' => $routeLeg['cost'],
        ':duration' => getArrayValue('duration', $routeLeg),
        ':start' => null,
        ':is_actual' => (getArrayValue('is_actual', $routeLeg) ? getArrayValue('is_acutal', $routeLeg) : false),
        ':route_id' => $routeId,
        ':from_location_id' => $fromId,
        ':to_location_id' => $toId,
        ':courier_id' => ($courier ? $courier['id'] : null),
        ':train_courier_id' => ($trainCourier ? $trainCourier['id'] : null),
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