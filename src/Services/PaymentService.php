<?php

namespace App\Services;

use App\Providers\PaymentProviderInterface;
use App\User\User;

class PaymentService
{
    public function __construct(
        private PaymentProviderInterface $payment,
        private float $amount,
        private User $user // username and email input
    ) {
        //
    }

    public function execute()
    {
        return $this->payment->charge(
            $this->amount,
            $this->user
        );
    }
}
