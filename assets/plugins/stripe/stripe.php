<?php
define('BASE_FILE', 'http://' . $_SERVER['HTTP_HOST'] . '/JOBS/_MY_COMP/toysaurus/toysaurus2025');

$testkey = $stripesecretKey;

require_once '/Applications/MAMP/htdocs/JOBS/_MY_COMP/toysaurus/toysaurus2025/assets/plugins/stripe/stripe-lib/init.php';
\Stripe\Stripe::setApiKey($stripesecretKey);


// ✅ Function to Create a Payment Intent

function makeStripePaymentIntent($amount, $paymentMethodId = null)
{
    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount, // Amount in cents (e.g., 5000 = 50.00 CZK)
            'currency' => 'czk',
            'payment_method_types' => ['card'],
            'payment_method' => $paymentMethodId, // Attach payment method if provided
            'confirm' => $paymentMethodId ? true : false, // Auto-confirm only if method is set
            'payment_method_options' => [
                'card' => [
                    'request_three_d_secure' => 'automatic', // Forces 3D Secure but no ZIP code
                ]
            ]
        ]);

        echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function createStripeCustomer($email)
{
    try {
        // ✅ Step 1: Search for an existing customer by email
        $existingCustomers = \Stripe\Customer::all([
            'email' => $email,
            'limit' => 1 // Fetch only 1 result for efficiency
        ]);

        if (!empty($existingCustomers->data)) {
            // ✅ Step 2: If found, reuse the existing customer
            $customer = $existingCustomers->data[0];
            echo "✅ Existing customer found: " . $customer->id;
        } else {
            // ✅ Step 3: If not found, create a new customer
            $customer = \Stripe\Customer::create([
                'email' => $email,
                'name' => 'John Doe',
                'payment_method' => 'pm_card_visa', // Replace with actual Payment Method ID
                'invoice_settings' => [
                    'default_payment_method' => 'pm_card_visa'
                ]
            ]);
            echo "✅ New customer created: " . $customer->id;
        }

        //now create the subscription for customer
        $subscriptionPriceId = getSubscriptionPrice('prod_M3upR6IO07KW8I'); //default
        createSubscriptionForCustomer($customer, $subscriptionPriceId);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

function createSubscriptionForCustomer($customer, $stripeSubscriptionId)
{
    try {
        $subscriptionPriceId = getSubscriptionPrice($stripeSubscriptionId);
        $subscription = \Stripe\Subscription::create([
            'customer' => $customer->id, 
            'items' => [['price' => $subscriptionPriceId]], 
            'payment_behavior' => 'default_incomplete', // 🕐 Creates a subscription but waits for payment

            'expand' => ['latest_invoice.payment_intent']
        ]);
        $paymentIntent = $subscription->latest_invoice->payment_intent;

        echo "✅ Subscription created: " . $subscription->id;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

// ✅ Function to Retrieve a Payment Intent
function getPaymentIntent($paymentIntentId)
{
    try {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        echo json_encode($paymentIntent);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

//function to get the subscription price with subscription ID
function getSubscriptionPrice($subscriptionId)
{
    try {
        $prices = \Stripe\Price::all(['product' => $subscriptionId]);

        foreach ($prices->data as $price) {
            return $price->id;
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

// ✅ Function to Cancel a Payment Intent
function cancelPaymentIntent($paymentIntentId)
{
    try {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        $paymentIntent->cancel();
        echo json_encode(['message' => 'Payment Intent Cancelled']);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// ✅ Handle Incoming JSON Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['action'])) {
        echo json_encode(['error' => 'No action provided']);
        exit;
    }

    switch ($data['action']) {
        case 'createPaymentIntent':
            if (isset($data['amount']) && is_numeric($data['amount'])) {
                makeStripePaymentIntent(intval($data['amount']));
            } else {
                echo json_encode(['error' => 'Invalid amount']);
            }
            break;

        case 'retrievePaymentIntent':
            if (isset($data['paymentIntentId'])) {
                getPaymentIntent($data['paymentIntentId']);
            } else {
                echo json_encode(['error' => 'Missing paymentIntentId']);
            }
            break;

        case 'cancelPaymentIntent':
            if (isset($data['paymentIntentId'])) {
                cancelPaymentIntent($data['paymentIntentId']);
            } else {
                echo json_encode(['error' => 'Missing paymentIntentId']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    exit;
}


?>
<script>


</script>