<?php
require_once BASE_PATH . '/controllers/ClientController.php';
require_once BASE_PATH . '/controllers/ToyOrderController.php';

$totalAmount = 20000;

$userId = $_SESSION['user_id'];
$client = ClientController::getClientById($_SESSION['user_id']) ?? new Client();
$clientCartID = $_SESSION['cart_order_id'];
if ($clientCartID !== null) {
    $clientCart = ToyOrderController::getOrderById($clientCartID);
    $CartItems = $clientCart->getOrderItems();
} else {
    echo 'session cart: '.$_SESSION['cart_order_id'];
    echo '<div>V košíku nejsou žádné hračky</div>' ;
    exit; // Prevents the rest of the page from loading
}

?>
<a href="" class="align-right">Zpět do košíku</a>
<div id="card-errors" role="alert"></div>
<div class="checkout-container">
    <div class="form_fields_wrapper">
        <div class="billing-fields">
            <h3>Fakturační údaje</h3>
            <div class="form-row" id="billing_first_name_field">
                <label for="billing_names" class="">Jméno a příjmení*</label>
                <input type="text" class="input-text " name="billing_names" id="billing_names" placeholder="Jméno a příjmení" value="<?= $client->get('firstName') . ' ' . $client->get('lastName'); ?>" required="true">
            </div>
            <div class="form-row" id="billing_phone_field">
                <label for="billing_phone" class="">Telefon *</label>
                <input type="tel" class="input-text " name="billing_phone" id="billing_phone" placeholder="" value="<?= $client->get('phone'); ?>" required="true">
            </div>
            <div class="form-row" id="billing_email_field">
                <label for="billing_email" class="">E-mailová adresa *</label>
                <input type="email" class="input-text " name="billing_email" id="billing_email" placeholder="" value="<?= $client->get('email'); ?>" required="true">
            </div>
            <div class="form-row" id="billing_address_1_field">
                <label for="billing_address_1" class="">Ulice a č.p. *</label>
                <input type="text" class="input-text " name="billing_address_1" id="billing_address_1" placeholder="Číslo domu a název ulice" value="<?= $client->get('address')['street']; ?>" required="true" data-placeholder="Číslo domu a název ulice">
            </div>
            <div class="form-row" id="billing_city_field" data-o_class="form-row form-row-wide address-field validate-required">
                <label for="billing_city" class="">Město *</label>
                <input type="text" class="input-text " name="billing_city" id="billing_city" placeholder="" value="<?= $client->get('address')['city']; ?>" required="true">
            </div>
            <div class="form-row" id="billing_postcode_field" data-o_class="form-row form-row-wide address-field validate-required validate-postcode">
                <label for="billing_postcode" class="">PSČ *</label>
                <input type="text" class="input-text " name="billing_postcode" id="billing_postcode" placeholder="" value="<?= $client->get('address')['postal_code']; ?>" required="true">
            </div>
            <div class="form-row form-row-wide add" id="billing_country_field">
                <label for="billing_country" class="">Země / Region :</label><span>Česká republika</span>
                <input type="hidden" name="billing_country" id="billing_country" value="CZ" aria-required="true" readonly="readonly">
            </div>

        </div>
        <div class="shipping-container">
            <H3>Doprava</H3>
            <div id="shipping_method" class="container">
                <div class="shipping_methods form_fields_wrapper">
                    <div class="form-row">
                        <input type="radio" name="shipping_method" id="shipping_method_local_pickup" value="local_pickup" data-value=0 class="shipping_method" checked="checked">
                        <label for="shipping_method_local_pickup">Osobní odběr Vestec: Zdarma</label>
                    </div>
                    <div class="form-row">
                        <input type="radio" name="shipping_method" id="shipping_method_paketa" value="paketa" data-value=100 class="shipping_method">
                        <label for="shipping_method_paketa">Zásilkovna: 100,00 Kč</label>
                    </div>
                    <div class="shipping-method-details" style="display:none">
                        <img class="packetery-widget-button-logo" src="https://www.imla.cz/wp-content/plugins/packeta/public/images/packeta-symbol.png?v=cba3a4416be106ab30b159cb8f9d2d2b" alt="Zásilkovna" width=50>
                        <div class="packetery-widget-button-wrapper">
                            <button class="packeta-selector-open" onclick="Packeta.Widget.pick(packetaApiKey, showSelectedPickupPoint, packetaOptions)">Vyberte místo vyzvednutí</button>
                            <div class="packeta-selector-value"></div>
                            <?php include_once "./assets/plugins/packeta/packeta.php"; ?>
                            <input name="packetery_point_id" id="packetery_point_id" type="hidden" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="payment-container">
            <H3>PLATBA</h3>
            <div id="payment" class="checkout-payment container">
                <div class="payment_methods form_fields_wrapper">
                    <div class="form-row">
                        <input id="payment_method_bacs" type="radio" class="input-radio" name="payment_method" value="bacs" checked="checked" data-order_button_text="">
                        <label for="payment_method_bacs">Bankovní převod s QR kódem </label>
                        <div class="payment_box payment_method_bacs">Platba předem na účet, budete moci využít QR kód. Objednávka bude expedována po připsání platby na náš účet.
                        </div>
                    </div>
                    <div class="form-row">
                        <input id="payment_method_stripe_gateway" type="radio" class="input-radio" name="payment_method" value="stripe" data-order_button_text="">
                        <label for="payment_method_stripe_gateway">
                            Platba kartou <img src="#" alt="Platba kartou"> </label>
                        <div class="payment_box payment_method_stripe_gateway" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="order_review" class="checkout-review-order">
        <h3>Rekapitulace objednávky</h3>

        <div class="checkout-review-order-container">
            <?php
            $subtotalTPoints = 0;
            $subtotalDeposit = 0;
            foreach ($CartItems as $item) {
                $toy = $item->getToy();
                $subtotalTPoints += $toy->getTPoints();
                $subtotalDeposit += $toy->getTPoints() * 150;
            ?>
                <div class="cart_item">
                    <div class="product-thumb">
                        <img width="70" height="70" src="https://www.imla.cz/wp-content/uploads/2024/10/ecoa-britta-3_bg-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Dámská korková peněženka Ecoa Britta">
                    </div>
                    <div class="product-name"><?= $toy->getName(); ?></div>
                    <div class="product-price"><?= $toy->getTPoints(); ?>&nbsp;🦕</div>
                    <div class="product-deposit"><?= $toy->getTPoints() * 150; ?>&nbsp;Kč</div>
                </div>

            <?php
            }
            ?>
        </div>
        <div class="cart-container">
            <H3>Mezisoučet</H3>
            <div class="subtotal-tPoints"><?= $subtotalTPoints; ?>&nbsp;🦕</div>
            <div class="subtotal-deposit"><?= $subtotalDeposit; ?>&nbsp;Kč</div>
        </div>
        <div class="order-total cart-container">
            <H3>Doprava</H3>
            <div></div>
            <div class="amount">0 Kč</div>
        </div>
        <div class="client-account-total container">
            <H3>K dispozici na Vašem účtu</H3>
            <div class="cart-container">
                <div></div>
                <div>10 &nbsp;🦕</div>
                <div>200,00&nbsp;Kč</div>
            </div>
        </div>
        <div class="final-total cart-container">
            <H3>Celkem k platbě</H3>
            <div class="amount">200,00&nbsp;Kč</div>
        </div>
        <div class="place-order">
            <div class="terms-and-conditions-wrapper">
                <div class="privacy-policy-text">
                    Informace ke zpracování osobních údajů naleznete na stránce <a href="#" class="privacy-policy-link" target="_blank">ochrana osobních údajů</a>.
                </div>
                <p class="form-row validate-required">
                    <label class="form__label form__label-for-checkbox checkbox">
                        <input type="checkbox" class="form__input form__input-checkbox input-checkbox" name="terms" id="terms" required>
                        <span class="terms-and-conditions-checkbox-text">Přečetl(a) jsem si <a href="#" class="terms-and-conditions-link" target="_blank">obchodní podmínky</a> a souhlasím s nimi</span>
                    </label>
                    <input type="hidden" name="terms-field" value="1">
                </p>
            </div>
            <button type="submit" class="button" name="checkout_place_order" id="checkout_place_order" value="Dokončit nákup" data-action="bacs" data-value="Dokončit nákup">Dokončit nákup</button>
            <input type="hidden" id="process-checkout-nonce" name="process-checkout-nonce" value="210ad82d60">
        </div>
    </div>
</div>

<form id="payment-form" style="display:none">
    <div id="card-element"><!-- Stripe will insert card input here --></div>
    <button id="payButton">Zaplatit</button>
</form>

</div>

<script>
    // Update price
    const shippingMethodInputs = document.querySelectorAll('input[name="shipping_method"]')
    const orderTotalAmount = document.querySelector('.order-total  .amount')
    const finalTotalAmount = document.querySelector('.final-total  .amount')
    let price = 0
    let deposit = parseInt(<?= $subtotalDeposit; ?>)
    let clientAvailableDeposit = 200
    let shippingCost = 0
    let finalCost = deposit - clientAvailableDeposit + shippingCost

    shippingMethodInputs.forEach(input => {
        input.addEventListener("change", function() {
            shippingCost = parseFloat(input.getAttribute("data-value"));
            updatePrice(); // Call a function to update the price
        });
    });

    function updatePrice() {
        orderTotalAmount.innerText = shippingCost + ' Kč'
        finalTotalAmount.innerText = deposit - clientAvailableDeposit + shippingCost + ' Kč'
        finalCost = deposit - clientAvailableDeposit + shippingCost + ' Kč'
    }

    // Update payment method
    const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
    const submitBtn = document.querySelector('button[name="checkout_place_order"]')

    paymentMethodInputs.forEach(input => {
        input.addEventListener("change", function() {
            paymentMethod = this.value;
            submitBtn.setAttribute('data-action', paymentMethod)
        });
    });

    const placeOrderBtn = document.getElementById("checkout_place_order")
    placeOrderBtn.addEventListener("click", async () => {
        paymentMethod = placeOrderBtn.getAttribute('data-action')
        if (paymentMethod === "stripe") {
            payWithStripe()
        } else if (paymentMethod === "bacs") {
            payByBacs()
        }
    });

    function payByBacs() {
        console.log('payment by bacs')
    }

    function generateQRCode() {

    }
</script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    function payWithStripe() {
        // ✅ Step 1: Initialize Stripe.js
        const stripe = Stripe("<?= $stripePublishKey; ?>"); // Your Stripe publishable key
        const elements = stripe.elements();
        const cardElement = elements.create("card");
        cardElement.mount("#card-element");

        document.getElementById("payment-form").style.display = "block"; // Show the form
        document.getElementById("checkout_place_order").style.display = "none"; // Hide the first button

        document.getElementById("payButton").addEventListener("click", async (event) => {
            event.preventDefault(); // Prevent form submission
            const paymentForm = document.getElementById('payment-form')
            paymentForm.style.display = 'block'

            // ✅ Step 1: Fetch Payment Intent from Backend
            const response = await fetch("<?= BASE_FILE . '/assets/plugins/stripe/stripe.php' ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: "createPaymentIntent",
                    amount: finalCost * 100 // in cents
                })
            });

            const data = await response.json();

            if (data.error) {
                alert("Error: " + data.error);
                return;
            }

            // ✅ Step 3: Create Payment Method
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod({
                type: "card",
                card: cardElement
            });

            if (error) {
                document.getElementById("card-errors").textContent = error.message;
                return;
            }

            // ✅ Step 4: Attach Payment Method to PaymentIntent
            const {
                error: confirmError
            } = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: paymentMethod.id
            });

            if (confirmError) {
                alert("⚠️ Payment requires authentication: " + confirmError.message);
                return;
            } else {
                //  alert("✅ Payment successful!");
                window.location.href = "payment-success"; // Redirect after payment
            }
        });
    }
</script>