<?php

namespace App\Services;

use App\User\User;

class PayPalPaymentProvider extends BasePaymentService
{
    public function __construct()
    {
        parent::__construct(
            deliverCost: 2.50,
            bankTransferCost: 0.00
        );
    }

    protected function buildRedirectUrl(float $amount, User $user): string
    {
        // Build payment specific redirect URL
        $userEmail = urlencode($user->getEmail());
        $formattedAmount = number_format($amount, 2, '.', '');

        return "http://partseurope.info/tesQng-paypal/{$userEmail}/{$formattedAmount}";
    }
}
