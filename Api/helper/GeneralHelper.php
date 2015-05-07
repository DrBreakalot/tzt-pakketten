<?php

function requireMethod(array $allowedMethods) {
    if (!$allowedMethods || count($allowedMethods) == 0) {
        return true;
    }
    $method = $_SERVER['REQUEST_METHOD'];
    $allowed = false;
    foreach ($allowedMethods as $allowedMethod) {
        if ($method === $allowedMethod) {
            $allowed = true;
        }
    }
    if (!$allowed) {
        http_response_code(405);
        echo json_encode(array("error" => "Method not allowed", "allowed_methods" => $allowedMethods));
        die;
    }
    return true;
}