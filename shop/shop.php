<?php
include_once BASE_PATH . '/controllers/ToyController.php';
$toys = ToyController::getAllToys();
?>

<section class="shop-container">
    <div class="shop-filter">
        <?php
        include_once BASE_PATH . '/shop/includes/filter.php';
        ?>
    </div>
    <div>
        <div>U nás můžete získat mnoho zábavných a vzdělávacích hraček vhodných pro děti od narození do šesti let věku. Náš katalog obsahuje oblíbené stavební hračky, hračky na koordinaci i motoriku, auta i panenky a mnoho dalšího. Neustále doplňujeme náš katalog o nové hračky, abyste mohli získat rozmanité a trendy hračky přizpůsobené věku a dovednostem vašeho dítěte. Hračky si prohlédněte online v našem katalogu, zarezervujte si je prostřednictvím vašeho členského konta a vyberte si, zda si hračky necháte zaslat nebo zda si je vyzvednete osobně.</div>
        <h1>Online katalog hraček</h1>
        <div class="shop-view-wrapper">
            <div class="order-by">
                <div>Seřadit</div>
                <select>
                    <option value="popularity">Seřadit podle oblíbenosti</option>
                    <option value="rating">Seřadit podle průměrného hodnocení</option>
                    <option value="date" selected="selected">Seřadit od nejnovějších</option>
                    <option value="price">Seřadit podle ceny: od nejnižší k nejvyšší</option>
                    <option value="price-desc">Seřadit podle ceny: od nejvyšší k nejnižší</option>
                </select>
            </div>
        </div>
        <div class="shop-catalogue">

            <?php
            foreach ($toys as $toy) { ?>
                <div class="product-container">
                    <a href="./toy/<?= $toy->getSlug(); ?>">
                        <div class="product-container-img"><img href="#" height=250 width=250></div>
                        <div><?= $toy->getName(); ?> - Price: <?= $toy->getTPoints(); ?> 🦕</div>
                    </a>
                    <div><button name="addToCartBtn" product-id="<?= $toy->getToyId(); ?>">Přidat do košíku</button></div>
                </div>
            <?php
            } ?>
        </div>
    </div>
</section>
<script></script>

<script type="module" src="./includes/auth-check.js"></script>
<script src="./assets/script/addItemToCart.js"></script>

<script>
    const toys = <?= json_encode(array_map(function ($toy) {
                        return [
                            'id' => $toy->getToyId(),
                            'name' => $toy->getName(),
                            'tPoints' => $toy->getTPoints(),
                            'slug' => $toy->getSlug(),
                            'categories' => $toy->get('categories')
                        ];
                    }, $toys)); ?>;

    sessionStorage.setItem("cartOrderId", "<?php echo isset($_SESSION['cart_order_id']) ? $_SESSION['cart_order_id'] : 'null'; ?>");
    const shopCatalogContainer = document.querySelector('.shop-catalogue');
    const filters = document.querySelectorAll('.filter-options input[type="checkbox"]');
    const searchInput = document.querySelector('input[name="searchProducts"]');

    // Handle adding product to the cart
    shopCatalogContainer.addEventListener('click', (e) => {
        const btn = e.target.closest('button[name="addToCartBtn"]');
        if (!btn) return;

        e.preventDefault();
        const productId = btn.getAttribute('product-id');
        addProductToCart(productId);
    });

    // Handle category filters
    filters.forEach(filter => {
        filter.addEventListener('click', () => {
            const filteredToys = filterProducts(toys);
            renderToys(filteredToys);
        });
    });

    // Handle search input
    searchInput.addEventListener('input', () => {
        const searchText = searchInput.value;
        const filteredToys = filterProducts(toys, searchText);
        renderToys(filteredToys);
    });

    // Combined filter for both categories and search
    function filterProducts(toys, searchText = '') {
        // Get all checked checkboxes and extract their data-value as numbers
        const checkedFilters = Array.from(
            document.querySelectorAll('.filter-options input[type="checkbox"]:checked')
        ).map(cb => parseInt(cb.getAttribute('data-value')));

        // First, filter by category
        let filteredToys = toys.filter(toy => {
            try {
                const toyCategories = JSON.parse(toy.categories);
                return checkedFilters.length > 0 ?
                    toyCategories.some(catId => checkedFilters.includes(catId)) :
                    true; // If no filters, include all toys
            } catch (e) {
                return false;
            }
        });

        // Then, filter by search term (if any)
        if (searchText.trim() !== '') {
            const searchTerm = searchText.trim().toLowerCase();
            filteredToys = filteredToys.filter(toy =>
                toy.name.toLowerCase().includes(searchTerm)
            );
        }

        return filteredToys;
    }

    // Function to render the toy cards into the catalog
    function renderToys(toysToRender) {
        // Clear current content
        shopCatalogContainer.innerHTML = '';

        // Add each toy
        toysToRender.forEach(toy => {
            shopCatalogContainer.innerHTML += `
            <div class="product-container">
                <a href="./toy/${toy.slug}">
                    <div class="product-container-img">
                        <img src="${toy.image || '#'}" height="250" width="250">
                    </div>
                    <div>${toy.name} - Price: ${toy.tPoints} 🦕</div>
                </a>
                <div>
                    <button name="addToCartBtn" product-id="${toy.id}">Přidat do košíku</button>
                </div>
            </div>
        `;
        });
    }

    // 🧩 Initial render
    renderToys(toys);
</script>