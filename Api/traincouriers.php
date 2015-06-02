<?php

require_once 'helper/general_helper.php';
require_once 'helper/database_helper.php';
require_once 'helper/auth_helper.php';


/**
 * Returns an array of all train couriers
 */

requireMethod(array("GET"));
requireUserType(array("BackOffice"));

echo json_encode(selectTrainCouriers());