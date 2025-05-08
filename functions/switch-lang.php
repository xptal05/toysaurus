<?php
session_start();

if (isset($_POST['lang']) && in_array($_POST['lang'], ['en', 'cs'])) {
    $_SESSION['lang'] = $_POST['lang'];

    // Preserve the current route
    $currentRoute = $_GET['url'] ?? '';

    // Redirect with updated language parameter
    header("Location: ?lang=" . $_SESSION['lang'] . "&url=" . $currentRoute);
    exit;
}
