<?php
session_set_cookie_params([
    'lifetime' => 0, // Ends session on browser close
    'path' => '/',
    'domain' => '', // Use your domain if needed
    'secure' => true, // 🔒 Ensures session is only sent over HTTPS
    'httponly' => true, // 🔒 Prevents JavaScript access to session
    'samesite' => 'Strict' // 🔒 Prevents CSRF attacks
]);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔹 If user is NOT logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 🔹 Security: Lock session to user's IP address & browser
if (!isset($_SESSION['ip_address'])) {
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
}
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

// ✅ If session does not match original IP or User-Agent → Destroy session (possible hijacking)
if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR'] || 
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_destroy();
    header("Location: login.php"); // 🔄 Force re-login
    exit;
}

// 🔹 Auto Logout After 30 Minutes of Inactivity
$timeout_duration = 1800; // 1800 seconds = 30 minutes
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset(); // Remove session variables
    session_destroy(); // Destroy session
    header("Location: login.php?timeout=true"); // Redirect to login with timeout message
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // ✅ Update last activity time
?>
