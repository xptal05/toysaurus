<?php
require_once './includes/router.php';
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_DIR;?>/assets/style/style.css">
    <link rel="stylesheet" href="<?= BASE_DIR;?>/assets/style/nav.css">
    <link rel="stylesheet" href="<?= BASE_DIR;?>/assets/style/footer.css">
    <link rel="stylesheet" href="<?= BASE_DIR;?>/assets/style/subscriptionView.css">

    <?php
    if (isset($cssFile[$route])) {
        echo '<link rel="stylesheet" href="' . BASE_DIR . $cssFile[$route] . '">';
    }
    ?>
    <title><?= $pageNames[$route][$_SESSION['lang']] ?? 'Page' ?></title>
</head>

<body>
    <?php
    include './includes/header.php';
    ?>
    <div id="app-response" class="modal"></div>
    <div class="app">
        <?php

        if (isset($routes[$route])) {
            include $routes[$route];
        } else {
            echo 'Page 404 - Not Found';
        }
        ?>
    </div>
    <?php
    include './includes/footer.php';
    ?>
</body>
<script src="<?=BASE_DIR; ?>/assets/script/ajax.js"></script>
<script src="<?=BASE_DIR; ?>/assets/script/logout.js"></script>
<script>
    const baseDir = "<?= BASE_DIR ;?>"
</script>
</html>