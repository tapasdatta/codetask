<?php

namespace App\Services;

use App\User\User;

abstract class BasePaymentService implements PaymentProviderInterface
{
    protected $deliverCost;
    protected $bankTransferCost;

    public function __construct($deliverCost, $bankTransferCost)
    {
        $this->deliverCost = $deliverCost;
        $this->bankTransferCost = $bankTransferCost;
    }

    abstract protected function buildRedirectUrl(float $amount, User $user): string;

    public function charge(float $amount, User $user): array
    {
        $redirectUrl = $this->buildRedirectUrl($amount, $user);

        $totalAmount = $amount + $this->deliverCost + $this->bankTransferCost;
        $totalVatAmount = $totalAmount * 1.18;

        return [
            "totalAmount" => round($totalAmount, 2),
            "totalVatAmount" => round($totalVatAmount, 2),
            "redirectUrl" => $redirectUrl,
        ];
    }
}
