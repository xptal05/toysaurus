<nav>
    <div><a href="<?= BASE_FILE; ?>"><img src="<?= BASE_DIR; ?>/public/src/img/logo.png" height="30"></a></div>
    <div>
        <a>O nás</a>
        <ul>
            <li><a href=" <?= BASE_FILE; ?>/about">O nás</a></li>
            <li><a href="<?= BASE_FILE; ?>/toy-library">Jak to funguje</a></li>
            <!--<li>Aktuality</li>-->
            <li><a href="<?= BASE_FILE; ?>/faq">Nejčastější dotazy</a></li>
        </ul>
    </div>
    <div>
        <a>Naše hračky</a>
        <ul>
            <li><a href="<?= BASE_FILE; ?>/e-shop">Katalog hraček</a></li>
            <li><a href="<?= BASE_FILE; ?>/donate-toys">Darovat hračky</a></li>
        </ul>
    </div>
    <div><a>Členství</a>
        <ul>
            <li><a href="<?= BASE_FILE; ?>/subscription">Vše o členství</a></li>
            <li><a href="<?= BASE_FILE; ?>/registration">Stát se členem</a></li>
        </ul>
    </div>
    <div><a>Podpořte nás</a>
        <ul>
            <li><a href="<?= BASE_FILE; ?>/support-us">Jak nám pomoci</a></li>
            <li><a href="<?= BASE_FILE; ?>/sponsoring">Pomáhají nám</a></li>
            <!--<li><a href="#">Sponzoři</a></li>
            <li><a href="#">Partneři</a></li>
            <li><a href="#">Pomáháme rodinám v nouzi</a></li>-->
        </ul>
    </div>
    <div><a href="<?= BASE_FILE; ?>/contact">Kontakt</a></div>
    <div><img src="<?= BASE_FILE; ?>/public/src/icons/account.svg" height="24">
        <ul>
            <?php
            if (!isset($_SESSION["user_id"])) {
                echo '<li><a href="' . BASE_FILE . '/shop-login">Login</a></li>';
            } else {
                echo '<li><a href="' . BASE_FILE . '/my-account">Můj účet</a></li>';
                echo '<li><a id="header-logout">Logout</a></li>';
            }
            ?>
        </ul>
    </div>
    <div>
        <form method="GET" action="">
            <select name="lang" onchange="this.form.submit()">
                <option value="en" <?php if ($_SESSION['lang'] == 'en') echo 'selected'; ?>><img src="<?= BASE_DIR; ?>/public/src/icon/eng-flag.png" height="30" alt="English flag">ENGLISH</option>
                <option value="cs" <?php if ($_SESSION['lang'] == 'cs') echo 'selected'; ?>><img src="<?= BASE_DIR; ?>/public/src/icon/cz-flag.png" height="30" alt="English flag">ČESKY</option>
            </select>
        </form>
    </div>
    <?php
    if (isset($_SESSION['cart_order_items_count']) && $_SESSION['cart_order_items_count'] >= 0) {
        $tPoints = $_SESSION['client_tPoints'] ?? 0;
    ?>
        <div>
            <a href="<?= BASE_FILE; ?>/shop-cart">
                <span id="shop-cart-item-count"><?= $_SESSION['cart_order_items_count']; ?></span>
                <img src="<?= BASE_FILE; ?>/public/src/icons/shop.svg" height="24">
            </a>
        </div>
        <div><?= $tPoints; ?>🦕</div>
    <?php
    }
    ?>

</nav>