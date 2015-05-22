<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/connection_helper.php';

requireMethod(array("POST"));

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    requireUserType("Customer", "BackOffice");

    decodePostBody();

    createPackage($json);
}

function createPackage($json) {
    $requiredParameters = array(
        "width" => array("integer", "double"),
        "height" => array("integer", "double"),
        "depth" => array("integer", "double"),
        "from" => array("array"),
        "to" => array("array"),
    );
    requirePostParameters($requiredParameters, $json, null);

    $fromAddress = $json["from"];
    $toAddress = $json["to"];

    $requiredAddressParameters = array(
        "name" => array("string", "NULL"),
        "address" => array("string"),
        "city" => array("string"),
        "postal_code" => array("string"),
        "latitude" => array("integer", "double", "NULL"),
        "longitude" => array("integer", "double", "NULL"),
    );
    requirePostParameters($requiredAddressParameters, $json["from"], "from");
    requirePostParameters($requiredAddressParameters, $json["to"], "to");

    $stations = selectStations();

    $closestToStation = null;
    $closestFromStation = null;
    $closestToDistance = 9999999999999;
    $closestFromDistance = 9999999999999;
    foreach ($stations as $station) {
        $toDistance = distanceBetween($toAddress, $station);
        $fromDistance = distanceBetween($fromAddress, $station);
        if ($toDistance < $closestToDistance) {
            $closestToDistance = $toDistance;
            $closestToStation = $station;
        }
        if ($fromDistance < $closestFromDistance) {
            $closestFromDistance = $fromDistance;
            $closestFromStation = $station;
        }
    }
    $addressDistance = distanceBetween($toAddress, $fromAddress);
    //TODO
}