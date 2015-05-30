<?php

require_once('helper/auth_helper.php');
require_once('helper/general_helper.php');

requireMethod(array("GET"));
fillUserData();
echo json_encode($user);