<?php
require_once BASE_PATH . '/controllers/ToyController.php';

if (!isset($_GET['toy_slug'])) {
  echo "Toy not found!";
  exit;
}

$toySlug = $_GET['toy_slug'];

// Convert slug back to original toy name (replace `_` with spaces)
$toyName = str_replace('_', ' ', $toySlug);

// Fetch the toy from the database by name
$toy = ToyController::getToyByName($toyName);

if (!$toy) {
  echo "Toy not found!";
  exit;
}


//inspiration: https://www.libraryofthings.co.uk/catalogue/borrow-heavy-duty-pressure-washer?showBack=true
?>


<h1><?= htmlspecialchars($toy->getName()); ?></h1>
<div class="shop-navigation-banner"><a href="#">Předchozí produkt</a></div>
<div class="product-container">
  <div class="product-imgs-infos-container">
    <div class="product-img-container">
      <img class="main-product-img" src="../public/src/toys/H-22-0262.jpg">
      <div class="product-gallery">
        <img src="#" height=100 width=100>
        <img src="#" height=100 width=100>
        <img src="#" height=100 width=100>
      </div>
    </div>
    <div class="Impact_Cards_Container">
      <div class="Impact_Cards_card container">
        <div class="Impact_Cards_card_info">
          <p class="Impact_Cards_caption">Saves space</p>
          <p class="Impact_Cards_value">0.225<span class="ImpactCards_unit">m²</span></p>
          <p class="Impact_Cards_description">of storage space saved</p>
        </div><img class="ImpactCards_card_Image" src="<?= BASE_DIR; ?>/public/src/icons/wardrobe.svg" alt="">
      </div>
      <div class="Impact_Cards_card container">
        <div class="Impact_Cards_card_info">
          <p class="ImpactCards_caption">Saves money</p>
          <p class="ImpactCards_value"> £181.50</p>
          <p class="ImpactCards_description">less than the RRP</p>
        </div><img class="ImpactCards_cardImage" src="<?= BASE_DIR; ?>/public/src/icons/person.svg" alt="">
      </div>
      <div class="Impact_Cards_card container">
        <div class="Impact_Cards_card_info">
          <p class="ImpactCards_caption">Kinder to the planet</p>
          <p class="ImpactCards_value">11.000<span class="ImpactCards_unit">kg</span></p>
          <p class="ImpactCards_description">waste saved from landfill</p>
        </div><img class="ImpactCards_cardImage" src="<?= BASE_DIR; ?>/public/src/icons/planet.svg" alt="">
      </div>
    </div>
    <div>
      <h2>Good to know /Description:</h2>
      <div><?= $toy->get('description'); ?></div>
    </div>
    <div>
      <h2>What is included</h2>
    </div>
    <div>
      <h2>What it does</h2>
    </div>
    <div>
      <h2>Good to know</h2>
      <div>Hmotnost 0,423 kg</div>
      <div>Rozměry 20 × 9 × 3 cm</div>
      <div>Materiál Karton, Plast</div>
      <div>od věku (výobce) Od 3 let</div>
      <div>Baterie - Ne</div>
      <div>Typ hračky - Malá hračka</div>
    </div>
  </div>
  <div class="main-product-info">
    <div class="add-to-card-container container">
      <div class="product-price-info-container">
        <div>Price: <?= $toy->get('priceNew'); ?> 🦕</div>
        <div>Status: <?= $toy->get('status'); ?></div>
      </div>
      <?php
      if (!isset($_SESSION["user_id"])) {
        echo
        '<div class="add-to-card-logged-out">
          <div class="add-to-cart-text">Hračky si mohou zapůjčit pouze členové Toysaurus Knihovny hraček. Pokud jste již členem přihlašte se.</div>
          <button id="checkout-button">Přihlášení</button>
          <button id="checkout-button">Stát se členem</button>
        </div>';
      } else {
        echo '
          <div class="add-to-card-logged-in">
            <button name="addToCartBtn" product-id="' . $toy->get('toyId') . '">Přidat do košíku</button>
          </div>';
      }
      ?>


    </div>
    <div class="container">
      <div>How borrowing periods work? -></div>
      <div>Co se stane, když ztratím část hračky?
      </div>
    </div>
    <div>
      <h2>At a glance</h2>
      <div>Bit bulky - get it home by bus/car</div>
      <div>Suitable for a variety of projects</div>
      <div>You'll need your own thread!</div>
    </div>
  </div>
</div>
<div>
  <H2>Související produkty</H2>
</div>
<script src="<?= BASE_DIR; ?>/assets/script/addItemToCart.js"></script>
<script>
  sessionStorage.setItem("cartOrderId", "<?php echo $_SESSION['cart_order_id']; ?>");
  const addProductBtn = document.querySelector('button[name="addToCartBtn"]')

  addProductBtn.addEventListener('click', (e) => {
    e.preventDefault()
    productId = addProductBtn.getAttribute('product-id')
    addProductToCart(productId)
  })
</script>
<script src="https://js.stripe.com/v3/"></script>
<?php //include_once "./stripe/stripe.php"
?>