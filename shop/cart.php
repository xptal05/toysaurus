<?php
require_once BASE_PATH . '/controllers/ClientController.php';
require_once BASE_PATH . '/controllers/ToyOrderController.php';

$userId = $_SESSION['user_id'];
$client = ClientController::getClientById($_SESSION['user_id']) ?? new Client();
$clientCartID = $_SESSION['cart_order_id'];
if ($clientCartID !== null) {
    $clientCart = ToyOrderController::getOrderById($clientCartID);
    $CartItems = $clientCart->getOrderItems();
} else {
    echo 'session cart: ' . $_SESSION['cart_order_id'];
    echo '<div>V košíku nejsou žádné hračky</div>';
    exit; // Prevents the rest of the page from loading
}

?>

<div class="cart">
    <div class="cart-notices-wrapper"></div>
    <table class="cart-table container" cellspacing="0">
        <thead>
            <tr>
                <th class="product-remove">Odstranit</th>
                <th class="product-thumbnail">Náhled</th>
                <th class="product-name">Produkt</th>
                <th class="product-price">Cena</th>
                <th class="product-deposit">Záloha</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($CartItems as $item) {
                $toy = $item->getToy();
            ?>
                <tr class="cart_item">
                    <td class="product-remove"><button name="removeFromCartBtn" product-id="<?= $toy->get('toyId'); ?>">X</button>
                    </td>
                    <td class="product-thumb">
                        <img width="70" height="70" src="https://www.imla.cz/wp-content/uploads/2024/10/ecoa-britta-3_bg-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Dámská korková peněženka Ecoa Britta">
                    </td>
                    <td class="product-name"><a href=""><?= $toy->getName(); ?></a></td>
                    <td class="product-price"><?= $toy->getTPoints(); ?>&nbsp;🦕</td>
                    <td class="product-deposit"><?= $toy->getTPoints() * 150; ?>&nbsp;Kč</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>


    <div class="cart_totals">
        <h2>Celkem</h2>
        <div class="final-total container">
            <div>
                <div class="tPoints-Total-Text">T body celkem</div>
                <div class="tPoints-Total-Value"></div>
            </div>
            <div>
                <div class="deposit-Total-Text">Záloha celkem</div>
                <div class="deposit-Total-Value"></div>
            </div>

        </div>
        <a href="./shop-checkout" class="checkout-button">
            <button>Přejít k pokladně</button></a>
    </div>



</div>
<div class="cross-sells">
    <h2>Mohlo by Vás zajímat…</h2>

</div>
<script src="<?= BASE_DIR; ?>/assets/script/addItemToCart.js"></script>
<script>
    sessionStorage.setItem("cartOrderId", "<?php echo $_SESSION['cart_order_id']; ?>");
    const removeProductBtns = document.querySelectorAll('button[name="removeFromCartBtn"]')

    removeProductBtns.forEach(removeProductBtn => {
        removeProductBtn.addEventListener('click', (e) => {
            e.preventDefault()

            const productId = removeProductBtn.getAttribute('product-id');
            const productTr = removeProductBtn.closest('tr');

            removeProductFromCart(productId)
                .then(() => {
                    updateUI(productTr)
                })
                .catch((error) => {
                    console.error("❌ Remove failed:", error);
                });
        })
    })

    function updateUI(productTr) {
        if (productTr) {
            productTr.remove(); // ✅ Remove the DOM element (e.g., <tr>, <div>, etc.)
        } else {
            console.warn("⚠️ Element to remove not found or invalid.");
        }
        countTotals()
    }


    function countTotals() {
        const tPointsTds = document.querySelectorAll('.cart_item .product-price');
        const totalTpoints = document.querySelector('.tPoints-Total-Value')
        const depositTds = document.querySelectorAll('.cart_item .product-deposit');
        const totaDeposit = document.querySelector('.deposit-Total-Value')

        let tPoints = 0
        let deposit = 0
        tPointsTds.forEach(td => {
            const num = parseInt(td.textContent) || 0;
            tPoints += num;
        })
        totalTpoints.textContent = tPoints + '🦕'

        depositTds.forEach(td => {
            const num = parseInt(td.textContent) || 0;
            deposit += num;
        })
        totaDeposit.textContent = deposit + ' Kč'

    }

    countTotals()
</script>