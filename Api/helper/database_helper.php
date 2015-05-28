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

function insertTrainCourier($trainCourier) {
    global $db;
    $insertStatement = $db->prepare('INSERT INTO `traincourier` (`name`, `email`, `address`, `bank_account`, `password`) VALUES (:name, :email, :address, :bank_account, :password)');
    $insertStatement->execute(array(
        ":name" => $trainCourier["name"],
        ":email" => $trainCourier["email"],
        ":address" => $trainCourier["address"],
        ":bank_account" => $trainCourier["bank_account"],
        ":password" => $trainCourier["password"],
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
    $user['is_business'] = boolval($user['is_business']);
    
    return $user;
}

function selectTrainCourier($trainCourierId) {
    global $db;
    $courierStatement = $db->prepare('SELECT `id`, `name`, `email`, `bank_account`, `address` FROM `traincourier` WHERE `id` = :id');
    $courierStatement->execute(array('id' => $trainCourierId));

    $courier = $courierStatement->fetch(PDO::FETCH_ASSOC);

    if ($courier['address'] != null) {
        $courier['address'] = selectLocation($courier['address']);
    }
    $courier['sections'] = selectSectionsForTrainCourier($courier['id']);

    return $courier;
}

function selectCustomers() {
    global $db;
    $customerStatement = $db->prepare("SELECT `id`, `name`, `email`, `is_business`, `kvk_number`, `address` FROM `customer`");
    $customerStatement->execute();
    $users = $customerStatement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $key => $user) {
        if ($user["address"] != null) {
            $user["address"] = selectLocation($user["address"]);
        }
        $user['is_business'] = boolval($user['is_business']);
        $users[$key] = $user;
    }

    return $users;
}

function selectTrainCouriers() {
    global $db;
    $courierStatement = $db->prepare('SELECT `id`, `name`, `email`, `bank_account`, `address` FROM `traincourier`');
    $courierStatement->execute();

    $couriers = $courierStatement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($couriers as $key => $courier) {
        if ($courier['address'] != null) {
            $courier['address'] = selectLocation($courier['address']);
        }
        $courier['sections'] = selectSectionsForTrainCourier($courier['id']);
        $couriers[$key] = $courier;
    }

    return $couriers;
}

function selectSectionsForTrainCourier($trainCourierId) {
    global $db;
    $sectionStatement = $db->prepare('SELECT `id`, `depature_time` `from_station`, `to_station`, `repeating` FROM `section` WHERE `courier` = :courier_id');
    $sectionStatement->execute(array(
        ':courier_id' => $trainCourierId,
    ));
    $sections = $sectionStatement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($sections as $key => $section) {
        $section['from_station'] = selectLocation($section['from_station']);
        $section['to_station'] = selectLocation($section['to_station']);

        $sections[$key] = $section;
    }

    return $sections;
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

function selectPackage($packageId) {
    global $db;
    $packageStatement = $db->prepare('SELECT * FROM `package` WHERE `id` = :id');
    $packageStatement->execute(array(
        ':id' => $packageId,
    ));
    $package = $packageStatement->fetch(PDO::FETCH_ASSOC);

    if ($package === null) {
        return null;
    }

    $route = selectRoute($package['route_id']);
    $package['route'] = $route;

    return $package;
}

function selectPackages($customerId) {
    global $db;
    $packageStatement = $db->prepare('SELECT * FROM `package` WHERE `customer_id` = :customer_id');
    $packageStatement->execute(array(
        ':customer_id' => $customerId,
    ));
    $packages = $packageStatement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($packages as $key => $package) {
        $route = selectRoute($package['route_id']);
        $package['route'] = $route;
        $packages[$key] = $package;
    }

    return $packages;
}

function selectRoute($routeId) {
    global $db;
    $routeStatement = $db->prepare('SELECT * FROM `route` WHERE `id` = :id');
    $routeStatement->execute(array(
        ':id' => $routeId,
    ));
    $route = $routeStatement->fetch(PDO::FETCH_ASSOC);

    if ($route === null) {
        return null;
    }

    $route['legs'] = selectRouteLegsWithRouteId($routeId);
    $route['from'] = selectLocation($routeId['from_address']);
    $route['to'] = selectLocation($routeId['to_address']);

    return $route;
}

function selectRouteLegsWithRouteId($routeId) {
    global $db;
    $legStatement = $db->prepare('SELECT * FROM `routeleg` WHERE `route_id` = :route_id');
    $legStatement->execute(array(
        ':route_id' => $routeId,
    ));
    $routeLegs = $legStatement->fetchAll(PDO::FETCH_ASSOC);

    if ($routeLegs === null) {
        return null;
    }

    foreach ($routeLegs as $key => $routeLeg) {
        $routeLeg['to'] = selectLocation($routeLeg['to_location_id']);
        $routeLeg['from'] = selectLocation($routeLeg['from_location_id']);
        $routeLegs[$key] = $routeLeg;
    }

    return $routeLegs;
}

function updatePackageState($packageId, $packageState) {
    global $db;
    $update = $db->prepare('UPDATE `package` SET `state` = :state WHERE `id` = :id');
    $update->execute(array(
        ':state' => $packageState,
        ':id' => $packageId,
    ));
}