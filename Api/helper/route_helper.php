<?php

require_once dirname(__FILE__).'/connection_helper.php';
require_once dirname(__FILE__).'/general_helper.php';
require_once dirname(__FILE__).'/database_helper.php';
require_once dirname(__FILE__).'/../config/config.php';

function calculateRoute($from, $to) {

    $addressDistance = distanceBetween($to, $from);


}

function calculateRouteUsingTrain($from, $to) {
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

function calculateRouteLegUsingRoad($from, $to) {
    $couriers = selectCouriers();
    $cheapestRouteLeg = null;
    $cheapestCost = 999999999999;

    foreach ($couriers as $courier) {
        $distanceDuration = getDistanceTime($from, $to, $courier["transit_mode"]);
        $distance = $distanceDuration["distance"];
        $duration = $distanceDuration["duration"];
        if ($distance && $duration) {
            $cost = calculateCost($courier["calculators"], $distance);
            if ($cost < $cheapestCost) {
                $cheapestCost = $cost;
                $cheapestRouteLeg = array(
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

function calculateCost($calculators, $distance) {
    foreach ($calculators as $calculator) {
        if ($calculator["minimum_distance"] >= $distance && ($calculator["maximum_distance"] === null || $calculator["maximum_distance"] <= $distance)) {
            return $calculator["cost"] + (max(0, $distance - $calculator["start_distance"]) * $calculator["per_kilometer"]);
        }
    }
    return null;
}