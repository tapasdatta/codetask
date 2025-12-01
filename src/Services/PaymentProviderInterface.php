<?php

namespace App\Services;

use App\User\User;

interface PaymentProviderInterface
{
    public function charge(float $amount, User $user);
}
