<?php

namespace App\Providers;

use App\User\User;

class CreditCardPaymentProvider extends BasePaymentService
{
    public function __construct()
    {
        parent::__construct(
            deliverCost: 2.00,
            bankTransferCost: 0.40
        );
    }

    protected function buildRedirectUrl(float $amount, User $user): string
    {
        // Build payment specific redirect URL
        $username = urlencode($user->getUsername());
        $formattedAmount = number_format($amount, 2, '.', '');

        return "https://partseurope.info/testing-credit-card/{$username}/{$formattedAmount}";
    }
}
