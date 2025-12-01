<?php

//client code

require_once 'vendor/autoload.php';

use App\Providers\CreditCardPaymentProvider;
use App\Services\PaymentService;
use App\User\User;

$user = new User("tapas", "tapas@example.com");


//example code for Credit Card Payment
$paymentService = new PaymentService(
    new CreditCardPaymentProvider(),
    100,
    $user //username and email
);

try {
    $result = $paymentService->execute();

    echo "Payment processed successfully:\n";
    echo "Total Amount: $" . $result['totalAmount'] . "\n";
    echo "Total with VAT: $" . $result['totalVatAmount'] . "\n";
    echo "Redirect URL: " . $result['redirectUrl'] . "\n\n";
} catch (Exception $e) {
    echo "Payment failed: " . $e->getMessage() . "\n";
}
