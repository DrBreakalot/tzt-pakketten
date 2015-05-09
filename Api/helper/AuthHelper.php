<?php

require_once 'DatabaseHelper.php';

$TZT_AUTH_HEADER = "HTTP_TZT_AUTHORIZATION";

/**
 * Fills the following variables with user data. This will not overwrite the values if a user has been found before
 * @global string $token The token as obtained from the TZT-AUTHORIZATION header
 * @global array $session The session obtained via the token, if available
 * @global array $user  The user obtained via the session, if available
 */
function fillUserData() {
    global $token;
    global $user;
    global $session;

    if ($token === null) {
        $token = $_SERVER[$TZT_AUTH_HEADER];
        $user = null;
        $session = null;

        if ($token !== null) {
            $statement = $db->prepare('SELECT * FROM `session` WHERE `token` = :token AND `is_valid` AND `expiry_date` > NOW()');
            $statement->execute(array(':token' => $token));
            $session = $statement->fetch(PDO::FETCH_ASSOC);
            if ($session !== null) {
                $domain = $session["domain"];
                if ($domain === "Customer") {
                    $customerStatement = $db->prepare('SELECT * FROM `customer` WHERE `id` = :id');
                    $customerStatement->execute(array(':id' => $session["user_id"]));
                    $user = $customerStatement->fetch(PDO::FETCH_ASSOC);
                } else if ($domain === "TrainCourier") {
                    $courierStatement = $db->prepare('SELECT * FROM `trainCourier` WHERE `id` = :id');
                    $courierStatement->execute(array(':id' => $session["user_id"]));
                    $user = $courierStatement->fetch(PDO::FETCH_ASSOC);
                }

                if ($user !== null) {
                    $user["type"] = $session["domain"];
                }
            }
        }
    }
}

/**
 * Kills if the userType is not found, returns status code 401 to the client if not authenticated, or 403 if authenticated but not authorized
 * @param array $allowedUserTypes The required userTypes, null if no authorization required.
 * @returns bool true if successful, doesn't return otherwise
 */
function requireUserType(array $allowedUserTypes) {    
    fillUserData();
    
    global $user;
    
    if (!$allowedUserTypes || count($allowedUserTypes) === 0) {
        return true;
    }
    if ($user === null) {
        http_response_code(401);
        echo json_encode(array("error" => "Unauthorized, user not logged in"));
        die;
    }
    
    $found = false;
    foreach ($allowedUserTypes as $value) {
        if ($value === $user["type"]) {
            $found = true;
        }
    }
    if (!found) {
        http_response_code(403);
        echo json_encode(array("error" => "Forbidden, user authorized"));
        die;
    }
    return true;
}