<?php

namespace App\Providers;

use App\User\User;

class BankTransferPaymentProvider extends BasePaymentService
{
    public function __construct()
    {
        parent::__construct(
            deliverCost: 0.00,
            bankTransferCost: 1.40
        );
    }

    protected function buildRedirectUrl(float $amount, User $user): string
    {
        // Build payment specific redirect URL with hashed username
        $hashedUsername = md5($user->getUsername());
        $formattedAmount = number_format($amount, 2, '.', '');

        return "https://partseurope.info/testing-bank-transfer/{$hashedUsername}/{$formattedAmount}";
    }
}
