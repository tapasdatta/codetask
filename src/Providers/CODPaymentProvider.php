<?php

namespace App\Providers;

use App\User\User;

class CODPaymentProvider extends BasePaymentService
{
    public function __construct()
    {
        parent::__construct(
            deliverCost: 5.50,
            bankTransferCost: 0.00
        );
    }

    protected function buildRedirectUrl(float $amount, User $user): string
    {
        // Build payment specific redirect URL with 10-character hash string
        $hashed = substr(str_shuffle($user->getUsername()), 0, 10);
        $formattedAmount = number_format($amount, 2, '.', '');

        return "https://partseurope.info/testing-cod/{$hashed}/{$formattedAmount}";
    }
}
