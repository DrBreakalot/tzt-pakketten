<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';

requireMethod(array("GET"));
requireUserType(array("BackOffice", "Customer"));

if ($user['type'] === "BackOffice") {
    requireGetParameters("user_id", "string", "integer");
    getPackagesForUser($_GET['user_id']);
} else {
    getPackagesForUser($user['id']);
}

function getPackagesForUser($userId) {
    echo json_encode(selectPackages($userId));
}