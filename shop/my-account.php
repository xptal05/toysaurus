<div class="flex-row">
    <?php
    include_once './shop/includes/menu.php';

    // Allowed subpages
    $allowed_subpages = ['info', 'subscription', 'toys'];
    $sub_pages_content = [];

    // Preload all allowed subpages into PHP variables
    foreach ($allowed_subpages as $sub_page) {
        ob_start();
        include "./shop/sections/$sub_page.php";
        $sub_pages_content[$sub_page] = ob_get_clean();
    }

    // Get current subpage from URL or default to 'info'
    $sub_page = isset($_GET['section']) ? $_GET['section'] : 'info';

    ?>
    <div id="account-content" class="main-content">

        <?php
        include "./shop/sections/$sub_page.php"; // Dynamically include subpage

        ?>
    </div>

</div>

<script>
const pages = <?= json_encode($sub_pages_content); ?>;

function loadSection(section, contentUrl) {
    updateURLParameter('section', section);
    switchPage(section)
}

</script>
<script src="./includes/dynamic-pages.js"></script>