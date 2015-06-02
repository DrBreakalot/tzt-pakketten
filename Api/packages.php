<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("GET"));
requireUserType(array("BackOffice", "Customer"));
fillUserData();

if ($user['type'] === "BackOffice") {
    requireGetParameters(array("customer_id" => array("string", "integer")));
    getPackagesForUser($_GET['customer_id']);
} else {
    if (getArrayValue('customer_id', $_GET)) {
        requireUser("Customer", $_GET['customer_id']);
    }
     getPackagesForUser($user['id']);
}

/**
 * Returns all packages linked to a given user
 * @param $userId integer|string The id of the user
 */
function getPackagesForUser($userId) {
    echo json_encode(selectPackages($userId));
}