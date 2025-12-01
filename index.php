<?php

require_once 'vendor/autoload.php';

use App\Services\PaymentService;
use App\Services\PaymentProviderFactory;
use App\User\User;

// Create user
$user = new User("john_doe", "john@example.com");

// Create payment service with Stripe provider
$stripeProvider = PaymentProviderFactory::create('stripe');
$paymentService = new PaymentService($stripeProvider);

try {
    // Process payment
    $result = $paymentService->processPayment(100.00, $user);

    echo "Payment processed successfully:\n";
    echo "Total Amount: $" . $result['totalAmount'] . "\n";
    echo "Total with VAT: $" . $result['totalVatAmount'] . "\n";
    echo "Redirect URL: " . $result['redirectUrl'] . "\n\n";

    // Switch to PayPal provider
    $paypalProvider = PaymentProviderFactory::create('paypal');
    $paymentService->setProvider($paypalProvider);

    $result2 = $paymentService->processPayment(50.00, $user);

    echo "PayPal Payment processed successfully:\n";
    echo "Total Amount: $" . $result2['totalAmount'] . "\n";
    echo "Total with VAT: $" . $result2['totalVatAmount'] . "\n";
    echo "Redirect URL: " . $result2['redirectUrl'] . "\n";
} catch (Exception $e) {
    echo "Payment failed: " . $e->getMessage() . "\n";
}
