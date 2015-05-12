<?php

require_once 'DatabaseHelper.php';
require_once 'GeneralHelper.php';

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
                    $user = selectCustomer($session["user_id"]);
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
 * @returns boolan true if successful, doesn't return otherwise
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
            break;
        }
    }
    if (!found) {
        http_response_code(403);
        echo json_encode(array("error" => "Forbidden, invalid access type"));
        die;
    }
    return true;
}

/**
 * Kills if the logged in user is not of the given type or does not have the given id.
 * @param type $userType Required type
 * @param type $userId Required id
 * @return boolean true if successful, doesn't return otherwise
 */
function requireUser($userType, $userId) {
    fillUserData();
    
    requireUserType(array($userType));
    
    global $user;
    if (!($user["type"] === $userType && $user["id"] === $userId)) {
        http_response_code(403);
        echo json_encode(array("error" => "Forbidden, invalid access id"));
        die;
    }
    
   return true;
}

/**
 * Creates a randomly salted password hash
 * @param string $password The password to be salted
 * @return string password to be stored in the database
 */
function createPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Logs the customer into the system.
 * @param type $email The email of the user to login
 * @param type $password The password of the user to login
 * @return boolean false if the email and password did not match
 * @return string token if the user was successfully logged in
 */
function loginCustomer($email, $password) {
    $customerStatement = $db->prepare("SELECT `id`, `password` FROM `customer` WHERE `email` = :email");
    $customerStatement->execute(array(':email' => $email));
    $user = $customerStatement->fetch(PDO::FETCH_ASSOC);
    
    if ($customerStatement->rowCount() > 0) {
        if (password_verify($password, $user["password"])) {
            return createSession("Customer", $user["id"]);            
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Creates a session in the database for the user in the domain
 * @param string $domain
 * @param integer $userId
 */
function createSession($domain, $userId) {
    global $db;
    $token = generateRandomString(128);
    $statement = $db->prepare("INSERT INTO `session` (`token`, `domain`, `expiry_date`, `is_valid`, `user_id`) VALUES (:token, :domain, :expiry_date, :is_valid, :user_id");
    $statement->execute(array(
        ":token" => token,
        ":domain" => $domain,
        ":expiry_date" => strtotime("+1 year"),
        ":is_valid" => true,
        ":user_id" => $userId,
    ));
    return $token;
}