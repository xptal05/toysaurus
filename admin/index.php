
<?php
include_once './admin/includes/menu.php';

$allowed_subpages = ['dashboard', 'orders', 'products', 'users'];
$sub_page = $_GET['section'] ?? 'dashboard';

if (!in_array($sub_page, $allowed_subpages)) {
    $sub_page = 'dashboard'; // fallback
}

echo BASE_PATH;

?>

<h2>Admin Dashboard</h2>
<div id="account-content">
    <?php include "./admin/sections/{$sub_page}.php"; ?>
</div>

<script>
    function loadSection(section, contentUrl) {

        updateURLParameter('section', section);
        switchPage(section)
    }
</script>
<script src="./includes/dynamic-pages.js"></script>
<script src="./admin/includes/product.js"></script>
<script src="./assets/script/ajax.js"></script>
<!--<script type="module" src="./includes/auth-admin.js"></script>-->