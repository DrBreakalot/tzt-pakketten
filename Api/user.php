<?php

require_once('helper/auth_helper.php');
require_once('helper/general_helper.php');

/**
 * Returns the currently logged in user
 */

requireMethod(array("GET"));
fillUserData();
echo json_encode($user);