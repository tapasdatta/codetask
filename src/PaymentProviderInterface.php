<?php

namespace App;

interface PaymentProviderInterface
{
    public function charge($amount, $user);
}
