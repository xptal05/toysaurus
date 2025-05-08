<?php

session_start();
include_once './config/config.php';
$url = isset($_GET['url']) ? explode('/', trim($_GET['url'], '/')) : [''];

// Get requested route from the URL
$route = $url[0] ?? '';
$urlParts = $url[1] ?? '';

// get toy name from url
$toySlug = ($route === 'toy' && isset($urlParts)) ? $urlParts : "";
$_GET['toy_slug'] = $toySlug;


// Detect language from query string (e.g., ?lang=en)
$lang = isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'cs']) ? $_GET['lang'] : ($_SESSION['lang'] ?? 'cs');

// Store selected language in session
$_SESSION['lang'] = $lang;

// Define routes (same as before)
$routes = [
    '' => './public/src/pages/home.php',
    'about' => './public/src/pages/about.php',
    'toy-library' => './public/src/pages/toylibrary.php',
    'faq' => './public/src/pages/faq.php',
    'e-shop' => './shop/shop.php',
    'donate-toys' => './public/src/pages/donatetoys.php',
    'subscription' => './public/src/pages/subscription.php',
    'registration' => './public/src/pages/registration.php',
    'support-us' => './public/src/pages/supportus.php',
    'sponsoring' => './public/src/pages/sponsoring.php',
    'contact' => './public/src/pages/contact.php',

    'shop-login' => './shop/login.php',
    'shop-register' => './shop/register.php',
    'shop-cart' => './shop/cart.php',
    'shop-checkout' => './shop/checkout.php',
    'payment-success' => './shop/success.php',
    'my-account' => './shop/my-account.php',

    'toy-admin' => './admin/index.php',

    // Dynamic toy route (e.g., /toy/lego_set)
    'toy' => './shop/product.php',
];



$cssFile = [
    '' => '/assets/style/home.css',
    'about' => '',
    'toy-library' => '',
    'faq' => '/assets/style/faq.css',
    'e-shop' => '/assets/style/shop.css',
    'donate-toys' => '/assets/style/donatetoys.css',
    'subscription' => '',
    'registration' => '',
    'support-us' => '',
    'sponsoring' => '',
    'contact' => '',

    'shop-login' => '/assets/style/login.css',
    'shop-register' => '',
    'shop-cart' => '/assets/style/cart.css',
    'shop-checkout' => '/assets/style/checkout.css',

    'my-account' => '/assets/style/my-account.css',

    'toy-admin' => '',
    'toy' =>  '/assets/style/shop-product.css',
];



// Ensure the route exists
if (!array_key_exists(strtolower($route), array_map('strtolower', $routes))) {
    $route = '404';
}
