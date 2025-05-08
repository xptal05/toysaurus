<?php
session_start();
header("Content-Type: application/json");

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// remove session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

echo json_encode([
    "success" => true,
    "message" => "Logout successful"
]);
