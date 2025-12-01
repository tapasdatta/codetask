<?php

namespace App;

use App\PaymentProviderInterface;
use App\User;


class Payment
{
    public function __construct(
        private PaymentProviderInterface $payment,
        private float $amount,
        private User $user // username and email input
    ) {
        //
    }

    public function pay()
    {
        return $this->payment->charge(
            $this->amount,
            $this->user
        );
    }
}
