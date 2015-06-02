<?php

require_once dirname(__FILE__).'/../config/config.php';
require_once dirname(__FILE__).'/general_helper.php';

$db = new PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8', Config::DB_USER, Config::DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

/**
 * Inserts a location into the database
 * @param $location array An array containing the location
 * @return string The id of the inserted location
 */
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

/**
 * Inserts a customer into the database
 * @param $customer array An array containing a customer
 * @return string The id of the inserted customer
 */
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

/**
 * Inserts a train courier into the database
 * @param $trainCourier array An array containing the train courier
 * @return string The id of the inserted train courier
 */
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

/**
 * Inserts a package into the database
 * @param $package array An array containing a package
 * @return string The id of the inserted package
 */
function insertPackage($package) {
    global $db;

    $routeId = insertRoute($package['route']);

    $insertStatement = $db->prepare('INSERT INTO `package` (`width`, `height`, `depth`, `weight`, `barcode`, `enter_date`, `paid_price`, `state`, `customer_id`, `route_id`, `invoice_number`) VALUES (:width, :height, :depth, :weight, :barcode, NOW(), :paid_price, :state, :customer_id, :route_id, :invoice_number)');
    $insertStatement->execute(array(
        ':width' => $package['width'],
        ':height' => $package['height'],
        ':depth' => $package['depth'],
        ':weight' => $package['weight'],
        ':barcode' => getArrayValue('barcode', $package),
        //':enter_date' => date('r', getArrayValue('enter_date', $package)),
        ':paid_price' => getArrayValue('paid_price', $package),
        ':state' => $package['state'],
        ':customer_id' => $package['customer']['id'],
        ':route_id' => $routeId,
        ':invoice_number' => null,
    ));
    return $db->lastInsertId();
}

/**
 * Inserts a route into the database
 * @param $route array An array containing the route
 * @return string The id of the inserted route
 */
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

/**
 * Inserts a route leg into the database
 * @param $routeLeg array an array containing the route leg
 * @param $routeId integer|string The id of the route to link this route leg to
 * @return string The id of the inserted route leg
 */
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

    $insertStatement = $db->prepare('INSERT INTO `routeleg` (`cost`, `duration`, `start`, `is_actual`, `route`, `from_location`, `to_location`, `courier`, `train_courier`) VALUES (:cost, :duration, :start, :is_actual, :route_id, :from_location_id, :to_location_id, :courier_id, :train_courier_id)');
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

/**
 * Selects a customer from the database
 * @param $customerId integer|string The id of which to select the customer
 * @return array The selected customer
 */
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

/**
 * Selects a train courier from the database
 * @param $trainCourierId integer|string The courier of which to select the train courier
 * @return array The selected train courier
 */
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

/**
 * Selects all customers from the database
 * @return array An array containing all customers
 */
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

/**
 * Selects all train couriers from the database
 * @return array An array containing all traincouriers
 */
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

/**
 * Selects the sections which a train courier travels from the database
 * @param $trainCourierId integer|string The courier of which to select the sections
 * @return array An array containing all sections for the requested customer
 */
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

/**
 * Selects a location from the database
 * @param $locationId integer|string The id of which to select the location
 * @return array The requested location
 */
function selectLocation($locationId) {
    global $db;
    $statement = $db->prepare('SELECT `id`, `name`, `latitude`, `longitude`, `address`, `city`, `postal_code`, CASE(`is_station`) WHEN TRUE THEN "Station" ELSE "Address" END AS `type` FROM `location` WHERE `id` = :id');
    $statement->execute(array(':id' => $locationId));
    $location = $statement->fetch(PDO::FETCH_ASSOC);
    
    return $location;
}

/**
 * Selects a list of all stations from the database
 * @return array An array containing all stations
 */
function selectStations() {
    global $db;
    $statement = $db->prepare('SELECT `id`, `name`, `latitude`, `longitude`, "Station" AS `type` FROM `location` WHERE `is_station`');
    $statement->execute();
    $stations = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $stations;
}

/**
 * Selects all couriers from the database
 * @return array An array containing all couriers
 */
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

/**
 * Selects a package from the database
 * @param $packageId integer|string The id of which to select the package
 * @return array The requested package
 */
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

/**
 * Selects all packages belonging to a customer from the database
 * @param $customerId integer|string The id of the customer of which to select te packages
 * @return array An array containing all packages belonging to the requested customer
 */
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

/**
 * Selects a route from the database
 * @param $routeId integer|string The id of which to select the route
 * @return array The requested route
 */
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
    $route['from'] = selectLocation($route['from_address']);
    $route['to'] = selectLocation($route['to_address']);

    return $route;
}

/**
 * Selects all route legs belonging to a route from the database
 * @param $routeId integer|string The id of the route of which to select the route legs
 * @return array An array containing all route legs belonging to the route
 */
function selectRouteLegsWithRouteId($routeId) {
    global $db;
    $legStatement = $db->prepare('SELECT * FROM `routeleg` WHERE `route` = :route_id');
    $legStatement->execute(array(
        ':route_id' => $routeId,
    ));
    $routeLegs = $legStatement->fetchAll(PDO::FETCH_ASSOC);

    if ($routeLegs === null) {
        return null;
    }

    foreach ($routeLegs as $key => $routeLeg) {
        $routeLeg['to'] = selectLocation($routeLeg['to_location']);
        $routeLeg['from'] = selectLocation($routeLeg['from_location']);
        $routeLegs[$key] = $routeLeg;
    }

    return $routeLegs;
}

/**
 * Updates the state of a package in the database
 * @param $packageId integer|string The id of the package to update
 * @param $packageState string The new state of the package, one of PREPARING, ACCEPTED, CANCELED, EN_ROUTE, ARRIVED
 */
function updatePackageState($packageId, $packageState) {
    global $db;
    $update = $db->prepare('UPDATE `package` SET `state` = :state WHERE `id` = :id');
    $update->execute(array(
        ':state' => $packageState,
        ':id' => $packageId,
    ));
}