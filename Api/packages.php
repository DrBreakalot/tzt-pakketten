<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("GET"));
requireUserType(array("BackOffice", "Customer"));

if ($user['type'] === "BackOffice") {
    requireGetParameters(array("customer_id" => array("string", "integer")));
    getPackagesForUser($_GET['customer_id']);
} else {
    requireUser("Customer", $_GET['customer_id']);
    getPackagesForUser($user['id']);
}

function getPackagesForUser($userId) {
    echo json_encode(selectPackages($userId));
}