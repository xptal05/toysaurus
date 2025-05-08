<?php
require_once BASE_PATH . '/controllers/CategoryController.php';
$categories = CategoryController::getNestedCategories();

?>

<div>
    <div class="filter-title">Vyhledávání</div>
    <input type="text" placeholder="Vyhledat produkty" name="searchProducts">
</div>
<?php
foreach ($categories as $category) {
    $categoryName = $category['category']->getName();
?>
    <div class="filter">
        <div class="filter-title"><?= $categoryName?></div>
        <div class=filter-options>
        <?php
        if (!empty($category['children'])) {
            foreach ($category['children'] as $subCategory) {
        ?>
                    <div><input type="checkbox" data-value="<?= $subCategory['category']->get('categoryId') ?>"><?= $subCategory['category']->getName() ?></div>
        <?php
            }
        } ?>
    </div>
    </div>
<?php
}
?>
<div class="filter">
    <div class="filter-title"></div>
    <div class=filter-options></div>
</div>
<div class="filter">
    <div class="filter-title">Typ hračky</div>
    <div class=filter-options></div>
</div>
<div class="filter">
    <div class="filter-title" data-value="Ages">Věk</div>
    <div class=filter-options>

    </div>
</div>
<div class="filter">
    <div class="filter-title">Dovednost</div>
    <div class=filter-options></div>
</div>
<div class="filter">
    <div class="filter-title">Účel</div>
    <div class=filter-options></div>
</div>