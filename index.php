<?php

//client code

require_once 'vendor/autoload.php';

use App\Services\CreditCardPaymentProvider;
use App\Services\PaymentService;
use App\User\User;

// Create a test user
$user = new User("tapas", "tapas@example.com");

$paymentService = new PaymentService(
    new CreditCardPaymentProvider(),
    100,
    $user
);

try {
    // Process payment for 
    $result = $paymentService->process();

    echo "Payment processed successfully:\n";
    echo "Total Amount: $" . $result['totalAmount'] . "\n";
    echo "Total with VAT: $" . $result['totalVatAmount'] . "\n";
    echo "Redirect URL: " . $result['redirectUrl'] . "\n\n";
} catch (Exception $e) {
    echo "Payment failed: " . $e->getMessage() . "\n";
}
