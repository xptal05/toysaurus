<?php
require '../stripe/stripe.php';
require_once '/stripe-php/init.php';


// To finnish when uploaded online -> for subscription renewals initiated by stripe

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    // ✅ Verify Webhook Signature
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpointSecret
    );

    // ✅ Handle Different Webhook Events
    switch ($event->type) {
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
            file_put_contents('payments.log', "✅ Subscription Renewed: " . $invoice->id . "\n", FILE_APPEND);
            break;

        case 'invoice.payment_failed':
            $invoice = $event->data->object;
            file_put_contents('payments.log', "❌ Payment Failed: " . $invoice->id . "\n", FILE_APPEND);
            break;

        case 'customer.subscription.deleted':
            $subscription = $event->data->object;
            file_put_contents('payments.log', "🚨 Subscription Canceled: " . $subscription->id . "\n", FILE_APPEND);
            break;

        default:
            file_put_contents('payments.log', "ℹ️ Unhandled Event: " . $event->type . "\n", FILE_APPEND);
    }

    http_response_code(200); // ✅ Send success response
} catch (\Exception $e) {
    http_response_code(400);
    file_put_contents('payments.log', "❌ Webhook Error: " . $e->getMessage() . "\n", FILE_APPEND);
    exit();
}
