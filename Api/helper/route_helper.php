<?php

require_once dirname(__FILE__).'/connection_helper.php';
require_once dirname(__FILE__).'/general_helper.php';
require_once dirname(__FILE__).'/database_helper.php';
require_once dirname(__FILE__).'/../config/config.php';

/**
 * Calculates the cheapest route between two locations
 * @param $from array Departing location
 * @param $to array Arrival location
 * @return array|null The route between the locations, or null if none available
 */
function calculateRoute($from, $to) {
    $trainRoute = calculateRouteUsingTrain($from, $to);
    $addressRoute = calculateRouteUsingRoad($from, $to);

    $route = null;
    if ($trainRoute !== null && $trainRoute['cost'] < $addressRoute['cost']) {
        $route = $trainRoute;
    } else {
        $route = $addressRoute;
    }

    return $route;
}

/**
 * Calculates the cheapest route when using train
 * @param $from array Departing location
 * @param $to array Arrival location
 * @return array|null The route between the locations, or null if none available
 */
function calculateRouteUsingTrain(&$from, &$to) {
    $stations = selectStations();

    $closestToStation = null;
    $closestFromStation = null;
    $closestToDistance = 9999999999999;
    $closestFromDistance = 9999999999999;

    foreach ($stations as $station) {
        $toDistance = distanceBetween($to, $station);
        $fromDistance = distanceBetween($from, $station);
        if ($toDistance < $closestToDistance) {
            $closestToDistance = $toDistance;
            $closestToStation = $station;
        }
        if ($fromDistance < $closestFromDistance) {
            $closestFromDistance = $fromDistance;
            $closestFromStation = $station;
        }
    }

    if ($closestToDistance + $closestFromDistance >= distanceBetween($to, $from)) {
        //Direct route is always better, we're not going to waste time calculating a train route
        return null;
    }

    $trainPart = getDistanceTime($closestFromStation, $closestToStation, "transit");
    $trainRouteLeg = array(
        'duration' => $trainPart['duration'],
        'is_actual' => false,
        'cost' => Config::TRAIN_COST,
        'from' => $closestFromStation,
        'to' => $closestToStation,
    );

    $toStationRouteLeg = calculateRouteLegUsingRoad($from, $closestFromStation);
    $fromStationRouteLeg = calculateRouteLegUsingRoad($closestToStation, $to);

    $legs = array(
        $toStationRouteLeg,
        $trainRouteLeg,
        $fromStationRouteLeg
    );
    $cost = 0;
    foreach ($legs as $leg) {
        $cost += $leg['cost'];
    }

    $route = array(
        'legs' => $legs,
        'from' => $from,
        'to' => $to,
        'cost' => $cost,
    );

    return $route;
}

/**
 * Calculates the cheapest route when not using train
 * @param $from array Departing location
 * @param $to array Arrival location
 * @return array|null The route between the locations, or null if none available
 */
function calculateRouteUsingRoad(&$from, &$to) {
    $routeLeg = calculateRouteLegUsingRoad($from, $to);
    $route = array (
        'legs' => array($routeLeg),
        'cost' => $routeLeg['cost'],
        'from' => $from,
        'to' => $to,
    );
    return $route;
}

/**
 * Calculates a route leg between two locations, using the couriers in the database
 * @param $from array Departing location
 * @param $to array Arrival location
 * @return array|null The route between the locations, or null if none available
 */
function calculateRouteLegUsingRoad(&$from, &$to) {
    $couriers = selectCouriers();
    $cheapestRouteLeg = null;
    $cheapestCost = 999999999999;

    fillLatitudeLongitude($from);
    fillLatitudeLongitude($to);

    foreach ($couriers as $courier) {
        $distanceDuration = getDistanceTime($from, $to, $courier["transit_mode"]);
        $distance = $distanceDuration["distance"];
        $duration = $distanceDuration["duration"];
        if ($distance && $duration) {
            $cost = calculateCost($courier['calculators'], $distance);
            if ($cost < $cheapestCost) {
                $cheapestCost = $cost;
                $cheapestRouteLeg = array(
                    'distance' => $distance,
                    'cost' => $cost,
                    'duration' => $duration,
                    'is_actual' => false,
                    'from' => $from,
                    'to' => $to,
                    'courier' => $courier,
                );
            }
        }
    }
    return $cheapestRouteLeg;
}

/**
 * Calculates the cost of a distance when using the available calculators
 * @param $calculators array An array of route calculators
 * @param $distance double the distance from the locations
 * @return integer the cost in cents
 */
function calculateCost($calculators, $distance) {
    foreach ($calculators as $calculator) {
        if ($calculator["minimum_distance"] <= $distance && ($calculator["maximum_distance"] === null || $calculator["maximum_distance"] >= $distance)) {
            return $calculator["cost"] + (max(0, $distance - $calculator["start_distance"]) * $calculator["per_kilometer"]);
        }
    }
    return null;
}